<?php require_once('header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Products</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- Botão para abrir o modal de cadastro -->
        <div class="btn-group me-2">
            <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <img src="/assets/images/add.png" alt="Adicionar Produto" width="45">
            </a>
        </div>
    </div>
</div>

<!-- Barra de busca -->
<div class="input-group mb-3">
    <input type="text" id="searchProduct" class="form-control" placeholder="Buscar produtos" aria-label="Buscar produtos">
    <button class="btn btn-outline-secondary" type="button" id="searchButton">Buscar</button>
</div>

<!-- Tabela de Produtos -->
<h2>Table</h2>
<div class="table-responsive small">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Editar</th>
                <th scope="col">Remover</th>
            </tr>
        </thead>
        <tbody id="productTable">
            <!-- Aqui será preenchido dinamicamente com os produtos -->
        </tbody>
    </table>

    <!-- Paginação -->
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination">
            <!-- Elementos de paginação gerados dinamicamente -->
        </ul>
    </nav>
</div>

<!-- Modal de Cadastro de Produto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Cadastrar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productQuantity" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="productQuantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Produto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editProductId">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="editProductName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductQuantity" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="editProductQuantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JavaScript -->
<script>
    // Função para carregar a listagem de produtos
    function loadProducts(page = 1) {
        $.ajax({
            url: '/actions/load_products.php',
            type: 'GET',
            data: {
                page: page
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    let productRows = '';
                    $.each(data.products, function(index, product) {
                        productRows += `
                            <tr>
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.quantity}</td>
                                <td><a href="#" class="edit-product" data-id="${product.id}"><img src="/assets/images/edit.png" alt="Editar Produto" width="25"></a></td>
                                <td><a href="#" class="delete-product" data-id="${product.id}"><img src="/assets/images/delete.png" alt="Delete Produto" width="25"></a></td>
                            </tr>`;
                    });
                    $('#productTable').html(productRows);
                    $('#pagination').html(data.pagination);
                } else {
                    alert('Erro ao carregar produtos.');
                }
            },
            error: function() {
                alert('Erro ao conectar com o servidor.');
            }
        });
    }

    // Chama a função de busca
    $('#searchButton').on('click', function() {
        const searchTerm = $('#searchProduct').val();
        $.ajax({
            url: '/actions/search_products.php',
            type: 'GET',
            data: {
                term: searchTerm
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    let productRows = '';
                    $.each(data.products, function(index, product) {
                        productRows += `
                        <tr>
                            <td>${product.id}</td>
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td><a href="#" class="edit-product" data-id="${product.id}"><img src="/assets/images/edit.png" alt="Editar Produto" width="25"></a></td>
                            <td><a href="#" class="delete-product" data-id="${product.id}"><img src="/assets/images/delete.png" alt="Delete Produto" width="25"></a></td>
                        </tr>`;
                    });
                    $('#productTable').html(productRows);
                    $('#pagination').html(data.pagination); // Atualiza a paginação
                } else {
                    alert('Nenhum produto encontrado.');
                }
            },
            error: function() {
                alert('Erro ao conectar com o servidor.');
            }
        });
    });

    // Modal de cadastro de produto
    // Modal de cadastro de produto
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();
        const productName = $('#productName').val();
        const productQuantity = $('#productQuantity').val();

        $.ajax({
            url: '/actions/add_product.php',
            type: 'POST',
            data: {
                name: productName,
                quantity: productQuantity
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#addProductModal').modal('hide');
                    $('#productName').val(''); // Limpa o campo de nome
                    $('#productQuantity').val(''); // Limpa o campo de quantidade
                    loadProducts(); // Recarrega a tabela após adicionar
                } else {
                    alert('Erro ao cadastrar produto.');
                }
            },
            error: function() {
                alert('Erro ao conectar com o servidor.');
            }
        });
    });

    // Abrir modal de edição com os dados do produto
    $(document).on('click', '.edit-product', function(e) {
        e.preventDefault(); // Evita o comportamento padrão do link
        const productId = $(this).data('id');
        $.ajax({
            url: '/actions/get_product.php',
            type: 'GET',
            data: {
                id: productId
            },
            dataType: 'json', // Certifique-se de que a resposta seja JSON
            success: function(data) {
                if (data.success) {
                    $('#editProductId').val(data.product.id);
                    $('#editProductName').val(data.product.name);
                    $('#editProductQuantity').val(data.product.quantity);
                    $('#editProductModal').modal('show');
                } else {
                    alert('Erro ao carregar os dados do produto.');
                }
            },
            error: function() {
                alert('Erro ao conectar com o servidor.');
            }
        });
    });

    // Submissão do formulário de edição de produto
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();
        const productId = $('#editProductId').val();
        const productName = $('#editProductName').val();
        const productQuantity = $('#editProductQuantity').val();

        $.ajax({
            url: '/actions/edit_product.php',
            type: 'POST',
            data: {
                id: productId,
                name: productName,
                quantity: productQuantity
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#editProductModal').modal('hide');
                    loadProducts(); // Recarrega a tabela após a edição
                } else {
                    alert('Erro ao editar produto.');
                }
            },
            error: function() {
                alert('Erro ao conectar com o servidor.');
            }
        });
    });

    // Remover produto
    $(document).on('click', '.delete-product', function(e) {
        e.preventDefault(); // Evita o comportamento padrão do link
        const productId = $(this).data('id');
        if (confirm('Deseja realmente remover este produto?')) {
            $.ajax({
                url: '/actions/delete_product.php',
                type: 'POST',
                data: {
                    id: productId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        loadProducts(); // Recarrega a tabela após a remoção
                    } else {
                        alert('Erro ao remover produto.');
                    }
                },
                error: function() {
                    alert('Erro ao conectar com o servidor.');
                }
            });
        }
    });

    // Carregar os produtos inicialmente
    loadProducts();
</script>

<?php require_once('footer.php'); ?>