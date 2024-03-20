<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV</title>
    <link rel="stylesheet" type="text/css" href="style_upload.css">
</head>
<body>
<div class="container">
    <div class="brand-title" style="margin-top: -50px; margin-bottom: 110px;">Upload CSV</div>
    <form method="post" enctype="multipart/form-data">
        <div class="selectcsv">
            <h4>Select CSV file:</h4>
            <input type="file" name="csvFile" id="csvFile" required accept=".csv">
        </div>
        <input type="submit" value="Insert Data"  class="submit-button">
        <input type="button" value="Generate Record" class="upload-button" onclick="window.location.href='index.php';">
    </form>
</div>
</body>
</html>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $csvFile = $_FILES['csvFile'];

    if ($csvFile['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('File upload failed.');</script>";
        exit;
    }

    // Check if the uploaded file is a CSV
    $fileType = pathinfo($csvFile['name'], PATHINFO_EXTENSION);
    if (strtolower($fileType) !== 'csv') {
        echo "<script>alert('Only CSV files are allowed.');</script>";
        exit;
    }

   
    $handle = fopen($csvFile['tmp_name'], 'r');
    if ($handle === false) {
        echo "<script>alert('Failed to open CSV file.');</script>";
        exit;
    }

    try {
        // Connect to SQLite database
        $pdo = new PDO('sqlite:mydb.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if not exists
        $createTableQuery = "
            CREATE TABLE IF NOT EXISTS csv_data (
                Id TEXT,
                Name TEXT,
                Surname TEXT,
                Initials TEXT,
                Age INTEGER,
                DateOfBirth DATE
            );
        ";
        $pdo->exec($createTableQuery);

       
        $clearTableQuery = "DELETE FROM csv_data";
        $pdo->exec($clearTableQuery);

        
        $query = "INSERT INTO csv_data (Id, Name, Surname, Initials, Age, DateOfBirth) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $pdo->prepare($query);

        // Batch 
        $batchSize = 1000; 
        $rowCount = 0;
        $isHeader = true;

       
        $pdo->beginTransaction();

        // Read file line by line
        while (($data = fgetcsv($handle)) !== false) {
            // Skip header row
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            
            $statement->execute($data);

            // Increment row count
            $rowCount++;

            
            if ($rowCount % $batchSize === 0) {
                $pdo->commit();
                $pdo->beginTransaction();
            }
        }

        
        $pdo->commit();

        
        $pdo = null;
        fclose($handle);

        echo "<script>alert('CSV data successfully imported into the database. Total records added: $rowCount');</script>";

    } catch (PDOException $e) {
        
        $pdo->rollBack();
        echo "<script>alert('Error importing CSV data: {$e->getMessage()}');</script>";
    }
}
?>
