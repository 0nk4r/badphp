<?php
// file_upload.php - Vulnerable file upload system
include 'config.php';

$target_dir = "uploads/";
$upload_status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vulnerable: Not validating file type properly
    if (isset($_FILES["fileToUpload"])) {
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        
        // Vulnerable: Moving the file without proper checks
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $upload_status = "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            $upload_status = "Error uploading file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload - <?php echo $app_name; ?></title>
    <!-- Same CSS as index.php with form styling -->
    <style>
        /* Copy relevant styles here */
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
        .status {
            padding: 10px;
            margin-bottom: 15px;
            background: #e8f4fd;
            border-radius: 4px;
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
            <h1>File Upload</h1>
        </header>
        
        <?php if (!empty($upload_status)) { ?>
            <div class="status"><?php echo $upload_status; ?></div>
        <?php } ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Select file to upload:</label>
                <input type="file" id="fileToUpload" name="fileToUpload" required>
            </div>
            
            <button type="submit">Upload File</button>
        </form>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>