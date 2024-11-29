<?php
$servername = "localhost"; // Ou 127.0.0.1
$username = "root"; // Seu usuário do MySQL
$password = "vo39092506"; // A senha do MySQL (deixe vazio se não configurou)
$dbname = "MeuSistema";

// Conexão com o MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
