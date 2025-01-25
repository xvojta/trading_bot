<?php
require_once(__DIR__  . '/../config/database.php');

try {
    $pdo->query('DELETE FROM `trade_history`');
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed clear history: ' . $e->getMessage()]);
}
?>