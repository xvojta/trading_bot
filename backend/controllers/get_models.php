<?php
// require_once your database connection
require_once '../config/database.php';

// Fetch trade history from the 'trades' table
try {
    $stmt = $pdo->query("SELECT `id`, `name` FROM trade_settings ORDER BY 'created_at' DESC");
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the trade history as a JSON response
    echo json_encode($models);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch trade history: ' . $e->getMessage()]);
}
?>
