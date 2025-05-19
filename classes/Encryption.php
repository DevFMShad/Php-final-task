<?php
class Encryption {
    private $cipher = 'aes-256-cbc';
    
    public function encrypt($data, $key) {
        // Geenrating Vector for my prokeject
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        // Encrypting the given the data
        $encrypted = openssl_encrypt($data, $this->cipher, $key, 0, $iv);
        if ($encrypted === false) {
            throw new Exception('Encryption failed');
        }
        
        // Combinimng IV and encrypted data 
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data, $key) {
        // Decoding the base64-encoded data
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        
        // Extractingg IV and encrypted data
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        
        // Decryption of the giving real the data
        $decrypted = openssl_decrypt($encrypted, $this->cipher, $key, 0, $iv);
        if ($decrypted === false) {
            throw new Exception('Decryption failed');
        }
        
        return $decrypted;
    }

    public function generateKey() {
        // Generate a random 32-byte key for AES-256
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}


// this is just a prototype code O am working with right now, |I will change these codes once i have creached a conclusiomn