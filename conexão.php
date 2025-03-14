<?php
$conexao = mysqli_connect( 'localhost','root','') or die("Erro na conexão");
$selectdb=mysqli_select_db($conexao,'biblioteca digital');

$host = 'localhost';
$db = 'biblioteca digital';
$user = 'root'; // Altere para o utilizador da sua base de dados
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
