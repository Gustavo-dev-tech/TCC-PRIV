<?php
session_start();

// Credenciais dos administradores em um array
$admins = [
    "007@etec" => "P007", // ETEC Polivalente de Americana
    "008@etec" => "J008", // ETEC João Belarmino
    "009@etec" => "A009", // ETEC de Arthur Nogueira
    "010@etec" => "T010", // ETEC Trajano Camargo
    "011@etec" => "B011", // ETEC Bento Quirino Extensão EE Hercy Moraes
    "012@etec" => "B012", // ETEC Bento Quirino
    "013@etec" => "C013", // ETEC Conselheiro Antônio Prado
    "014@etec" => "F014", // FATEC Bragança Paulista
    "115@etec" => "H115", // ETEC de Hortolândia
    "016@etec" => "F016", // FATEC Indaiatuba
    "017@etec" => "R017", // ETEC Rosa Perrone Scavone
    "018@etec" => "V018", // ETEC Vasco Antônio Venchiarutti
    "019@etec" => "B019", // ETEC Benedito Storani
    "020@etec" => "C020", // ETEC Campo Limpo Paulista
    "021@etec" => "E021", // ETEC Euro Albino de Souza
    "022@etec" => "P022", // ETEC de Paulínia
    "023@etec" => "D023", // ETEC Deputado Ary de Camargo Pedroso
    "024@etec" => "P024", // ETEC Professor Doutor José Dagnoni
    "025@etec" => "S025"  // ETEC Sumaré
];

// Verifica se é uma requisição POST para login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $buttonClicked = $_POST['action'] ?? ''; // Identifica qual botão foi clicado

    // Verifica se o e-mail e a senha correspondem a alguma entrada no array
    if (isset($admins[$email]) && $admins[$email] === $password) {
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_email'] = $email;

        // Redireciona de acordo com o botão clicado
        if ($buttonClicked === 'dashboard') {
            header("Location: admin_dashboard.php");
        } elseif ($buttonClicked === 'calendario') {
            header("Location: calendario_admin.html");
        }
        exit;
    } else {
        $error = "E-mail ou senha inválidos.";
    }
}

// Verifica se o administrador está logado para acessar páginas protegidas
function isAdminLoggedIn() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Protege páginas como admin_dashboard.php e ações específicas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getAvisos') {
    if (!isAdminLoggedIn()) {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso negado']);
        exit;
    }

    $avisosFile = 'avisos.json';
    $avisos = file_exists($avisosFile) ? json_decode(file_get_contents($avisosFile), true) : [];
    header('Content-Type: application/json');
    echo json_encode($avisos);
    exit;
}

// Adiciona avisos com a verificação de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date']) && isset($_POST['message'])) {
    if (!isAdminLoggedIn()) {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso negado']);
        exit;
    }

    $avisosFile = 'avisos.json';
    $avisos = file_exists($avisosFile) ? json_decode(file_get_contents($avisosFile), true) : [];

    $date = $_POST['date'];
    $message = $_POST['message'];

    if (!isset($avisos[$date])) {
        $avisos[$date] = [];
    }
    $avisos[$date][] = $message;

    file_put_contents($avisosFile, json_encode($avisos, JSON_PRETTY_PRINT));
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Aviso salvo com sucesso.']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admin.css">
  <title>Login do Administrador</title>
</head>
<body>
  <div class="login-container">
    <br><br><br><br>
    <h1>Login do Administrador</h1>
    <form method="POST">
        <label for="">115@ETEC</label>
      <input type="email" name="email" placeholder="Digite o código da ETEC" required>
      <label for="">H115</label>
      <input type="password" name="password" placeholder="Senha" required>
      <br>
      <button type="submit" name="action" value="dashboard">Adicionar aviso no calendario de adm  </button>
      <br><br>
      <button type="submit"  name="action" value="calendario">Solicitações de agendamento de usuarios</button>
    </form>
    
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
  </div>
</body>
</html>
