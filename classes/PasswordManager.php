<?php

class PasswordManager {
    private $db;
    private $encryption;

    public function __construct() {
        $this->db = (new Database())->getPdo();
        $this->encryption = new Encryption();
    }

    public function savePassword() {
        
    }

    public function getPasswords() {
        
    }
}



// I am continuing my process for creating skeleton codes
// I will move on from this after I have a definite path I want to taake 