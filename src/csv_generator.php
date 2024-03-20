<?php
require_once('arrays.php');

function generateCSV($num_records) {
    global $names, $surnames;

    // Start time measurement
    $start_time = microtime(true);

    $output_file = fopen('output/output.csv', 'w');

    $header = sprintf("%s,%s,%s,%s,%s,%s\n", "Id", "Name", "Surname", "Initials", "Age", "DateOfBirth");
    fwrite($output_file, $header);

    $generated_count = 0;

  
    $min_age = date('Y') - 2005;
    $max_age = date('Y') - 1940;

    $records = array();

while ($generated_count < $num_records) {
    $name = $names[array_rand($names)];
    $surname = $surnames[array_rand($surnames)];
    $initials = $name[0] . $surname[0];
    $dob = date('d/m/Y', mt_rand(strtotime('01/01/1940'), strtotime('01/01/2005')));
    $age = date('Y') - substr($dob, -4);

    $record = sprintf("%d,%s,%s,%s,%d,%s", $generated_count + 1, $name, $surname, $initials, $age, $dob);

    // Generates a hash key from the reord 
    $hash = md5($record);

    // checks if the hash key is in the table
    if (!isset($records[$hash])) {
        // If it isnt add it
        $records[$hash] = true;
        fwrite($output_file, $record . "\n");
        $generated_count++;
    }
}

fclose($output_file);

// End time measurement
$end_time = microtime(true);
$elapsed_time = $end_time - $start_time;


    echo "<script>alert('$generated_count records generated successfully! Time taken: $elapsed_time seconds');</script>";
}
?>
