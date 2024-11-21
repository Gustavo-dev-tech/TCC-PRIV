<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

// Recebe dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['date'] ?? '';
    $message = $_POST['message'] ?? '';

    // Valida os dados
    if (empty($data) || empty($message)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Carrega o arquivo JSON existente ou inicializa um novo
    $file = 'notices.json';
    $notices = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    // Adiciona o novo aviso
    $notices[$data][] = $message;

    // Salva no arquivo
    file_put_contents($file, json_encode($notices, JSON_PRETTY_PRINT));
    echo "Aviso salvo com sucesso!";
    exit;
}
?>
