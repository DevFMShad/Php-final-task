<?php
require_once 'Database.php'; 
require_once 'Encryption.php';
require_once 'User.php';

class PasswordManager {
    private $db;
    private $encryption;
    private $user;

    public function __construct() {
        $this->db = (new Database())->getPdo();
        $this->encryption = new Encryption();
        $this->user = new User();
    }

    public function savePassword($userId, $website, $password, $userPassword) {
        // Validate inputs
        if (empty($website) || empty($password)) {
            throw new Exception('Website and password are required');
        }

        // Get user's encrypted key and decrypt it
        $encryptedKey = $this->user->getEncryptedKey($userId);
        $key = $this->encryption->decrypt($encryptedKey, $userPassword);

        // Encrypt the password with the user's key
        $encryptedPassword = $this->encryption->encrypt($password, $key);

        // Save to database
        $stmt = $this->db->prepare('INSERT INTO passwords (user_id, website, encrypted_password) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $website, $encryptedPassword]);

        return $this->db->lastInsertId();
    }

    public function getPasswords($userId, $userPassword) {
        // Get user's encrypted key and decrypt it
        $encryptedKey = $this->user->getEncryptedKey($userId);
        $key = $this->encryption->decrypt($encryptedKey, $userPassword);

        // Retrieve passwords
        $stmt = $this->db->prepare('SELECT id, website, encrypted_password, created_at FROM passwords WHERE user_id = ?');
        $stmt->execute([$userId]);
        $passwords = $stmt->fetchAll();

        // Decrypt passwords
        foreach ($passwords as &$entry) {
            $entry['password'] = $this->encryption->decrypt($entry['encrypted_password'], $key);
            unset($entry['encrypted_password']); // Remove encrypted password from output
        }

        return $passwords;
    }
}