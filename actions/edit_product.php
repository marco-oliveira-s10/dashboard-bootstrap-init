<?php
header('Content-Type: application/json');

// Caminho para o arquivo JSON
$jsonFile = 'produtos.json';

// Lê os produtos existentes
$products = json_decode(file_get_contents($jsonFile), true);

// Dados do produto editado
$product_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$product_name = isset($_POST['name']) ? $_POST['name'] : '';
$product_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

// Atualiza o produto no array
foreach ($products as &$product) {
    if ($product['id'] == $product_id) {
        $product['name'] = $product_name;
        $product['quantity'] = $product_quantity;
        break;
    }
}

// Salva o novo array de produtos no arquivo JSON
file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));

// Retorna sucesso
echo json_encode(['success' => true]);
