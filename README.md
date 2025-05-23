# PHP Password Manager

## Project Overview

This project, developed as part of a PHP OOP programming assignment, creates a web application for generating and securely storing passwords using PHP and MySQL. The application allows users to sign up, log in, generate random passwords based on specified parameters, save them with associated website names, and view their stored passwords securely. The project was assigned by our instructor, Donatas Sir, with the goal of demonstrating object-oriented programming (OOP) principles, database management, and secure password handling.

### Project Requirements
The project fulfills the following requirements outlined by Donatas Sir:

1. **PHP OOP Implementation**: The application is built using PHP with an object-oriented approach, utilizing classes like `Database`, `Encryption`, `User`, `PasswordGenerator`, and `PasswordManager` to encapsulate functionality.
2. **MySQL Database**: Data is stored in a MySQL database named `password_manager`, with two tables: `users` (for user data) and `passwords` (for stored passwords).
3. **User Signup with Hashed Password**: During signup, a user’s password is hashed using Bcrypt (`password_hash`) and stored in the `users` table.
4. **Encryption Key Generation**: Upon signup, a unique encryption key is generated for each user. This key is encrypted using AES-256-CBC with the user’s plain password and is used to encrypt/decrypt stored passwords. Note: The key remains unchanged throughout the user’s existence, and re-encrypting the key during password changes is not implemented in this version.
5. **Password Generation**: The `PasswordGenerator` class generates passwords based on user-specified parameters (e.g., length, number of uppercase, lowercase, numbers, and special characters). For example, a 9-character password with 3 uppercase, 2 lowercase, 2 numbers, and 2 special characters might be `aF$3E.D5s`.
6. **Password Storage**: Users can save passwords with associated website names (e.g., “Gmail”). Each entry in the `passwords` table includes the website, encrypted password, and an automatically generated timestamp (`created_at`).
7. **UML Diagrams**: Class and database diagrams are included in the report to illustrate the application’s structure and database schema.

### Assessment Criteria
The project addresses the following assessment criteria:
1. **Functionality**: The application works as intended—users can sign up, log in, generate passwords, save them, and view them securely.
2. **Code Quality**: The code is organized using OOP principles, with classes for specific responsibilities (e.g., `Encryption` for cryptography, `User` for authentication). Methods are well-defined, and inheritance could be explored in future iterations.
3. **Database Design**: The database schema is optimized with appropriate data types (e.g., `varchar(50)` for usernames, `text` for encrypted data) and a foreign key relationship between `users` and `passwords` with `ON DELETE CASCADE`.
4. **User Interface**: The UI is simple and functional, with forms for signup, login, password generation, and storage, styled minimally with CSS.

-----------------------------------------------------------------------------------

## Project Structure

The project is organized as follows:

PHP-FINAL-TASK/
├── classes/
│   ├── Database.php           # Manages MySQL database connection using PDO
│   ├── Encryption.php         # Handles AES-256-CBC encryption/decryption
│   ├── PasswordGenerator.php  # Generates random passwords based on parameters
│   ├── PasswordManager.php    # Manages password storage and retrieval
│   ├── User.php               # Handles user signup, login, and key management
├── css/
│   ├── styles.css             # Basic CSS for styling the UI
├── dashboard.php              # User dashboard to view passwords and access features
├── generate-password.php      # Form to generate passwords
├── index.php                  # Landing page with signup/login links
├── login.php                  # Login form
├── logout.php                 # Logout script
├── save-password.php          # Form to save generated passwords
├── signup.php                 # Signup form
├── test_db.php                # Test script for database connection
├── sql/
│   ├── database.sql           # SQL script to create the database and tables

----------------------------------------------------------------------------------


---

## Prerequisites

To run this project, you need the following installed on your system:
- XAMPP: A local server environment with Apache, MySQL, and phpMyAdmin.
- PHP: Version 8.0 or higher, with `openssl` and `pdo_mysql` extensions enabled.
- Check with `php -m` to confirm `openssl` and `pdo_mysql` are listed.
- MySQL: For storing user data and passwords.
- A Web Browser: To access the application (e.g., Chrome, Firefox).

---

## Setup Instructions

Follow these steps to set up and run the project on your local machine:

1. Clone the Repository:
   - If you’re using Git, clone the repository to your local machine:
     ```bash
     git clone <repository-url>
     ```
   - Alternatively, download the project files as a ZIP and extract them.

2. Move the Project to XAMPP’s htdocs:
   - Copy or move the `PHP-FINAL-TASK` folder to XAMPP’s `htdocs` directory:
     - Default path on Windows: `C:\xampp\htdocs\PHP-FINAL-TASK`.
     - Ensure the folder name matches (`PHP-FINAL-TASK`).

3. Start XAMPP Services:
   - Open the XAMPP Control Panel.
   - Start the **Apache** and **MySQL** modules (they should turn green, indicating they’re running).

4. Set Up the Database:
   - Open phpMyAdmin in your browser: `http://localhost/phpmyadmin`.
   - Import the database:
     - Click the “Import” tab.
     - Choose `PHP-FINAL-TASK/sql/database.sql`.
     - Click “Go” to create the `password_manager` database and tables (`users`, `passwords`).
   - Alternatively, manually create the database:
     - Create a new database named `password_manager`.
     - Go to the “SQL” tab, paste the following code, and click “Go”:
       ```sql
       -- Create the database
       CREATE DATABASE IF NOT EXISTS password_manager;
       USE password_manager;

       -- Create the users table
       CREATE TABLE users (
           id INT AUTO_INCREMENT PRIMARY KEY,
           username VARCHAR(50) NOT NULL UNIQUE,
           hashed_password VARCHAR(255) NOT NULL,
           encrypted_key TEXT NOT NULL
       );

       -- Create the passwords table
       CREATE TABLE passwords (
           id INT AUTO_INCREMENT PRIMARY KEY,
           user_id INT NOT NULL,
           website VARCHAR(100) NOT NULL,
           encrypted_password TEXT NOT NULL,
           created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
           FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
       );
       ```
   - Verify in phpMyAdmin that the `password_manager` database exists with the `users` and `passwords` tables.

5. Update Database Credentials:
   - Open `classes/Database.php` and update the MySQL credentials to match your setup:
     ```php
     $username = 'root'; // Your MySQL username (default: 'root' in XAMPP)
     $password = '';     // Your MySQL password (default: empty in XAMPP)
     ```
   - If you’ve set a MySQL password, update `$password` accordingly (e.g., `$password = 'mypassword';`).

6. Test the Database Connection:
   - Access `http://localhost/PHP-FINAL-TASK/test_db.php` in your browser.
   - Expected output: `Database connection successful!`.
   - If you see an error (e.g., “Access denied” or “Unknown database”), double-check the credentials in `Database.php` or ensure the database was created correctly.

---

## Usage Instructions

Here’s a step-by-step guide to using the password manager application:

1. Access the Application:
   - Open your browser and go to `http://localhost/PHP-FINAL-TASK/index.php`.
   - You’ll see the landing page with options to sign up or log in.

2. Sign Up:
   - Click “Sign Up” to go to `signup.php`.
   - Enter a username (e.g., `testuser`) and password (e.g., `test123456`).
   - Click “Sign Up”. If successful, you’ll be redirected to the dashboard (`dashboard.php`).
   - Note: The password is hashed using Bcrypt, and a unique encryption key is generated and encrypted with your plain password.

3. Log In:
   - If you already have an account, click “Login” on the landing page to go to `login.php`.
   - Enter your username and password.
   - Click “Login” to access the dashboard.

4. Generate a Password:
   - On the dashboard, click “Generate Password” to go to `generate-password.php`.
   - Enter the desired password parameters:
     - Total length (e.g., 9).
     - Number of uppercase letters (e.g., 3).
     - Number of lowercase letters (e.g., 2).
     - Number of numbers (e.g., 2).
     - Number of special characters (e.g., 2).
   - Click “Generate”. You’ll see a generated password (e.g., `aF$3E.D5s`).

5. Save the Password:
   - From the password generation page, click “Save This Password” to go to `save-password.php` with the generated password pre-filled.
   - Enter the website or program name (e.g., `Gmail`).
   - Enter your login password (e.g., `test123456`) to decrypt the key for encryption.
   - Click “Save Password”. The password is encrypted with your key and saved in the `passwords` table.
   - You’ll see a success message and be redirected to the dashboard.

6. View Saved Passwords:
   - On the dashboard, enter your login password in the “View Passwords” section.
   - Click “View Passwords”. The application will decrypt and display your saved passwords, including the website, password, and creation date.

7. **Log Out**:
   - On the dashboard, click “Logout” to end your session and return to the landing page.

---

## Troubleshooting

If you encounter issues while running the project, here are some common problems and solutions:

- Database Connection Fails:
  - **Error**: “Access denied for user 'root'@'localhost'”.
    - **Solution**: Update the `$username` and `$password` in `classes/Database.php` to match your MySQL credentials.
  - **Error**: “Unknown database 'password_manager'”.
    - **Solution**: Ensure you’ve imported `sql/database.sql` in phpMyAdmin or manually created the database (see Setup Instructions, Step 4).
  - **Error**: “Table 'password_manager.users' doesn’t exist”.
    - **Solution**: Verify the `users` and `passwords` tables were created in phpMyAdmin.

- File Not Found Errors:
  - **Error**: “Failed to open stream: No such file or directory”.
    - **Solution**: Check the `require_once` paths in the PHP files. For example, in `classes/User.php`, ensure `require_once 'Database.php'` points to the correct file in the `classes/` directory.

- Encryption Fails:
  - **Error**: Blank page or decryption errors when viewing passwords.
    - **Solution**: Ensure the `openssl` PHP extension is enabled (`php -m | findstr openssl`). If not, enable it in `php.ini` (`extension=openssl`) and restart Apache.

- UI Issues:
  - **Problem**: The UI looks unstyled or broken.
    - **Solution**: Ensure `css/styles.css` is in the `css/` directory and that the PHP files link to it correctly (`<link rel="stylesheet" href="css/styles.css">`).

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

---

## Acknowledgments

- Instructor: Donatas Sir, for providing the project requirements and guidance.
- Tools Used:
  - XAMPP for the local development environment.
  - phpMyAdmin for database management.
  - Mermaid Live Editor (mermaid.live) for creating UML diagrams.
  - Visual Studio Code for coding.

---

## Author

- Name: Fuad Mahmud Shad
- Date: May 23, 2025



