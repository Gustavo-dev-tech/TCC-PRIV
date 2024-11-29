<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_email'])) {
    die("Usuário não está logado.");
}

// Conectar ao banco de dados
include("conexao.php");

$email = $_SESSION['user_email'];  // Recupera o e-mail da sessão

// Recuperar os dados do usuário
$query = "SELECT nome, email, telefone, foto_perfil FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nome, $email, $telefone, $foto_perfil);
$stmt->fetch();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="perfil.css">
    <style>
        .edit-form input {
            pointer-events: none;  /* Desabilita os campos */
            font-family: Arial, Helvetica, sans-serif;
        }

        .edit-form.editable input {
            pointer-events: auto;  /* Habilita os campos para edição */
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <div class = "formulario" >
        <div class = "form"> 
        <a href="etecs.html" class="back-button" title="Voltar para a lista de ETECs">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </a>
        
        
    <!-- Formulário de edição do perfil -->
    <h2>Perfil de Usuário</h2> 
    
    
    <form class="edit-form"  method="POST" action="atualizar_perfil.php">
          <br><br>
            <label for="nome">Nome</label>
            <br>
                <input class = "insirir" type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        <br>
            <label for="email">Email</label>
            <br>
                <input class = "insirir" type="email" id="email" name="email"  value="<?php echo htmlspecialchars($email); ?>" required>
        <br>
            <label for="telefone">Telefone</label>
                <input class = "insirir" type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" required>
                <br><br>

        <input class = "insirir" type="submit" value="Atualizar Perfil">
        <br>
        

        <br><br><br>
        <a href="etecs.html">
    <input class = "enviarr" type="submit" value="Voltar">
    </a>

    </form>
    
    
<!-- Botão "Editar" que habilita os campos para edição -->
<button class="enviar" id="editButton" onclick="toggleEdit()">Editar</button>

    
    <script>
        // Função para alternar entre edição e visualização
        function toggleEdit() {
            var form = document.querySelector('.edit-form');
            var button = document.getElementById('editButton');
            
            form.classList.toggle('editable');  // Alterna a classe para habilitar ou desabilitar os campos
            if (form.classList.contains('editable')) {
                button.textContent = 'Cancelar Edição';  // Muda o texto do botão para "Cancelar"
            } else {
                button.textContent = 'Editar';  // Muda o texto do botão de volta para "Editar"
            }
        }
    </script>
</div>   

   
</body>
</html>
