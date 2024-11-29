<?php
include("conexao.php");

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar se o token existe no banco de dados e não expirou
    $query = "SELECT email, token_expiracao FROM usuarios WHERE token_recuperacao = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Token encontrado, verificar se não expirou
        $stmt->bind_result($email, $token_expiracao);
        $stmt->fetch();

        if (strtotime($token_expiracao) > time()) {
            // O token não expirou, permitir a redefinição da senha
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['senha']) && !empty($_POST['senha'])) {
                    $nova_senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografar a nova senha

                    // Atualizar a senha no banco de dados
                    $query = "UPDATE usuarios SET senha = ?, token_recuperacao = NULL, token_expiracao = NULL WHERE email = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ss", $nova_senha, $email);
                    $stmt->execute();

                    echo "Senha alterada com sucesso!";
                }
            }
            ?>
            <form method="POST" action="redefinir.php?token=<?php echo $token; ?>">
                <input type="password" name="senha" placeholder="Nova Senha" required>
                <input type="submit" value="Redefinir Senha">
            </form>
            <?php
        } else {
            echo "O token de recuperação expirou.";
        }
    } else {
        echo "Token inválido.";
    }
} else {
    echo "Token não encontrado.";
}
?>
