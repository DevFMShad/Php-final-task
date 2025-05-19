<?php
class PasswordGenerator {
    private $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    private $numbers = '0123456789';
    private $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    public function generate($length, $uppercaseCount, $lowercaseCount, $numbersCount, $specialCount) {
        // Validate parameters
        if ($length < ($uppercaseCount + $lowercaseCount + $numbersCount + $specialCount)) {
            throw new Exception('Total character counts exceed password length');
        }
        if ($length <= 0 || $uppercaseCount < 0 || $lowercaseCount < 0 || $numbersCount < 0 || $specialCount < 0) {
            throw new Exception('Invalid parameter values');
        }

        $password = [];
        
        // Add required characters
        $password = array_merge($password, $this->getRandomChars($this->uppercase, $uppercaseCount));
        $password = array_merge($password, $this->getRandomChars($this->lowercase, $lowercaseCount));
        $password = array_merge($password, $this->getRandomChars($this->numbers, $numbersCount));
        $password = array_merge($password, $this->getRandomChars($this->special, $specialCount));

        // Fill remaining length with random characters from all sets
        $remaining = $length - count($password);
        $allChars = $this->uppercase . $this->lowercase . $this->numbers . $this->special;
        $password = array_merge($password, $this->getRandomChars($allChars, $remaining));

        // Shuffle the password
        shuffle($password);
        return implode('', $password);
    }

    private function getRandomChars($charSet, $count) {
        $chars = [];
        for ($i = 0; $i < $count; $i++) {
            $chars[] = $charSet[random_int(0, strlen($charSet) - 1)];
        }
        return $chars;
    }
}