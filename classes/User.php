<?php
require_once 'Database.php'; 
require_once 'Encryption.php';

class User {
    private $db;
    private $encryption;

    public function __construct() {
        $this->db = (new Database())->getPdo();
        $this->encryption = new Encryption();
    }

    public function signup($username, $password) {
        // Validate inputs
        if (empty($username) || empty($password)) {
            throw new Exception('Username and password are required');
        }

        // Check if username exists
        $stmt = $this->db->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            throw new Exception('Username already exists');
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Generate and encrypt the user's KEY
        $key = $this->encryption->generateKey();
        $encryptedKey = $this->encryption->encrypt($key, $password);

        // Insert user into database
        $stmt = $this->db->prepare('INSERT INTO users (username, hashed_password, encrypted_key) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hashedPassword, $encryptedKey]);

        return $this->db->lastInsertId();
    }

    public function login($username, $password) {
        // Find user
        $stmt = $this->db->prepare('SELECT id, hashed_password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['hashed_password'])) {
            throw new Exception('Invalid username or password');
        }

        return $user['id'];
    }

    public function changePassword($userId, $newPassword) {
        // Get current encrypted key
        $stmt = $this->db->prepare('SELECT encrypted_key FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) {
            throw new Exception('User not found');
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password in the database
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