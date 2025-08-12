<?php
require_once 'config/db.php';
$pdo = getDBConnection();

$stmt = $pdo->query("
    SELECT e.id, e.nome AS team_name, m.nome AS member_name
    FROM equipes e
    LEFT JOIN membros m ON e.id = m.equipe_id
    ORDER BY e.id
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$teams = [];
foreach ($rows as $row) {
    $id = $row['id'];
    if (!isset($teams[$id])) {
        $teams[$id] = [
            'id' => $id,
            'name' => $row['team_name'],
            'members' => []
        ];
    }
    if ($row['member_name']) {
        $teams[$id]['members'][] = $row['member_name'];
    }
}

header('Content-Type: application/json');
echo json_encode(array_values($teams));
