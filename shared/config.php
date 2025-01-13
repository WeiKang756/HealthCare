<?php
namespace Shared;

$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = ''; // Example password
$databaseName = 'Healthcare';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
