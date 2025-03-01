<?php
// api.php - Vulnerable API endpoint (No authentication, information disclosure)
include 'config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Vulnerable: No proper authentication or rate limiting
// Vulnerable: Exposes sensitive database information

$response = array();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'get_users':
            // Vulnerable: Returns all user data including passwords
            $query = "SELECT * FROM users";
            $result = mysqli_query($conn, $query);
            
            $users = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            
            $response = [
                'status' => 'success',
                'data' => $users
            ];
            break;
            
        case 'get_products':
            $query = "SELECT * FROM products";
            $result = mysqli_query($conn, $query);
            
            $products = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            
            $response = [
                'status' => 'success',
                'data' => $products
            ];
            break;
            
        case 'get_user':
            // Vulnerable: No validation of user ID parameter
            if (isset($_GET['id'])) {
                $user_id = $_GET['id'];
                $query = "SELECT * FROM users WHERE id = $user_id";
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    $response = [
                        'status' => 'success',
                        'data' => mysqli_fetch_assoc($result)
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'User not found'
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Missing user ID'
                ];
            }
            break;
            
        case 'server_info':
            // Vulnerable: Exposes server information
            $response = [
                'status' => 'success',
                'server_info' => [
                    'php_version' => phpversion(),
                    'server_software' => $_SERVER['SERVER_SOFTWARE'],
                    'server_name' => $_SERVER['SERVER_NAME'],
                    'document_root' => $_SERVER['DOCUMENT_ROOT'],
                    'database_info' => mysqli_get_server_info($conn)
                ]
            ];
            break;
            
        default:
            $response = [
                'status' => 'error',
                'message' => 'Invalid action'
            ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'No action specified'
    ];
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>