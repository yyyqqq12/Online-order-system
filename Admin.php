
<?php
include_once 'User.php';

class Admin extends User {
    public function getUser() {
        return "SELECT username, AD_pss as password FROM ad WHERE username = :username";
    }
}
?>
