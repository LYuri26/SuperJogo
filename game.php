<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config/db.php';
require_once 'config/functions.php';

$pdo = getDBConnection();

// Verifica se o jogo foi iniciado
if (!isset($_SESSION['jogo'])) {
    header('Location: index.php');
    exit();
}

// Função para sortear a próxima equipe a responder
function sortearEquipe(array $equipes, int $ultimaEquipe = null): int
{
    if ($ultimaEquipe === null) {
        return $equipes[array_rand($equipes)];
    }
    // Evita repetir equipe atual se possível
    do {
        $sorteada = $equipes[array_rand($equipes)];
    } while ($sorteada === $ultimaEquipe && count($equipes) > 1);
    return $sorteada;
}

// Valida equipes válidas na sessão
if (!isset($_SESSION['jogo']['equipes']) || !is_array($_SESSION['jogo']['equipes']) || count($_SESSION['jogo']['equipes']) === 0) {
    die("Nenhuma equipe registrada para o jogo.");
}

$equipesValidas = [];
foreach ($_SESSION['jogo']['equipes'] as $idEquipe) {
    if (is_numeric($idEquipe) && $idEquipe > 0) {
        $equipe = getEquipeComMembros((int)$idEquipe);
        if ($equipe !== null && !empty($equipe)) {
            $equipesValidas[] = (int)$idEquipe;
        }
    }
}
if (empty($equipesValidas)) {
    die("Nenhuma equipe válida encontrada para o jogo.");
}
$_SESSION['jogo']['equipes'] = $equipesValidas;

// Sorteia equipe atual se inexistente ou inválida
if (!isset($_SESSION['jogo']['equipe_atual']) || !in_array($_SESSION['jogo']['equipe_atual'], $_SESSION['jogo']['equipes'])) {
    $_SESSION['jogo']['equipe_atual'] = sortearEquipe($_SESSION['jogo']['equipes']);
}
$equipeAtualId = $_SESSION['jogo']['equipe_atual'];
$equipeAtual = getEquipeComMembros($equipeAtualId);

if (empty($equipeAtual['membros'])) {
    die("Equipe selecionada (ID: $equipeAtualId) não tem membros para responder.");
}

// Remove penalidade da equipe atual (penalidade dura até a próxima vez da equipe)
if (isset($_SESSION['jogo']['ultima_penalidade']) && $_SESSION['jogo']['ultima_penalidade']['equipe'] === $equipeAtualId) {
    unset($_SESSION['jogo']['ultima_penalidade']);
}

// Sorteia membro da equipe atual para responder
$membroSorteado = $equipeAtual['membros'][array_rand($equipeAtual['membros'])];

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['resposta'])) {
        $resposta = $_POST['resposta'];
        $perguntaId = (int)$_POST['pergunta_id'];
        $equipeId = $equipeAtualId;
        $membroId = $membroSorteado['id'];

        $pergunta = getPerguntaById($perguntaId);
        $acertou = ($resposta === $pergunta['resposta_correta']);

        registrarResposta($equipeId, $perguntaId, $membroId, $resposta, $acertou);

        $pontos = $acertou ? 1 : -1;
        atualizarPontuacao($equipeId, $pontos);

        $_SESSION['jogo']['historico'][] = [
            'equipe' => $equipeId,
            'pergunta' => $perguntaId,
            'acertou' => $acertou,
            'resposta' => $resposta,
            'membro' => $membroId,
        ];

        if (!$acertou) {
            $prenda = getPrendaAleatoria('penalidade');
            $_SESSION['jogo']['ultima_penalidade'] = [
                'equipe' => $equipeId,
                'prenda' => $prenda['descricao']
            ];
            // Remove bônus antigo se houver (opcional)
            unset($_SESSION['jogo']['ultimo_bonus']);
        } else {
            $bonus = getPrendaAleatoria('bonus');
            $_SESSION['jogo']['ultimo_bonus'] = [
                'equipe' => $equipeId,
                'bonus' => $bonus['descricao']
            ];
        }

        $_SESSION['jogo']['equipe_atual'] = sortearEquipe($_SESSION['jogo']['equipes'], $equipeId);
        $_SESSION['jogo']['perguntas_restantes']--;
        $_SESSION['jogo']['rodada_atual']++;

        if ($_SESSION['jogo']['perguntas_restantes'] <= 0) {
            header('Location: ranking.php');
            exit();
        } else {
            header('Location: game.php');
            exit();
        }
    }

    if (isset($_POST['ajustar_pontos'])) {
        $equipeId = (int)$_POST['equipe_id'];
        $pontos = (int)$_POST['pontos'];
        atualizarPontuacao($equipeId, $pontos);
        header('Location: game.php');
        exit();
    }
}

// Busca pergunta aleatória para exibir
$pergunta = getPerguntaAleatoria();

// Busca dados das equipes para exibir na tela
$equipes = [];
foreach ($_SESSION['jogo']['equipes'] as $id) {
    $equipes[] = getEquipeComMembros($id);
}

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
    <link rel="stylesheet" href="assets/css/game.css" />
</head>


<body>
    <div class="container py-5">
        <div class="game-container">
            <!-- Cabeçalho do Jogo -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary"><i class="fas fa-gamepad me-2"></i>Quiz Game</h1>
                <div class="text-end">
                    <span class="badge bg-secondary fs-6">Rodada <?= $_SESSION['jogo']['rodada_atual'] ?></span>
                    <span class="badge bg-primary fs-6 ms-2">
                        Perguntas restantes: <?= $_SESSION['jogo']['perguntas_restantes'] ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <!-- Área da Pergunta -->
                <div class="col-lg-8 mb-4">
                    <div class="question-card mb-4">
                        <!-- Exibe nível apenas se for difícil -->
                        <?php if (!empty($pergunta['nivel']) && strtolower($pergunta['nivel']) === 'difícil'): ?>
                            <div class="text-center mb-2">
                                <span class="badge bg-danger fs-6">
                                    Nível: <?= htmlspecialchars($pergunta['nivel']) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <h3 class="text-center mb-4"><?= htmlspecialchars($pergunta['pergunta']) ?></h3>

                        <form method="POST" novalidate>
                            <input type="hidden" name="pergunta_id" value="<?= $pergunta['id'] ?>" />

                            <div class="mb-3">
                                <label class="form-label text-white">Membro respondendo:</label>
                                <input type="text" class="form-control" readonly
                                    value="<?= htmlspecialchars($membroSorteado['nome']) ?>" />
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="resposta"
                                    value="<?= htmlspecialchars($pergunta['opcao1']) ?>"
                                    class="btn btn-light option-btn">
                                    A) <?= htmlspecialchars($pergunta['opcao1']) ?>
                                </button>
                                <button type="submit" name="resposta"
                                    value="<?= htmlspecialchars($pergunta['opcao2']) ?>"
                                    class="btn btn-light option-btn">
                                    B) <?= htmlspecialchars($pergunta['opcao2']) ?>
                                </button>
                                <button type="submit" name="resposta"
                                    value="<?= htmlspecialchars($pergunta['opcao3']) ?>"
                                    class="btn btn-light option-btn">
                                    C) <?= htmlspecialchars($pergunta['opcao3']) ?>
                                </button>
                                <button type="submit" name="resposta"
                                    value="<?= htmlspecialchars($pergunta['opcao4']) ?>"
                                    class="btn btn-light option-btn">
                                    D) <?= htmlspecialchars($pergunta['opcao4']) ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Área das Equipes -->
                <div class="col-lg-4">
                    <h3 class="text-center mb-3"><i class="fas fa-users me-2"></i>Equipes</h3>

                    <div class="teams-container">
                        <?php foreach ($equipes as $equipe): ?>
                            <div class="team-card p-3 rounded <?= $equipe['id'] === $equipeAtualId ? 'current-team' : '' ?>"
                                style="border-left-color: <?= getTeamColor($equipe['id']) ?>;">

                                <!-- Exibe penalidade ativa para esta equipe -->
                                <?php if (isset($_SESSION['jogo']['ultima_penalidade']) && $_SESSION['jogo']['ultima_penalidade']['equipe'] === $equipe['id']): ?>
                                    <div class="penalty-box mb-2">
                                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Penalidade:</strong>
                                        <?= htmlspecialchars($_SESSION['jogo']['ultima_penalidade']['prenda']) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0"><?= htmlspecialchars($equipe['nome']) ?></h4>
                                    <span class="badge bg-primary rounded-pill fs-6"><?= $equipe['pontos'] ?? 0 ?>
                                        pts</span>
                                </div>

                                <div class="mt-2">
                                    <small class="text-muted">Membros:
                                        <?= implode(', ', array_column($equipe['membros'], 'nome')) ?></small>
                                </div>

                                <?php if ($equipe['id'] === $equipeAtualId): ?>
                                    <div class="progress mt-2">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            style="width: 100%"></div>
                                    </div>
                                    <p class="text-center mt-1 mb-0 text-success"><small><strong>É a vez desta
                                                equipe!</strong></small></p>
                                <?php endif; ?>

                                <!-- Ajustar pontos -->
                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="equipe_id" value="<?= $equipe['id'] ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="pontos" class="form-control" placeholder="Ajustar pontos"
                                            required>
                                        <button type="submit" name="ajustar_pontos" class="btn btn-outline-secondary">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Bônus Global -->
                    <?php if (isset($_SESSION['jogo']['ultimo_bonus'])): ?>
                        <div class="bonus-box mt-3">
                            <strong><i class="fas fa-gift me-2"></i>Bônus para
                                <?= htmlspecialchars(getEquipeComMembros($_SESSION['jogo']['ultimo_bonus']['equipe'])['nome']) ?>:</strong>
                            <?= htmlspecialchars($_SESSION['jogo']['ultimo_bonus']['bonus']) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Finalizar Jogo -->
                    <div class="d-grid mt-3">
                        <a href="ranking.php" class="btn btn-danger">
                            <i class="fas fa-stop-circle me-2"></i>Finalizar Jogo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/game.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>