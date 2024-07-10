<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant In-Shop Ordering System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        #search-bar {
            text-align: center;
            margin: 20px 0;
        }

        #search-input {
            padding: 10px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        section {
            padding: 20px;
        }

        .menu-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center;
        }

        .menu-item img {
            max-width: 200px; /* Set the maximum width */
            max-height: 200px; /* Set the maximum height */
            width: auto; /* Maintain aspect ratio */
            height: auto; /* Maintain aspect ratio */
            margin-right: 10px; /* Optional: Add some space between image and text */
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .hidden {
            display: none;
        }

        #order-notification {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #order-summary {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
        }

        #order-list {
            list-style: none;
            padding: 0;
        }

        #view-order {
            display: block;
            margin: 20px auto;
        }

        .delete-button {
            background-color: #FF5733;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
        }

        .quantity-input {
            width: 50px;
            margin-left: 10px;
            text-align: center;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
        }

        .quantity-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin: 0 5px;
        }

        .quantity-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Our Restaurant</h1>
        <p id="pax-display"></p>
    </header>

    <section id="search-bar">
        <input type="text" id="search-input" placeholder="Search for food...">
    </section>

    <section id="menu">
        <h2>Our Menu</h2>
        <!-- Menu items will be dynamically populated here -->
    </section>

    <div id="order-notification" class="hidden">Your order has been added!</div>

    <button id="view-order">View Order</button>
    <input type="hidden" id="table-id" value="<?php echo htmlspecialchars($_GET['table_id']); ?>">
    <section id="order-summary" class="hidden">
        <h2>Order Summary</h2>
        <ul id="order-list"></ul>
        <p>Total: RM<span id="total-amount">0</span></p>
        <button id="confirm-order">Confirm Order</button>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableId = document.getElementById('table-id').value;

            fetch('Get_Menu_Items.php')
                .then(response => response.json())
                .then(data => {
                    const menuSection = document.getElementById('menu');
                    data.forEach(item => {
                        const menuItem = document.createElement('div');
                        menuItem.classList.add('menu-item');
                        menuItem.dataset.itemId = item.id;
                        menuItem.dataset.item = item.name;
                        menuItem.dataset.price = item.price;

                        const imagePath = item.image_path ? item.image_path : 'default-image.jpg'; // Provide a default image if none exists

                        menuItem.innerHTML = `
                            <img src="${imagePath}" alt="${item.name}">
                            <div>
                                <p></p>
                                <h3>${item.name}</h3>
                                <p>${item.description} (RM${item.price})</p>
                                <button>Add to Order</button>
                            </div>
                        `;
                        menuSection.appendChild(menuItem);
                    });
                    addMenuEventListeners();
                });

            function addMenuEventListeners() {
                const order = [];
                const menuItems = document.querySelectorAll('.menu-item button');
                const orderList = document.getElementById('order-list');
                const totalAmount = document.getElementById('total-amount');
                const viewOrderButton = document.getElementById('view-order');
                const orderSummarySection = document.getElementById('order-summary');
                const orderNotification = document.getElementById('order-notification');
                const searchInput = document.getElementById('search-input');
                const confirmOrderButton = document.getElementById('confirm-order');

                menuItems.forEach(button => {
                    button.addEventListener('click', function() {
                        const parent = this.parentElement.parentElement;
                        const item = parent.getAttribute('data-item');
                        const price = parseFloat(parent.getAttribute('data-price'));
                        const itemId = parent.getAttribute('data-item-id');
                        const existingOrderItem = order.find(orderItem => orderItem.item_id === itemId);

                        if (existingOrderItem) {
                            existingOrderItem.quantity += 1;
                            existingOrderItem.total_price = existingOrderItem.quantity * price;
                        } else {
                            order.push({ item_id: itemId, item, price, quantity: 1, total_price: price });
                        }

                        showNotification();
                        updateOrderSummary();
                    });
                });

                viewOrderButton.addEventListener('click', function() {
                    orderSummarySection.classList.toggle('hidden');
                });

                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.toLowerCase();
                    const items = document.querySelectorAll('.menu-item');
                    items.forEach(item => {
                        const itemName = item.getAttribute('data-item').toLowerCase();
                        if (itemName.includes(query)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                confirmOrderButton.addEventListener('click', function() {
                    const tableId = document.getElementById('table-id').value;
                    const orderData = { table_id: tableId, order };
                    fetch('Store_Order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(orderData)
                    }).then(response => response.text())
                    .then(responseText => {
                        try {
                            const data = JSON.parse(responseText);
                            if (data.status === 'success') {
                                localStorage.setItem('order', JSON.stringify(order));
                                window.location.href = 'Receipt.html';
                            } else {
                                console.error('Server error:', data.message);
                            }
                        } catch (error) {
                            console.error('Error parsing response:', error);
                        }
                    }).catch(error => {
                        console.error('Error during fetch:', error);
                    });
                });

                function showNotification() {
                    orderNotification.classList.remove('hidden');
                    setTimeout(() => {
                        orderNotification.classList.add('hidden');
                    }, 1000);
                }

                function updateOrderSummary() {
                    orderList.innerHTML = '';
                    let total = 0;
                    order.forEach((orderItem, index) => {
                        const listItem = document.createElement('li');
                        listItem.textContent = `${orderItem.item} - RM${orderItem.price}`;

                        const quantityControls = document.createElement('div');
                        quantityControls.classList.add('quantity-controls');

                        const quantityInput = document.createElement('input');
                        quantityInput.type = 'number';
                        quantityInput.value = orderItem.quantity;
                        quantityInput.min = 1;
                        quantityInput.classList.add('quantity-input');

                        const increaseButton = document.createElement('button');
                        increaseButton.textContent = '+';
                        increaseButton.classList.add('quantity-button');
                        increaseButton.addEventListener('click', function() {
                            orderItem.quantity += 1;
                            orderItem.total_price = orderItem.quantity * orderItem.price;
                            updateOrderSummary();
                        });

                        const decreaseButton = document.createElement('button');
                        decreaseButton.textContent = '-';
                        decreaseButton.classList.add('quantity-button');
                        decreaseButton.addEventListener('click', function() {
                            if (orderItem.quantity > 1) {
                                orderItem.quantity -= 1;
                                orderItem.total_price = orderItem.quantity * orderItem.price;
                                updateOrderSummary();
                            }
                        });

                        quantityControls.appendChild(decreaseButton);
                        quantityControls.appendChild(quantityInput);
                        quantityControls.appendChild(increaseButton);

                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = 'Remove';
                        deleteButton.classList.add('delete-button');
                        deleteButton.addEventListener('click', function() {
                            order.splice(index, 1);
                            updateOrderSummary();
                        });

                        listItem.appendChild(quantityControls);
                        listItem.appendChild(deleteButton);
                        orderList.appendChild(listItem);
                        total += orderItem.total_price;
                    });
                    totalAmount.textContent = total.toFixed(2);
                }
            }
        });
    </script>
</body>
</html>
