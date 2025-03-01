
<?php
// index.php - Main entry point
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $app_name; ?> - Vulnerable By Design</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f7;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
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
        p.subtitle {
            color: #666;
            margin: 5px 0 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .tile {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            position: relative;
            transition: all 0.2s ease;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-decoration: none;
            color: #333;
        }
        .tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .tile-number {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 24px;
            height: 24px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $app_name; ?></h1>
            <p class="subtitle">Vulnerable By Design - For Educational Purposes Only</p>
        </header>
        
        <div class="grid">
            <a href="login.php" class="tile">
                <div class="tile-number">1</div>
                <h3>Login System</h3>
            </a>
            <a href="file_upload.php" class="tile">
                <div class="tile-number">2</div>
                <h3>File Upload</h3>
            </a>
            <a href="sql_query.php" class="tile">
                <div class="tile-number">3</div>
                <h3>SQL Query Builder</h3>
            </a>
            <a href="pdf_generator.php" class="tile">
                <div class="tile-number">4</div>
                <h3>PDF Generator</h3>
            </a>
            <a href="user_profile.php" class="tile">
                <div class="tile-number">5</div>
                <h3>User Profile</h3>
            </a>
            <a href="search.php" class="tile">
                <div class="tile-number">6</div>
                <h3>Search Function</h3>
            </a>
            <a href="api.php" class="tile">
                <div class="tile-number">7</div>
                <h3>API Endpoint</h3>
            </a>
            <a href="config_editor.php" class="tile">
                <div class="tile-number">8</div>
                <h3>Config Editor</h3>
            </a>
            <a href="feedback.php" class="tile">
                <div class="tile-number">9</div>
                <h3>Feedback Form</h3>
            </a>
        </div>
    </div>
</body>
</html>