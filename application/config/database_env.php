<?php
/**
 * Environment-Specific Database Configuration
 * 
 * This file loads different database configurations based on environment
 */

// Load environment variables from .env file if not already loaded
if (file_exists(FCPATH . '.env') && !getenv('DB_HOST')) {
    $lines = file(FCPATH . '.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            // Set environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Get environment
$environment = getenv('APP_ENV') ?: 'production';

// Define environment-specific configurations
$configurations = [
    'development' => [
        'hostname' => getenv('DB_HOST') ?: 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'database' => getenv('DB_DATABASE') ?: 'msdlearning_dev',
    ],
    'testing' => [
        'hostname' => getenv('DB_HOST') ?: 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'database' => getenv('DB_DATABASE') ?: 'msdlearning_test',
    ],
    'production' => [
        'hostname' => getenv('DB_HOST') ?: 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'database' => getenv('DB_DATABASE') ?: 'msdlearning_prod',
    ]
];

// Get configuration for current environment
$db_config = isset($configurations[$environment]) ? $configurations[$environment] : $configurations['production'];

// Validate that required environment variables are set
$required_vars = ['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];
$missing_vars = [];

foreach ($required_vars as $var) {
    if (!getenv($var)) {
        $missing_vars[] = $var;
    }
}

// Debug information (remove in production)
if (getenv('APP_DEBUG') === 'true') {
    error_log("Environment: $environment");
    error_log("DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET'));
    error_log("DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'NOT SET'));
    error_log("DB_PASSWORD: " . (getenv('DB_PASSWORD') ? 'SET' : 'NOT SET'));
    error_log("DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET'));
}

// If critical variables are missing, show error
if (!empty($missing_vars) && $environment === 'production') {
    die('Error: Missing required environment variables: ' . implode(', ', $missing_vars));
}

// Log warning for development
if (!empty($missing_vars) && $environment === 'development') {
    error_log('Warning: Missing environment variables: ' . implode(', ', $missing_vars));
}

return $db_config;
