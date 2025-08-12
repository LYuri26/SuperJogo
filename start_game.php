<?php
session_start();
require_once 'config/db.php';
require_once 'config/functions.php';

$pdo = getDBConnection();
$equipes = getEquipesComMembros();

if (count($equipes) < 2) {
    $_SESSION['error'] = "Você precisa de pelo menos 2 equipes para iniciar o jogo!";
    header("Location: index.php");
    exit;
}

$totalQuestions = filter_input(INPUT_POST, 'totalQuestions', FILTER_VALIDATE_INT);
if (!$totalQuestions || $totalQuestions < 1 || $totalQuestions > 100) {
    $_SESSION['error'] = "Número de perguntas inválido!";
    header("Location: index.php");
    exit;
}

// Monta array de IDs das equipes
$equipesIds = array_map(function ($e) {
    return (int)$e['id'];
}, $equipes);

// Inicializa pontuações com zero
$pontuacoes = array_combine($equipesIds, array_fill(0, count($equipesIds), 0));

// Prepara estrutura esperada por game.php em $_SESSION['jogo']
$_SESSION['jogo'] = [
    'equipes' => $equipesIds,
    'pontuacoes' => $pontuacoes,
    'perguntas_restantes' => $totalQuestions,
    'rodada_atual' => 1,
    // 'equipe_atual' será sorteada pelo game.php na primeira exibição
    'historico' => [],
];

// Redireciona para game.php
header("Location: game.php");
exit;
?>
