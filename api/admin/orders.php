<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登录']);
    exit;
}

require_once __DIR__ . '/../data.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// 获取订单列表
if ($action === 'list') {
    $orders = readJson('orders');
    echo json_encode(['success' => true, 'orders' => $orders]);
    exit;
}

// 更新订单状态
if ($action === 'update') {
    $orderId = $_POST['order_id'] ?? '';
    $status = $_POST['status'] ?? '';

    if (empty($orderId) || empty($status)) {
        echo json_encode(['success' => false, 'message' => '参数不完整']);
        exit;
    }

    $orders = readJson('orders');
    $found = false;

    foreach ($orders as &$o) {
        if ($o['order_id'] === $orderId) {
            $o['status'] = $status;
            $found = true;
            break;
        }
    }

    if ($found) {
        writeJson('orders', $orders);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '订单不存在']);
    }
    exit;
}

// 删除订单
if ($action === 'delete') {
    $orderId = $_POST['order_id'] ?? '';

    if (empty($orderId)) {
        echo json_encode(['success' => false, 'message' => '无效的订单号']);
        exit;
    }

    $orders = readJson('orders');
    $originalCount = count($orders);
    $orders = array_values(array_filter($orders, function($o) use ($orderId) {
        return $o['order_id'] !== $orderId;
    }));

    if (count($orders) < $originalCount) {
        writeJson('orders', $orders);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '订单不存在']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的操作']);
