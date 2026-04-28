<?php
$host = 'localhost';
$dbname = 'shop';
$user = 'postgres';
$password = '19191974est';

try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>