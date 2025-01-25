<?php
// require_once your database connection
require_once(__DIR__  . '/../config/database.php');
require_once(__DIR__ . '/account_manager.php');

if(!$logged_in) {
    echo json_encode(['error' => 'Not logged in']);
    return;
}

// Fetch trade history from the 'trades' table
try {
    $stmt = $pdo->query("SELECT `id`, `name` FROM trade_settings WHERE owner=". $uid ." ORDER BY 'created_at' DESC");
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the trade history as a JSON response
    echo json_encode($models);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch models: ' . $e->getMessage()]);
}
?>
