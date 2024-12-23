<?php
require_once('../config/database.php');

$input = json_decode(file_get_contents('php://input'), true);

if(isset($input['id']))
{
    $id = filter_var($input['id'], FILTER_VALIDATE_INT);

    // Fetch trade history from the 'trades' table
    try {
        $pdo->query('UPDATE `trade_settings` SET `running` = 0 WHERE `trade_settings`.`id` = ' . $id);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to start the model: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing input values.', 0]);
}
?>