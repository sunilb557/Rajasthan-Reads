<?php
require_once 'config.php';
require_once 'app/core/Database.php';
require_once 'app/core/Model.php';
require_once 'app/models/User.php';

$userData = [
    'username' => 'admin',
    'email' => 'admin@rajasthanreads.com',
    'password' => 'admin123',
    'full_name' => 'System Administrator',
    'role' => 'admin',
    'email_verified' => 1
];

try {
    $userModel = new User();
    
    // Check if user exists
    if ($userModel->findByUsername($userData['username'])) {
        echo "User already exists.\n";
        exit;
    }
    
    $userId = $userModel->createUser($userData);
    
    // Explicitly set role to admin (in case createUser doesn't handle role from array if it's not in the model's structure, 
    // though User model uses $this->create($data) which handles all keys in $data)
    
    // Let's verify the role is set correctly
    $db = Database::getInstance()->getConnection();
    $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?")->execute([$userId]);
    
    echo "Admin user created successfully!\n";
    echo "Username: " . $userData['username'] . "\n";
    echo "Password: admin123\n";
    echo "Email: " . $userData['email'] . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
