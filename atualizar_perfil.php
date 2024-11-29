<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_email'])) {
    die("Usuário não está logado.");
}

// Conectar ao banco de dados
include("conexao.php");

$email = $_SESSION['user_email'];  // Recupera o e-mail da sessão

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $foto_perfil = $_POST['foto_perfil'];  // Se o usuário inserir a URL da foto

    // Atualiza os dados do usuário
    $query = "UPDATE usuarios SET nome = ?, telefone = ?, foto_perfil = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nome, $telefone, $foto_perfil, $email);

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar perfil.";
    }
}
?>
