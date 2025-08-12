<?php
session_start();
require_once 'config/db.php';

$pdo = getDBConnection();

try {
    // Apaga rodadas primeiro (depende de membros e equipes)
    $pdo->exec("DELETE FROM rodadas");

    // Apaga membros (depende de equipes)
    $pdo->exec("DELETE FROM membros");

    // Apaga equipes
    $pdo->exec("DELETE FROM equipes");

    // Limpa sessão do jogo
    unset($_SESSION['jogo']);

    // Redireciona para a página inicial
    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    echo "Erro ao limpar dados: " . $e->getMessage();
}
