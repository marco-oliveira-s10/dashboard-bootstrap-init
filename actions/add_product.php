<?php
header('Content-Type: application/json');

// Caminho para o arquivo JSON
$jsonFile = 'produtos.json';

// Lê os produtos existentes
$products = json_decode(file_get_contents($jsonFile), true);

// Recebe os dados do produto
$name = isset($_POST['name']) ? $_POST['name'] : '';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

// Adiciona o produto ao array
$productId = count($products) + 1; // Gera um ID para o novo produto
$products[] = [
    'id' => $productId,
    'name' => $name,
    'quantity' => $quantity
];

// Salva o novo array de produtos no arquivo JSON
file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));

// Retorna sucesso
echo json_encode(['success' => true]);
