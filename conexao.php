<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";
// Criar a conexão
$conexao = new mysqli($servername, $username, $password, $dbname) ;
// Verificar a conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>