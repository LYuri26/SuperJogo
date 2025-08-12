<?php
session_start();

require_once 'config/db.php';
require_once 'config/functions.php';

// Cria a conexão PDO e deixa global para as funções funcionarem
$pdo = getDBConnection();

// Função getEquipesComMembros() usa global $pdo
// Busca equipes com seus membros
$equipes = getEquipesComMembros();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Quiz Game - Cadastro de Equipes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>

<body class="bg-light">
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="container py-5">
        <h1 class="mb-4 text-primary"><i class="fas fa-gamepad me-2"></i>Quiz Game - Cadastro de Equipes</h1>

        <!-- Formulário para nova equipe -->
        <div class="card mb-4">
            <div class="card-header">Cadastrar Nova Equipe</div>
            <div class="card-body">
                <form id="teamForm" method="POST" action="process_team.php">
                    <div class="mb-3">
                        <label for="teamName" class="form-label">Nome da Equipe</label>
                        <input type="text" id="teamName" name="teamName" class="form-control" required />
                    </div>
                    <label class="form-label">Membros</label>
                    <div id="membersContainer">
                        <div class="input-group mb-2">
                            <input type="text" name="members[]" class="form-control member-input"
                                placeholder="Nome do membro" required />
                            <button type="button" class="btn btn-outline-danger remove-member" title="Remover membro">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="addMemberBtn" class="btn btn-outline-primary btn-sm mb-3">
                        <i class="fas fa-plus"></i> Adicionar Membro
                    </button>
                    <br />
                    <button type="submit" id="addTeamBtn" class="btn btn-success">Adicionar Equipe</button>
                </form>
            </div>
        </div>

        <!-- Lista de equipes cadastradas -->
        <?php if (!empty($equipes)): ?>
            <div class="card mb-4">
                <div class="card-header">Equipes Cadastradas</div>
                <ul class="list-group list-group-flush" id="teamsList">
                    <?php foreach ($equipes as $equipe): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($equipe['nome']) ?></strong><br />
                                <small class="text-muted">
                                    <?= implode(', ', array_column($equipe['membros'], 'nome')) ?>
                                </small>
                            </div>
                            <form method="POST" action="process_team.php" onsubmit="return confirm('Remover esta equipe?')">
                                <input type="hidden" name="removeTeamId" value="<?= $equipe['id'] ?>" />
                                <button type="submit" class="btn btn-sm btn-danger">Remover</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Se houver 2 ou mais equipes, pode iniciar o jogo -->
        <div id="gameSection" class="card <?php if (count($equipes) < 2) echo 'd-none'; ?>">
                <div class="card-header">Configuração do Jogo</div>
                <div class="card-body">
                    <form id="startGameForm" method="POST" action="start_game.php">
                        <div class="mb-3">
                            <label for="totalQuestions" class="form-label">Quantidade de Perguntas</label>
                            <input type="number" id="totalQuestions" name="totalQuestions" class="form-control" value="10"
                                min="1" max="100" required />
                        </div>
                        <button type="submit" id="startGameBtn" class="btn btn-primary">Iniciar Jogo</button>
                    </form>
                </div>
            </div>
    </div>

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>