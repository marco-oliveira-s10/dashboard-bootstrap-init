<?php require_once('header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Configurações</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- Botão para abrir o modal de cadastro -->
        <div class="btn-group me-2">
            <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <img src="/assets/images/add.png" alt="Adicionar Produto" width="45">
            </a>
        </div>
    </div>
</div>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-12 col-md-8 col-lg-6">
        <form id="settingsForm">
            <h3 class="text-center mb-4">Configurações da Conta</h3>
            <div class="row mb-3">
                <div class="col">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="tel" class="form-control" id="phone" required>
                </div>
                <div class="col">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="neighborhood" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="neighborhood" required>
                </div>
                <div class="col">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="city" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="state" class="form-label">Estado</label>
                    <input type="text" class="form-control" id="state" required>
                </div>
                <div class="col">
                    <label for="country" class="form-label">País</label>
                    <input type="text" class="form-control" id="country" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Sexo</label>
                <select class="form-select" id="gender" required>
                    <option value="">Selecione</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="userType" class="form-label">Tipo de Usuário</label>
                <select class="form-select" id="userType" required>
                    <option value="">Selecione</option>
                    <option value="admin">Admin</option>
                    <option value="comum">Comum</option>
                </select>
            </div>

            <h3 class="mt-4">Trocar Senha</h3>
            <div class="mb-3">
                <label for="oldPassword" class="form-label">Senha Antiga</label>
                <input type="password" class="form-control" id="oldPassword" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="newPassword" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="newPassword" required>
                </div>
                <div class="col">
                    <label for="confirmPassword" class="form-label">Confirme a Nova Senha</label>
                    <input type="password" class="form-control" id="confirmPassword" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
            <button type="button" class="btn btn-danger w-100 mt-2" id="deleteAccount">Deletar Conta</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Função para carregar os dados do usuário
        function loadUserData() {
            $.ajax({
                url: '/actions/get_user_data.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#name').val(data.user.name);
                        $('#email').val(data.user.email);
                        $('#phone').val(data.user.phone);
                        $('#cep').val(data.user.cep);
                        $('#neighborhood').val(data.user.neighborhood);
                        $('#city').val(data.user.city);
                        $('#state').val(data.user.state);
                        $('#country').val(data.user.country);
                        $('#gender').val(data.user.gender);
                        $('#userType').val(data.user.type); // Assume type is set
                    } else {
                        alert('Erro ao carregar dados do usuário: ' + data.message);
                    }
                },
                error: function() {
                    alert('Erro ao conectar com o servidor.');
                }
            });
        }

        // Salvar alterações de configurações
        $('#settingsForm').on('submit', function(e) {
            e.preventDefault();

            const userData = {
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                cep: $('#cep').val(),
                neighborhood: $('#neighborhood').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                country: $('#country').val(),
                gender: $('#gender').val(),
                oldPassword: $('#oldPassword').val(),
                newPassword: $('#newPassword').val(),
                confirmPassword: $('#confirmPassword').val()
            };

            // Validação simples de senhas
            if (userData.newPassword && userData.newPassword !== userData.confirmPassword) {
                alert('As novas senhas não coincidem.');
                return;
            }

            $.ajax({
                url: '/actions/update_user.php',
                type: 'POST',
                data: userData,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        alert('Dados atualizados com sucesso!');
                        loadUserData(); // Reload user data
                    } else {
                        alert('Erro ao atualizar dados: ' + data.message);
                    }
                },
                error: function() {
                    alert('Erro ao conectar com o servidor.');
                }
            });
        });

        // Deletar conta
        $('#deleteAccount').on('click', function() {
            if (confirm('Você tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.')) {
                $.ajax({
                    url: '/actions/delete_account.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            alert('Conta deletada com sucesso!');
                            window.location.href = '/login.php'; // Redirect to logout page
                        } else {
                            alert('Erro ao deletar conta: ' + data.message);
                        }
                    },
                    error: function() {
                        alert('Erro ao conectar com o servidor.');
                    }
                });
            }
        });

        // Carregar os dados do usuário ao iniciar a página
        loadUserData();
    });
</script>

<?php require_once('footer.php'); ?>