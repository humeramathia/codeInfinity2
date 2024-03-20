<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["num_records"])) {
    require_once('src/csv_generator.php');
    $num_records = $_POST["num_records"];
    generateCSV($num_records);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CSV Generator</title>
</head>
<body>
<div class="container">
<div class="brand-title" style="margin-top: -20px;">Generate Records</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="inputs">
        <h4>Number of Records:</h4>
        <input type="number" name="num_records" id="num_records" required placeholder= "Enter a Number">
        <input type="submit" value="Generate CSV" class="submit-button">
        <input type="button" value="Upload CSV" class="upload-button" onclick="window.location.href='upload.php';">
    </form>
</div>
</div>
</div>
</body>
</html>
<!-----------------------------------------------------------------END OF CODE------------------------------------------------------------->
