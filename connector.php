<?php 
    date_default_timezone_set('Asia/Bangkok');
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "qwer";
    $db_name = "pdo";

    try {
        $conn = new PDO("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOEXCEPTION $e) {
        $e->getMessage();
    }

?>