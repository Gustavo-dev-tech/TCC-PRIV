<?php
// Configurações de cabeçalho para permitir o envio de dados JSON
header('Content-Type: application/json');

// Caminho para o arquivo JSON onde os agendamentos serão salvos
$jsonFile = 'agendamento.json'; // Alterado para avisos.json

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se as variáveis 'data' e 'etec' estão presentes na requisição
    if (isset($_POST['data']) && isset($_POST['etec'])) {
        $data = $_POST['data']; // A data escolhida pelo usuário
        $etec = $_POST['etec']; // A ETEC selecionada pelo usuário

        // Cria um array com as informações de agendamento
        $agendamento = [
            'data' => $data,
            'etec' => $etec,
            'timestamp' => date('Y-m-d H:i:s') // Adiciona o timestamp do momento do agendamento
        ];

        // Verifica se o arquivo JSON já existe
        if (file_exists($jsonFile)) {
            // Se o arquivo já existe, carrega o conteúdo existente
            $existingData = json_decode(file_get_contents($jsonFile), true);
        } else {
            // Se o arquivo não existe, inicializa um array vazio
            $existingData = [];
        }

        // Adiciona o novo agendamento ao array existente
        $existingData[] = $agendamento;

        // Salva o novo array de agendamentos de volta no arquivo JSON
        if (file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT))) {
            // Retorna uma resposta de sucesso em formato JSON
            echo json_encode([
                'status' => 'success',
                'message' => 'Agendamento realizado com sucesso!',
                'data' => $agendamento
            ]);
        } else {
            // Caso haja erro ao salvar no arquivo
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro ao salvar o agendamento no arquivo JSON.'
            ]);
        }
    } else {
        // Se algum dado estiver faltando, retorna um erro
        echo json_encode([
            'status' => 'error',
            'message' => 'Dados incompletos. Por favor, forneça a data e a ETEC.'
        ]);
    }
} else {
    // Se o método não for POST, retorna um erro
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de requisição inválido. Por favor, use o método POST.'
    ]);
}
?>
