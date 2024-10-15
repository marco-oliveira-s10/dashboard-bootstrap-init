<?php
session_start(); // Inicia a sessão

$_SESSION['user_id'] = 1;

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

// Obtém o ID do usuário logado
$userId = $_SESSION['user_id'];

// Lê o arquivo usuarios.json
$jsonData = file_get_contents('usuarios.json');

// Verifica se a leitura do arquivo foi bem-sucedida
if ($jsonData === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao ler o arquivo usuarios.json.']);
    exit;
}

// Decodifica o JSON em um array
$users = json_decode($jsonData, true);

// Verifica se a decodificação foi bem-sucedida
if ($users === null) {
    echo json_encode(['success' => false, 'message' => 'Erro ao decodificar JSON: ' . json_last_error_msg()]);
    exit;
}

// Busca o usuário logado
$userData = null;
foreach ($users as $user) {
    if ($user['id'] == $userId) {
        $userData = $user;
        break;
    }
}

// Verifica se o usuário foi encontrado
if ($userData) {
    echo json_encode(['success' => true, 'user' => $userData]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
}
?>
