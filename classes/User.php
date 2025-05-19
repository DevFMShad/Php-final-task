<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Encryption.php';

class User {
    private $db;
    private $encryption;

    public function __construct() {
        $this->db = (new Database())->getPdo();
        $this->encryption = new Encryption();
    }

    public function signup($username, $password) {
        // Validating inputs
        if (empty($username) || empty($password)) {
            throw new Exception('Username and password are required');
        }

        // Checking if username exists
        $stmt = $this->db->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            throw new Exception('Username already exists');
        }

        // Hashing the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Generating and encrypt the user's KEY
        $key = $this->encryption->generateKey();
        $encryptedKey = $this->encryption->encrypt($key, $password);

        // Inserting user into database
        $stmt = $this->db->prepare('INSERT INTO users (username, hashed_password, encrypted_key) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hashedPassword, $encryptedKey]);

        return $this->db->lastInsertId();
    }

    public function login($username, $password) {
        // Finding the user
        $stmt = $this->db->prepare('SELECT id, hashed_password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['hashed_password'])) {
            throw new Exception('Invalid username or password');
        }

        return $user['id'];
    }

    public function changePassword($userId, $newPassword) {
        // Getting current encrypted key
        $stmt = $this->db->prepare('SELECT encrypted_key FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) {
            throw new Exception('User not found');
        }

        // Decryptong  the key with the old password 
        $encryptedKey = $user['encrypted_key'];
        // Since we don't have the old password here, this is a placeholder for re-encryption

        // Hashing the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Updating the password in the database 
        $stmt = $this->db->prepare('UPDATE users SET hashed_password = ? WHERE id = ?');
        $stmt->execute([$hashedPassword, $userId]);

    }

    public function getEncryptedKey($userId) {
        $stmt = $this->db->prepare('SELECT encrypted_key FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) {
            throw new Exception('User not found');
        }
        return $user['encrypted_key'];
    }
}