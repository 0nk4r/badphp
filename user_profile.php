<?php
// user_profile.php - Vulnerable user profile page (XSS and CSRF)
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$update_message = "";

// Get user data
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vulnerable: No input validation or sanitization
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    
    // Vulnerable: No CSRF protection
    // Vulnerable: SQL Injection possible
    $update_query = "UPDATE users SET email = '$email', bio = '$bio' WHERE id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        $update_message = "Profile updated successfully!";
        
        // Refresh user data
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
    } else {
        $update_message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - <?php echo $app_name; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f7;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
        }
        header {
            padding: 20px 0;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            color: #333;
            font-size: 32px;
        }
        .user-info {
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .message {
            padding: 10px;
            margin: 15px 0;
            background: #e8f4fd;
            border-radius: 4px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .username {
            font-size: 20px;
            font-weight: bold;
        }
        .role {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>User Profile</h1>
        </header>
        
        <div class="user-info">
            <p>
                <span class="username"><?php echo $user['username']; ?></span>
                <span class="role"><?php echo $user['role']; ?></span>
            </p>
            
            <!-- Vulnerable: Outputs user-supplied content without escaping (XSS) -->
            <p><strong>Bio:</strong> <?php echo $user['bio']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        </div>
        
        <?php if (!empty($update_message)) { ?>
            <div class="message"><?php echo $update_message; ?></div>
        <?php } ?>
        
        <h2>Edit Profile</h2>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo $user['bio']; ?></textarea>
            </div>
            
            <button type="submit">Update Profile</button>
        </form>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>