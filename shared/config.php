<?php
/**
 * using mysqli_connect for database connection
 */
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = ''; // Example password
$databaseName = 'Healthcare';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// 检查连接是否成功
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
