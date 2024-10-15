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
$userIndex = -1;

// Procura o usuário na lista de usuários
foreach ($users as $index => $u) {
    if ($u['id'] == $userId) {
        $userIndex = $index;
        break;
    }
}

// Verifica se o usuário foi encontrado
if ($userIndex === -1) {
    echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
    exit;
}

// Obtém os dados enviados pelo formulário
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$cep = $_POST['cep'];
$neighborhood = $_POST['neighborhood'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$gender = $_POST['gender'];
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

// Atualiza os dados do usuário
$users[$userIndex]['name'] = $name;
$users[$userIndex]['email'] = $email;
$users[$userIndex]['phone'] = $phone;
$users[$userIndex]['cep'] = $cep;
$users[$userIndex]['neighborhood'] = $neighborhood;
$users[$userIndex]['city'] = $city;
$users[$userIndex]['state'] = $state;
$users[$userIndex]['country'] = $country;
$users[$userIndex]['gender'] = $gender;

// Verifica se uma nova senha foi fornecida
if (!empty($newPassword)) {
    // Verifica se a senha antiga está correta
    if (!password_verify($oldPassword, $users[$userIndex]['password'])) {
        echo json_encode(['success' => false, 'message' => 'Senha antiga incorreta.']);
        exit;
    }

    // Valida se as novas senhas coincidem
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'As novas senhas não coincidem.']);
        exit;
    }

    // Atualiza a senha
    $users[$userIndex]['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
}

// Salva os dados atualizados de volta no arquivo JSON
file_put_contents('usuarios.json', json_encode($users, JSON_PRETTY_PRINT));

echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
?>
