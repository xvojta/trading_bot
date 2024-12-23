<?php
require_once('../config/database.php');

$input = json_decode(file_get_contents('php://input'), true);

if(isset($input['id']))
{
    $id = filter_var($input['id'], FILTER_VALIDATE_INT);

    // Fetch trade history from the 'trades' table
    try {
        $stmt = $pdo->query('SELECT running FROM `trade_settings` WHERE `trade_settings`.`id` = ' . $id);
        $status = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'running' => $status]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to check the model status: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing input values.', 0]);
}
?>