<?php
// search.php - Vulnerable search function (SQL Injection, XSS)
include 'config.php';

$search_results = null;
$search_term = "";

if (isset($_GET['q'])) {
    // Vulnerable: No sanitization of search term
    $search_term = $_GET['q'];
    
    // Vulnerable: Direct SQL Injection possible
    $query = "SELECT * FROM products WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'";
    $search_results = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - <?php echo $app_name; ?></title>
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
        .search-form {
            display: flex;
            margin-bottom: 20px;
        }
        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 16px;
        }
        .search-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-size: 16px;
        }
        .results {
            margin-top: 20px;
        }
        .result-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .result-item:last-child {
            border-bottom: none;
        }
        .result-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .result-price {
            color: #3498db;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .no-results {
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Product Search</h1>
        </header>
        
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="q" class="search-input" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Search for products..." required>
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php if ($search_results !== null) { ?>
            <div class="results">
                <!-- Vulnerable: Directly echoing search term without escaping -->
                <h2>Search Results for: <?php echo $search_term; ?></h2>
                
                <?php if (mysqli_num_rows($search_results) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($search_results)) { ?>
                        <div class="result-item">
                            <!-- Vulnerable: Directly echoing database results without escaping -->
                            <div class="result-title"><?php echo $row['name']; ?></div>
                            <div class="result-price">$<?php echo $row['price']; ?></div>
                            <p><?php echo $row['description']; ?></p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="no-results">
                        <p>No products found matching your search.</p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>
