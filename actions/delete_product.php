<?php
header('Content-Type: application/json');

// Caminho para o arquivo JSON
$jsonFile = 'produtos.json';

// Lê os produtos existentes
$products = json_decode(file_get_contents($jsonFile), true);

// ID do produto a ser removido
$product_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Remove o produto do array
$products = array_filter($products, function ($product) use ($product_id) {
    return $product['id'] != $product_id;
});

// Reindexa o array
$products = array_values($products);

// Salva o novo array de produtos no arquivo JSON
file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));

// Retorna sucesso
echo json_encode(['success' => true]);
