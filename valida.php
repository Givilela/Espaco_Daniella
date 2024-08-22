<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conexao, $_POST['username'] ?? '');
    $password = mysqli_real_escape_string($conexao, $_POST['password'] ?? '');
} else {
    $username = '';
    $password = '';
}

$query = "SELECT * FROM usuarios WHERE usuario = '$username' AND senha = '$password'";
$result = mysqli_query($conexao, $query);

if ($result) {
    $row = mysqli_num_rows($result);

    if ($row == 1) {
        $_SESSION['usuario'] = $username;
        header('Location: adm.html');
        exit();
    } else {
        header('Location: login.html?error=1');
        exit();
    }
} else {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>
