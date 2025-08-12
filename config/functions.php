<?php
require_once 'db.php'; // Arquivo de conexão com o banco

// ========================= EQUIPES =========================
function criarEquipe($nome)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO equipes (nome, pontos) VALUES (?, 0)");
    $stmt->execute([$nome]);
    return $pdo->lastInsertId();
}

function adicionarMembro($idEquipe, $nomeMembro)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO membros (equipe_id, nome) VALUES (?, ?)");
    return $stmt->execute([$idEquipe, $nomeMembro]);
}

function removerEquipe($id)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM membros WHERE equipe_id = ?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM equipes WHERE id = ?");
    return $stmt->execute([$id]);
}

function getEquipeComMembros($idEquipe)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM equipes WHERE id = ?");
    $stmt->execute([$idEquipe]);
    $equipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipe) {
        return null;  // Equipe não encontrada
    }

    $stmt = $pdo->prepare("SELECT * FROM membros WHERE equipe_id = ?");
    $stmt->execute([$idEquipe]);
    $equipe['membros'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $equipe;
}

function getEquipesComMembros()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM equipes");
    $equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($equipes as &$equipe) {
        $stmt = $pdo->prepare("SELECT * FROM membros WHERE equipe_id = ?");
        $stmt->execute([$equipe['id']]);
        $equipe['membros'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $equipes;
}

// ========================= PERGUNTAS =========================
function getPerguntaById($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM perguntas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPerguntaAleatoria()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM perguntas ORDER BY RAND() LIMIT 1");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========================= PRENDAS =========================
function getPrendaAleatoria($tipo = null)
{
    global $pdo;
    if ($tipo) {
        $stmt = $pdo->prepare("SELECT * FROM prendas WHERE tipo = ? ORDER BY RAND() LIMIT 1");
        $stmt->execute([$tipo]);
    } else {
        $stmt = $pdo->query("SELECT * FROM prendas ORDER BY RAND() LIMIT 1");
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========================= RESPOSTAS E PONTUAÇÃO =========================
// Ajustada para usar tabela rodadas
function registrarResposta($idEquipe, $idPergunta, $idMembro, $resposta, $acertou)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO rodadas (equipe_id, pergunta_id, membro_id, resposta, acertou, data_resposta) VALUES (?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([$idEquipe, $idPergunta, $idMembro, $resposta, $acertou ? 1 : 0]);
}

function atualizarPontuacao($idEquipe, $pontos)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE equipes SET pontos = pontos + ? WHERE id = ?");
    return $stmt->execute([$pontos, $idEquipe]);
}
