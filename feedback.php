
<?php
// feedback.php - Vulnerable feedback form (CSRF, XSS, Email Header Injection)
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vulnerable: No CSRF protection
    // Vulnerable: No validation/sanitation of user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];
    
    // Vulnerable: Email header injection possible
    $to = "admin@example.com";
    $subject = "Feedback from $name";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    
    // Store in database
    $query = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$feedback')";
    
    if (mysqli_query($conn, $query)) {
        // Simulate email sending
        $message = "Thank you for your feedback! We will get back to you soon.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form - <?php echo $app_name; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f7;
        }
        .container {
            width: 90%;
            max-width: 600px;
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
            height: 150px;
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Feedback Form</h1>
        </header>
        
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="feedback">Your Feedback:</label>
                <textarea id="feedback" name="feedback" required></textarea>
            </div>
            
            <button type="submit">Submit Feedback</button>
        </form>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>