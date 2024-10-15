<?php
// Funções para manipular os dados
function readProducts()
{
    $json_data = file_get_contents('produtos.json');
    return json_decode($json_data, true);
}

// Lê os produtos do arquivo
$products = readProducts();

// ID do produto a ser recuperado (via GET ou POST)
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

// Busca o produto pelo ID
$product = null;
foreach ($products as $p) {
    if ($p['id'] == $product_id) {
        $product = $p;
        break;
    }
}

// Retorna o produto ou uma mensagem de erro
if ($product) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'product' => $product]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Produto não encontrado']);
}
