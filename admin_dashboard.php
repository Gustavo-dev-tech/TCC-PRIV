<?php
session_start();

// Caminho do arquivo onde os avisos são armazenados
$avisosFile = 'avisos.json';

// Função para carregar os avisos do arquivo JSON
function loadAvisos() {
    global $avisosFile;
    if (file_exists($avisosFile)) {
        return json_decode(file_get_contents($avisosFile), true);
    }
    return [];
}

// Função para salvar os avisos no arquivo JSON
function saveAvisos($avisos) {
    global $avisosFile;
    file_put_contents($avisosFile, json_encode($avisos, JSON_PRETTY_PRINT));
}

// Verifica se o administrador está logado para acessar páginas protegidas
function isAdminLoggedIn() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Adicionar um aviso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date']) && isset($_POST['message'])) {
    if (isAdminLoggedIn()) {
        $date = $_POST['date'];
        $message = $_POST['message'];

        // Carrega os avisos existentes
        $avisos = loadAvisos();

        // Se não houver um aviso para essa data, cria um array para ela
        if (!isset($avisos[$date])) {
            $avisos[$date] = [];
        }

        // Adiciona o novo aviso
        $avisos[$date][] = $message;

        // Salva os avisos no arquivo
        saveAvisos($avisos);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Aviso salvo com sucesso.']);
        exit;
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso negado']);
        exit;
    }
}

// Retorna os avisos para o calendário (requisição GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getAvisos') {
    if (!isAdminLoggedIn()) {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso negado']);
        exit;
    }

    // Carrega os avisos
    $avisos = loadAvisos();

    header('Content-Type: application/json');
    echo json_encode($avisos);
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar Avisos ao Calendário</title>
  <style>
    /* Adicione estilos simples para o formulário */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f4f4f4;
    }

    .form-container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    input[type="date"], textarea {
      width: 100%;
      padding: 8px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px;
      font-size: 14px;
      cursor: pointer;
      border-radius: 5px;
      width: 100%;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Adicionar Aviso</h2>
    <form method="POST">
      <label for="date">Data:</label>
      <input type="date" id="date" name="date" required>
      <label for="message">Aviso:</label>
      <textarea id="message" name="message" rows="4" required></textarea>
      <button type="submit">Salvar Aviso</button>
    </form>
  </div>
</body>
</html>
