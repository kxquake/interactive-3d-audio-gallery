<?php
$action = $_GET['action'] ?? '';
if ($action === '') {
    readfile(__DIR__ . '/index.html');
    exit;
}
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
header('X-Content-Type-Options: nosniff');
if (!is_string($action) || !in_array($action, ['apiGetProducts', 'apiGetProduct'], true)) {
    http_response_code(404);
    echo json_encode(['error' => 'Action not found']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    require_once __DIR__ . '/application/controller/controller.php';
    $controller = new Controller();
    $controller->$action();
} catch (Throwable $e) {
    error_log('Gallery API: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Product data is unavailable']);
}
