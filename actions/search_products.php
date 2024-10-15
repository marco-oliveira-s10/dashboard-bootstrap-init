<?php
// Funções para manipular os dados
function readProducts()
{
    $json_data = file_get_contents('produtos.json');
    return json_decode($json_data, true);
}

// Lê os produtos do arquivo
$products = readProducts();

// Termo de busca
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Filtrar produtos
$filteredProducts = [];
if ($searchTerm !== '') {
    foreach ($products as $product) {
        if (stripos($product['name'], $searchTerm) !== false) {
            $filteredProducts[] = $product;
        }
    }
} else {
    $filteredProducts = $products; // Se não houver termo de busca, use todos os produtos
}

// Contagem total para a paginação
$totalProducts = count($filteredProducts);

// Aqui você pode definir o número de produtos por página
$productsPerPage = 10;
$totalPages = ceil($totalProducts / $productsPerPage);

// Pegar a página atual, se não estiver definido, use a primeira página
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Garante que a página está dentro dos limites

// Calcular o offset para a consulta
$offset = ($currentPage - 1) * $productsPerPage;

// Pegar os produtos para a página atual
$productsToDisplay = array_slice($filteredProducts, $offset, $productsPerPage);

// Função para gerar a paginação
function generatePagination($totalPages, $currentPage)
{
    $pagination = '';
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            $pagination .= "<li class='page-item active'><a class='page-link' href='#'>{$i}</a></li>";
        } else {
            $pagination .= "<li class='page-item'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
        }
    }
    return $pagination;
}

// Gera a resposta
$response = [
    'success' => true,
    'products' => $productsToDisplay,
    'pagination' => generatePagination($totalPages, $currentPage),
];

header('Content-Type: application/json');
echo json_encode($response);
