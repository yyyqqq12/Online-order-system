
<?php
class User {
    protected $conn;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUser() {
        // Placeholder method to be overridden by subclasses
    }

    public function login() {
        $query = $this->getUser();
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($this->password === $row['password']) {
                return true;
            }
        }
        return false;
    }
}
?>
