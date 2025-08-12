<?php
session_start();
require_once 'config/db.php';
require_once 'config/functions.php';

$pdo = getDBConnection();

// Verifica se o jogo foi iniciado e se tem histórico
if (!isset($_SESSION['jogo']) || !isset($_SESSION['jogo']['historico'])) {
    header('Location: index.php');
    exit();
}

// Pega equipes com membros e pontos atuais
$equipes = [];
if (isset($_SESSION['jogo']['equipes'])) {
    foreach ($_SESSION['jogo']['equipes'] as $idEquipe) {
        $equipe = getEquipeComMembros($idEquipe);
        if ($equipe !== null) {
            $equipes[] = $equipe;
        }
    }
}

// Ordena equipes pela pontuação (decrescente)
usort($equipes, function ($a, $b) {
    return ($b['pontos'] ?? 0) <=> ($a['pontos'] ?? 0);
});

function getTeamColor($id)
{
    $colors = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#d35400'];
    return $colors[$id % count($colors)];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quiz Game - Ranking Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/ranking.css" />
</head>

<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1><i class="fas fa-trophy me-3"></i>Ranking Final</h1>
            <p class="lead">Confira o desempenho das equipes no Quiz Game</p>
        </div>

        <?php if (empty($equipes)): ?>
            <div class="alert alert-warning text-center fs-5">Nenhuma equipe para mostrar.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table id="rankingTable" class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Equipe</th>
                            <th>Membros</th>
                            <th>Pontuação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipes as $index => $equipe):
                            // Marca o campeão (primeiro colocado)
                            $isCampeao = ($index === 0);
                        ?>
                            <tr class="<?= $isCampeao ? 'table-success' : '' ?>"
                                style="border-left: 7px solid <?= getTeamColor($equipe['id']) ?>">
                                <th scope="row"><?= $index + 1 ?></th>
                                <td class="fw-bold fs-5"><?= htmlspecialchars($equipe['nome']) ?></td>
                                <td><?= htmlspecialchars(implode(', ', array_column($equipe['membros'], 'nome'))) ?></td>
                                <td class="fs-4"><?= $equipe['pontos'] ?? 0 ?> pts</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="reset_game.php" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-undo me-2"></i>Reiniciar Jogo
            </a>
            <a href="clear_all.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-home me-2"></i>Voltar ao Início
            </a>
        </div>
    </div>

    <!-- Bootstrap e FontAwesome JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/ranking.js"></script>
</body>

</html>