<?php
// config_editor.php - Vulnerable configuration editor (LFI/RFI)
include 'config.php';

$file_contents = "";
$file_path = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['file_path'])) {
        // Vulnerable: No validation of file path (LFI/RFI)
        $file_path = $_POST['file_path'];
        
        if (isset($_POST['file_contents'])) {
            // Vulnerable: No sanitization of file contents
            $file_contents = $_POST['file_contents'];
            
            // Vulnerable: Allows writing to any file the web server has access to
            if (file_put_contents($file_path, $file_contents) !== false) {
                $message = "File saved successfully!";
            } else {
                $message = "Error saving file!";
            }
        } else {
            // Vulnerable: Allows reading any file the web server has access to
            if (file_exists($file_path)) {
                $file_contents = file_get_contents($file_path);
                $message = "File loaded successfully!";
            } else {
                $message = "File does not exist!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Editor - <?php echo $app_name; ?></title>
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            height: 300px;
            font-family: monospace;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
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
            <h1>Configuration Editor</h1>
        </header>
        
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="file_path">File Path:</label>
                <input type="text" id="file_path" name="file_path" value="<?php echo htmlspecialchars($file_path); ?>" required placeholder="e.g., config.php">
            </div>
            
            <div>
                <button type="submit" name="load">Load File</button>
            </div>
            
            <?php if (!empty($file_contents)) { ?>
                <div class="form-group" style="margin-top: 20px;">
                    <label for="file_contents">File Contents:</label>
                    <textarea id="file_contents" name="file_contents"><?php echo htmlspecialchars($file_contents); ?></textarea>
                </div>
                
                <div>
                    <button type="submit" name="save">Save File</button>
                </div>
            <?php } ?>
        </form>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>