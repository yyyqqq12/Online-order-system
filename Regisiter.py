class User:
    def __init__(self, user_id, name, email):
        self.user_id = user_id
        self.name = name
        self.email = email

    def display_info(self):
        return f"User ID: {self.user_id}, Name: {self.name}, Email: {self.email}"

class Manager(User):
    def __init__(self, user_id, name, email, department):
        super().__init__(user_id, name, email)
        self.department = department

    def display_info(self):
        base_info = super().display_info()
        return f"{base_info}, Department: {self.department}"

class Customer(User):
    def __init__(self, user_id, name, email, loyalty_points):
        super().__init__(user_id, name, email)
        self.loyalty_points = loyalty_points

    def display_info(self):
        base_info = super().display_info()
        return f"{base_info}, Loyalty Points: {self.loyalty_points}"

class Admin(User):
    def __init__(self, user_id, name, email, privileges):
        super().__init__(user_id, name, email)
        self.privileges = privileges

    def display_info(self):
        base_info = super().display_info()
        return f"{base_info}, Privileges: {', '.join(self.privileges)}"

class Waitress(User):
    def __init__(self, user_id, name, email, shift):
        super().__init__(user_id, name, email)
        self.shift = shift

    def display_info(self):
        base_info = super().display_info()
        return f"{base_info}, Shift: {self.shift}"

# Example usage:
manager = Manager(1, "Alice", "alice@example.com", "Sales")
customer = Customer(2, "Bob", "bob@example.com", 150)
admin = Admin(3, "Charlie", "charlie@example.com", ["add_user", "delete_user"])
waitress = Waitress(4, "Daisy", "daisy@example.com", "Evening")

print(manager.display_info())
print(customer.display_info())
print(admin.display_info())
print(waitress.display_info())