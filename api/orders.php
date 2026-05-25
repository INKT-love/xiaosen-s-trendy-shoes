<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../api/data.php';

$orders = readJson('orders');
echo json_encode(['success' => true, 'orders' => $orders]);
