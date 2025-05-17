<?php

class User {
    private $db;
    private $encryption;

    public function __construct() {
        $this->db = (new Database())->getPdo();
        $this->encryption = new Encryption();
    }

    public function signup($username, $password) {
        // Hash password, generate and encrypt KEY, store in DB
    }

    public function login($username, $password) {
        // Verify password, start session
    }

    public function changePassword($userId, $newPassword) {
        // Re-encrypt KEY with new password, update hashed password
    }

    public function getEncryptedKey($userId) {
        // Retrieve encrypted KEY from DB
    }
}


// In here, I am only trying to come up with the coding files and their systems. And the functions that I may need and I am keeping them private so that when I am trying to access them 
// in the future, I don't get a compile error. And all of these are just experimental. All of it might change in the future or I just might delete something or not or I may add something
// So please think about it as just experimenting with things. 
// Let me see what comes up with this and then change that