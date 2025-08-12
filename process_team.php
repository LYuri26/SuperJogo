<?php
session_start();

require_once 'config/db.php';
require_once 'config/functions.php';

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Se veio para remover equipe
    if (isset($_POST['removeTeamId'])) {
        $removeTeamId = intval($_POST['removeTeamId']);
        if ($removeTeamId > 0) {
            try {
                // Começa transação
                $pdo->beginTransaction();

                // Remove membros da equipe
                $stmt = $pdo->prepare("DELETE FROM membros WHERE equipe_id = ?");
                $stmt->execute([$removeTeamId]);

                // Remove equipe
                $stmt = $pdo->prepare("DELETE FROM equipes WHERE id = ?");
                $stmt->execute([$removeTeamId]);

                $pdo->commit();

                $_SESSION['msg'] = "Equipe removida com sucesso!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Erro ao remover equipe: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID inválido para remoção.";
        }

        header("Location: index.php");
        exit;
    }

    // Se veio para cadastrar equipe nova
    if (isset($_POST['teamName'], $_POST['members'])) {
        $teamName = trim($_POST['teamName']);
        $members = $_POST['members'];

        if (empty($teamName)) {
            $_SESSION['error'] = "O nome da equipe não pode ser vazio.";
            header("Location: index.php");
            exit;
        }

        // Remove membros vazios
        $members = array_filter(array_map('trim', $members));
        if (count($members) === 0) {
            $_SESSION['error'] = "Adicione pelo menos um membro à equipe.";
            header("Location: index.php");
            exit;
        }

        try {
            // Verifica se já existe equipe com o mesmo nome (case insensitive)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM equipes WHERE LOWER(nome) = LOWER(?)");
            $stmt->execute([$teamName]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $_SESSION['error'] = "Já existe uma equipe com esse nome.";
                header("Location: index.php");
                exit;
            }

            // Inicia transação para inserir equipe + membros
            $pdo->beginTransaction();

            // Insere equipe
            $stmt = $pdo->prepare("INSERT INTO equipes (nome) VALUES (?)");
            $stmt->execute([$teamName]);
            $teamId = $pdo->lastInsertId();

            // Insere membros
            $stmt = $pdo->prepare("INSERT INTO membros (equipe_id, nome) VALUES (?, ?)");
            foreach ($members as $memberName) {
                $memberName = trim($memberName);
                if ($memberName !== '') {
                    $stmt->execute([$teamId, $memberName]);
                }
            }

            $pdo->commit();

            $_SESSION['msg'] = "Equipe '$teamName' cadastrada com sucesso!";
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erro ao cadastrar equipe: " . $e->getMessage();
            header("Location: index.php");
            exit;
        }
    }
}

// Se chegar aqui, redireciona para a página principal
header("Location: index.php");
exit;
