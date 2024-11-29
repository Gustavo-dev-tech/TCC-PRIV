<?php
// Inclui a conexão com o banco de dados
include("conexao.php");

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"]; // Obtém o e-mail fornecido

    // Verifica se o e-mail existe no banco de dados
    $query = "SELECT senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // E-mail encontrado, envia a senha
        $row = $result->fetch_assoc();
        $senha = $row["senha"];

        // Aqui, enviaremos um e-mail com a senha (ou exiba, no caso de testes)
        echo '<span style="font-size: 28px;">Sua senha criptografada é: <strong>' . htmlspecialchars($senha) . '</strong></span>';
        
        // Exemplo de envio de e-mail (configurar servidor de e-mail no PHP.ini):
        /*
        $to = $email;
        $subject = "Recuperação de senha";
        $message = "Sua senha é: " . $senha;
        $headers = "From: seuemail@dominio.com";
        mail($to, $subject, $message, $headers);
        */
    } else {
        echo "E-mail não encontrado no sistema. ";
    }
}
?>
