<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

// Carrega os dados dos usuários do arquivo JSON
$users = json_decode(file_get_contents('usuarios.json'), true);
$userId = $_SESSION['user_id'];

// Remove o usuário da lista
$users = array_filter($users, function($u) use ($userId) {
    return $u['id'] !== $userId;
});

// Salva os dados atualizados de volta no arquivo JSON
file_put_contents('usuarios.json', json_encode(array_values($users), JSON_PRETTY_PRINT));

// Destrói a sessão do usuário
session_destroy();

echo json_encode(['success' => true, 'message' => 'Conta deletada com sucesso!']);
?>
