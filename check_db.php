<?php
$mysqli = new mysqli('localhost', 'root', '', '', 3308);
if ($mysqli->connect_error) {
    echo 'Connection error: ' . $mysqli->connect_error;
    exit;
}
$result = $mysqli->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='gad_submission_system'");
if ($result && $result->num_rows > 0) {
    echo 'Database gad_submission_system EXISTS';
} else {
    echo 'Database NOT FOUND';
}
$mysqli->close();
?>
