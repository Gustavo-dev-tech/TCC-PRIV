<?php
// Conectar ao banco de dados
include("conexao.php");

// Supondo que você tenha a variável $user_id ou a sessão para identificar o usuário logado
$email = $_SESSION['user_email']; // Exemplo de como pegar o e-mail do usuário logado (pode ser diferente dependendo da implementação)

// Buscar as informações do usuário
$query = "SELECT nome, email, telefone, foto_perfil FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nome, $email, $telefone, $foto_perfil);
$stmt->fetch();

?>

