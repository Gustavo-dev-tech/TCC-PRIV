<?php
session_start();
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados enviados pelo formulário
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $senha = isset($_POST['senha']) ? $_POST['senha'] : null;

    // Verifica se os campos foram preenchidos
    if ($email && $senha) {
        // Consulta o banco de dados para buscar o usuário pelo email
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtém os dados do usuário
            $usuario = $result->fetch_assoc();

            // Verifica se a senha está correta
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido: armazena o e-mail na sessão e redireciona
                $_SESSION['user_email'] = $email;
                header("Location: etecs.html");  // Redirecionar para etecs.html
                exit();
            } else {
                echo "Senha incorreta.";
            }
        } else {
            echo "Usuário não encontrado.";
        }
    } else {
        echo "Preencha todos os campos!";
    }
}
?>
