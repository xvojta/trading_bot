<?php
require_once(__DIR__  . '/../config/database.php');

$input = json_decode(file_get_contents('php://input'), true);

if(isset($input['id']))
{
    $id = filter_var($input['id'], FILTER_VALIDATE_INT);

    // Fetch trade history from the 'trades' table
    try {
        $stmt = $pdo->prepare('SELECT running, dip, sell, amount FROM `trade_settings` WHERE `trade_settings`.`id` = ?');
        $stmt->bindParam(1, $id);
        $stmt->execute(); // Execute the statement
        $status = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]; // Fetch the results
    
        //$stmt = $pdo->query('SELECT running, dip, sell, amount FROM `trade_settings` WHERE `trade_settings`.`id` = ' . $id);
        //$status = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'running' => $status['running'], 
        'dip' => $status['dip'], 'sell' => $status['sell'], 'amount' => $status['amount']]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to check the model status: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing input values.', 0]);
}
?>