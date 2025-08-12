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
                'prenda' => $prenda['descricao'],
                'completa' => false
            ];
            unset($_SESSION['jogo']['ultimo_bonus']);
        } else {
            $bonus = getPrendaAleatoria('bonus');
            $_SESSION['jogo']['ultimo_bonus'] = [
                'equipe' => $equipeId,
                'bonus' => $bonus['descricao'],
                'completa' => false
            ];
            unset($_SESSION['jogo']['ultima_penalidade']);
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

    if (isset($_POST['pular_pergunta'])) {
        $_SESSION['jogo']['equipe_atual'] = sortearEquipe($_SESSION['jogo']['equipes'], $equipeAtualId);
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

    if (isset($_POST['completar_tarefa'])) {
        $equipeId = (int)$_POST['equipe_id'];
        $tipo = $_POST['tipo'] ?? '';

        if ($tipo === 'penalidade' && isset($_SESSION['jogo']['ultima_penalidade']) && $_SESSION['jogo']['ultima_penalidade']['equipe'] === $equipeId) {
            $_SESSION['jogo']['ultima_penalidade']['completa'] = true;
        }

        if ($tipo === 'bonus' && isset($_SESSION['jogo']['ultimo_bonus']) && $_SESSION['jogo']['ultimo_bonus']['equipe'] === $equipeId) {
            $_SESSION['jogo']['ultimo_bonus']['completa'] = true;
        }

        header('Location: game.php');
        exit();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .game-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        max-width: 1100px;
        margin: 2rem auto;
    }

    .question-card {
        background: #3b74d1;
        color: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 8px 16px rgba(59, 116, 209, 0.5);
        margin-bottom: 1.5rem;
    }

    .team-card {
        background: white;
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        border-left: 6px solid;
    }

    .current-team {
        animation: highlight 2s infinite;
    }

    .penalty-box {
        background: linear-gradient(135deg, #ff4d4d 0%, #b22222 100%);
        color: white;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(178, 34, 34, 0.3);
        border-left: 4px solid #ff8a8a;
        animation: pulse 2s infinite;
    }

    .bonus-box {
        background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1.5rem;
        box-shadow: 0 4px 16px rgba(76, 175, 80, 0.3);
        border-left: 4px solid #a5d6a7;
        animation: float 3s ease-in-out infinite;
    }

    .option-btn {
        width: 100%;
        text-align: left;
        padding: 0.85rem 1.5rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        border: 2.5px solid #2e62c7;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .option-btn:hover {
        background-color: #dbe9ff;
        transform: translateX(4px);
    }

    .task-completed {
        opacity: 0.6;
        position: relative;
    }

    .task-completed::after {
        content: "✓ CONCLUÍDO";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
        color: #2e7d32;
        z-index: 10;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(178, 34, 34, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(178, 34, 34, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(178, 34, 34, 0);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    @keyframes highlight {

        0%,
        100% {
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.5);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(74, 144, 226, 0.3);
        }
    }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="game-container">
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
                        <?php if (!empty($pergunta['nivel']) && strtolower($pergunta['nivel']) === 'difícil'): ?>
                        <div class="text-center mb-3">
                            <span class="badge bg-danger fs-5 p-2">
                                <i class="fas fa-exclamation-triangle me-1"></i> NÍVEL DIFÍCIL
                            </span>
                        </div>
                        <?php endif; ?>

                        <h3 class="text-center mb-4"><?= htmlspecialchars($pergunta['pergunta']) ?></h3>

                        <form method="POST" novalidate>
                            <input type="hidden" name="pergunta_id" value="<?= $pergunta['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label text-white">Membro respondendo:</label>
                                <input type="text" class="form-control" readonly
                                    value="<?= htmlspecialchars($membroSorteado['nome'] ?? 'Nenhum membro selecionado') ?>">
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

                            <div class="d-grid mt-2">
                                <button type="submit" name="pular_pergunta" class="btn btn-warning">
                                    <i class="fas fa-forward me-2"></i>Pular Pergunta
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

                            <!-- Penalidade -->
                            <?php if (
                                    isset($_SESSION['jogo']['ultima_penalidade']) &&
                                    $_SESSION['jogo']['ultima_penalidade']['equipe'] === $equipe['id'] &&
                                    !$_SESSION['jogo']['ultima_penalidade']['completa']
                                ): ?>
                            <div class="penalty-box mb-2">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Penalidade:</strong>
                                <?= htmlspecialchars($_SESSION['jogo']['ultima_penalidade']['prenda']) ?>
                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="equipe_id" value="<?= $equipe['id'] ?>">
                                    <input type="hidden" name="tipo" value="penalidade">
                                    <button type="submit" name="completar_tarefa" class="btn btn-success btn-sm">
                                        <i class="fas fa-check-circle me-1"></i>Concluir Penalidade
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>

                            <!-- Bônus -->
                            <?php if (
                                    isset($_SESSION['jogo']['ultimo_bonus']) &&
                                    $_SESSION['jogo']['ultimo_bonus']['equipe'] === $equipe['id'] &&
                                    !$_SESSION['jogo']['ultimo_bonus']['completa']
                                ): ?>
                            <div class="bonus-box mb-2">
                                <strong><i class="fas fa-gift me-2"></i>Bônus:</strong>
                                <?= htmlspecialchars($_SESSION['jogo']['ultimo_bonus']['bonus']) ?>
                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="equipe_id" value="<?= $equipe['id'] ?>">
                                    <input type="hidden" name="tipo" value="bonus">
                                    <button type="submit" name="completar_tarefa" class="btn btn-success btn-sm">
                                        <i class="fas fa-check-circle me-1"></i>Concluir Bônus
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($equipe['nome'] ?? '') ?>
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