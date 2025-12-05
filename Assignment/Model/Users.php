<?php
require_once __DIR__ . '/../ObserverPattern/Subject.php';

class Users implements Subject{
    private $UserID;
    private $UserName;
    private $Password;
    private $Email;
    private $Phone;
    private $Role;
    private $FailedAttempts;
    private $LockUntil;

    public function __construct($UserID, $UserName, $Password, $Email, $Phone, $Role = 'user', $FailedAttempts = 0, $LockUntil = null) {
        $this->UserID = $UserID;
        $this->UserName = $UserName;
        $this->Password = $Password;
        $this->Email = $Email;
        $this->Phone = $Phone;
        $this->Role = $Role;
        $this->FailedAttempts = $FailedAttempts;
        $this->LockUntil = $LockUntil;
    }

    //Observer Design Pattern (Concrete Subject)
    #[\Override]
    public function add(Observer $observer): void {
        $this->observers[] = $observer;
    }

    #[\Override]
    public function remove(Observer $observer): void {
        $this->observers = array_filter($this->observers, fn($obs) => $obs !== $observer);
    }

    #[\Override]
    public function notify(): void {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    
    //Getter
    public function getUserID() {
        return $this->UserID;
    }

    public function getUserName() {
        return $this->UserName;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function getPhone() {
        return $this->Phone;
    }

    public function getRole() {
        return $this->Role;
    }
    
    public function getFailedAttempts() {
        return $this->FailedAttempts;
    }

    public function getLockUntil() {
        return $this->LockUntil;
    }

    //Setter
    public function setUserID($id) {
        $this->UserID = (int) $id;
    }

    public function setUserName($name) {
        $this->UserName = $name;
    }

    public function setPassword($password) {
        $this->Password = $password;
    }

    public function setEmail($email) {
        $this->Email = $email;
    }

    public function setPhone($Phone) {
        $this->Phone = $Phone;
    }

    public function setRole($role) {
        $this->Role = $role;
    }

    public function setFailedAttempts($attempts) {
        $this->FailedAttempts = $attempts;
    }

    public function setLockUntil($lockUntil) {
        $this->LockUntil = $lockUntil;
    }

    //ORM Methods
    public function insert() {
        $db = Db::getInstance();
        $db->query(
            "INSERT INTO users (UserName, Password, Email, Phone, Role) VALUES (?, ?, ?, ?, ?)",
            [$this->UserName, $this->Password, $this->Email, $this->Phone, $this->Role]
        );
    }
    
    public static function all() {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM users");
        return array_map(fn($row) => new self(
                        $row['UserID'],
                        $row['UserName'],
                        $row['Password'],
                        $row['Email'],
                        $row['Phone'],
                        $row['Role'],
                        $row['failed_attempts'],
                        $row['lock_until']
                ), $rows);
    }
    
    public static function find($id) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM users WHERE UserID = ?", [$id]);
        if (!$rows) {
            return null;
        }

        $row = $rows[0];
        return new self(
                $row['UserID'],
                $row['UserName'],
                $row['Password'],
                $row['Email'],
                $row['Phone'],
                $row['Role'],
                $row['failed_attempts'],
                $row['lock_until']
        );
    }

    public static function findByEmail($email) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM users WHERE Email = ?", [$email]);
        if (!$rows) {
            return null;
        }

        $row = $rows[0];
        return new self(
                $row['UserID'],
                $row['UserName'],
                $row['Password'],
                $row['Email'],
                $row['Phone'],
                $row['Role'],
                $row['failed_attempts'],
                $row['lock_until']
        );
    }
    
    public static function findByUsername($userName) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM users WHERE UserName = ?", [$userName]);
        if (!$rows) {
            return null;
        }

        $row = $rows[0];
        return new self(
                $row['UserID'],
                $row['UserName'],
                $row['Password'],
                $row['Email'],
                $row['Phone'],
                $row['Role'],
                $row['failed_attempts'],
                $row['lock_until']
        );
    }
    
    public function update() {
        $db = Db::getInstance();
        $db->query(
                "UPDATE users SET UserName = ?, Password = ?, Email = ?, Phone = ?, Role = ?, failed_attempts = ?, lock_until = ? WHERE UserID = ?",
                [$this->UserName, $this->Password, $this->Email, $this->Phone, $this->Role, $this->FailedAttempts, $this->LockUntil, $this->UserID]
        );
    }
    
    public function delete() {
        $db = Db::getInstance();
        $db->query("DELETE FROM users WHERE UserID = ?", [$this->UserID]);
    }
    
    public function verifyUnique(){
        $db = Db::getInstance();

            $rows = $db->query("SELECT * FROM users WHERE UserName = :username OR Email = :email OR Phone = :phone", 
            [':username' => $this->UserName,':email' => $this->Email,':phone' => $this->Phone]);

            $errors = [];

            foreach ($rows as $row) {
                if ($row['UserName'] === $this->UserName){
                    $errors[] = "Username already exists.";
                }
                if ($row['Email'] === $this->Email){
                    $errors[] = "Email already exists.";
                }
                if ($row['Phone'] === $this->Phone){
                    $errors[] = "Phone Number already exists.";
                }
            }

            return $errors ? implode("\n", $errors) : null;
    }
    
    public function verifyEdit($username, $email, $phone) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT UserName, Email, Phone FROM users WHERE (UserName = ? OR Email = ? OR Phone = ?) AND UserID != ?",
                [$username, $email, $phone, $this->UserID]);

        if (!$rows) {
            return null;
        }

        $errorCode = 0;
        foreach ($rows as $row) {
            if ($row['UserName'] === $username){
                $errorCode |= 1;
            }
            if ($row['Email'] === $email){
                $errorCode |= 2;
            }
            if ($row['Phone'] === $phone){
                $errorCode |= 4;
            }
        }

        return $errorCode === 0 ? null : $errorCode;
    }
}
