<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha

// Preparar e executar a consulta SQL
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senha);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
