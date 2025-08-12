<?php
session_start();
require_once 'config/db.php';

$pdo = getDBConnection();

try {
    // Zera a pontuação das equipes (mantém equipes e membros)
    $pdo->exec("UPDATE equipes SET pontos = 0");

    // Apaga histórico de rodadas para reiniciar o jogo
    $pdo->exec("DELETE FROM rodadas");

    // Limpar dados da sessão do jogo para reiniciar
    unset($_SESSION['jogo']);

    // Redireciona para a página inicial
    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    echo "Erro ao reiniciar o jogo: " . $e->getMessage();
}
