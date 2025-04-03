<?php
// pdf_generator.php - Vulnerable PDF generation (command injection)
include 'config.php';

$pdf_content = "";
$output_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdf_content = $_POST['content'];
    $filename = $_POST['filename'];
    
    // Vulnerable: Command injection possible as user input is passed to system command
    $temp_file = "temp/" . time() . ".txt";
    file_put_contents($temp_file, $pdf_content);
    
    // Vulnerable: Command injection through filename
    $output_file = "generated/" . $filename . ".pdf";
    
    // Using external tool to convert text to PDF (vulnerable to command injection)
    $command = "textpdf $temp_file $output_file 2>&1";
    $output = shell_exec($command);
    
    $output_message = "PDF has been generated. <a href='$output_file'>Download PDF</a>";
    
    // For demo purposes, show command output (revealing system details)
    if ($debug_mode) {
        $output_message .= "<pre>Command output: $output</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Generator - <?php echo $app_name; ?></title>
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
            height: 200px;
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
        .message {
            padding: 10px;
            margin: 15px 0;
            background: #e8f4fd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>PDF Generator</h1>
        </header>
        
        <?php if (!empty($output_message)) { ?>
            <div class="message"><?php echo $output_message; ?></div>
        <?php } ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="filename">Output File Name:</label>
                <input type="text" id="filename" name="filename" required placeholder="my_document">
            </div>
            
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" required placeholder="Enter the content for your PDF..."></textarea>
            </div>
            
            <button type="submit">Generate PDF</button>
        </form>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>