<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

function pp($arr, $die = "true")
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    if ($die == 'true') {
        die();
    }
}

function generatePassword($length = 12) {
    // Define the allowed characters for the password
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    
    // Generate a random password
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $password;
}

function isPasswordUsed($password) {
    // Fetch all hashed passwords from the User model
    $hashedPasswords = User::pluck('password')->toArray();

    // Check if the password (hashed) exists in the database
    foreach ($hashedPasswords as $hashedPassword) {
        if (Hash::check($password, $hashedPassword)) {
            return true; // Password already used
        }
    }

    return false; // Password is not used
}

function generateUniquePassword() {
    // Keep generating a password until a unique one is found
    do {
        $password = generatePassword();
    } while (isPasswordUsed($password));

    return $password; // Return the unique password
}