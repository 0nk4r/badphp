
<?php
// sql_query.php - Vulnerable SQL query builder
include 'config.php';

$query_result = null;
$query = "";
$tables = ["users", "products", "orders"]; // Available tables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vulnerable: Directly using user input in SQL query
    $table = $_POST['table'];
    $columns = $_POST['columns'];
    $where = $_POST['where'];
    
    // Building the SQL query from user input
    $query = "SELECT $columns FROM $table";
    if (!empty($where)) {
        $query .= " WHERE $where";
    }
    
    // Execute the query
    $query_result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Query Builder - <?php echo $app_name; ?></title>
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
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
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
        .results {
            margin-top: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .query {
            padding: 10px;
            background: #f8f8f8;
            border-left: 3px solid #3498db;
            margin-bottom: 15px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>SQL Query Builder</h1>
        </header>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="table">Select Table:</label>
                <select id="table" name="table" required>
                    <?php foreach ($tables as $table) { ?>
                        <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="columns">Columns (comma separated or *):</label>
                <input type="text" id="columns" name="columns" value="*" required>
            </div>
            
            <div class="form-group">
                <label for="where">WHERE Clause (optional):</label>
                <input type="text" id="where" name="where" placeholder="e.g. id = 1">
            </div>
            
            <button type="submit">Execute Query</button>
        </form>
        
        <?php if ($query_result !== null) { ?>
            <div class="results">
                <h3>Query:</h3>
                <div class="query"><?php echo $query; ?></div>
                
                <h3>Results:</h3>
                <?php if ($query_result && mysqli_num_rows($query_result) > 0) { ?>
                    <table>
                        <thead>
                            <tr>
                                <?php 
                                $field_info = mysqli_fetch_fields($query_result);
                                foreach ($field_info as $field) { 
                                    echo "<th>" . $field->name . "</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($query_result)) { ?>
                                <tr>
                                    <?php foreach ($row as $value) { ?>
                                        <td><?php echo $value; ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No results or error in query.</p>
                    <?php if (!$query_result) { 
                        // Vulnerable: Exposing error details
                        echo "<p>Error: " . mysqli_error($conn) . "</p>";
                    } ?>
                <?php } ?>
            </div>
        <?php } ?>
        
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>