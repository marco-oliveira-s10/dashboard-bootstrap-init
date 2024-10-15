<?php
header('Content-Type: application/json');

// Caminho para o arquivo JSON
$jsonFile = 'produtos.json';

// Lê os produtos existentes
$products = json_decode(file_get_contents($jsonFile), true);

// Paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Número de produtos por página
$total = count($products);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

// Seleciona os produtos da página atual
$currentProducts = array_slice($products, $offset, $perPage);

// Cria a estrutura de paginação
$pagination = '';
if ($totalPages > 1) {
    for ($i = 1; $i <= $totalPages; $i++) {
        $pagination .= '<li class="page-item' . ($i === $page ? ' active' : '') . '"><a class="page-link" href="#" onclick="loadProducts(' . $i . ')">' . $i . '</a></li>';
    }
}

// Retorna a resposta JSON
echo json_encode([
    'success' => true,
    'products' => $currentProducts,
    'pagination' => $pagination
]);
