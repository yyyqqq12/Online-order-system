
<?php
include_once 'User.php';

class Customer extends User {
    public function getUser() {
        return "SELECT Username as username, Cus_pss as password FROM cus WHERE Username = :username";
    }
}
?>
