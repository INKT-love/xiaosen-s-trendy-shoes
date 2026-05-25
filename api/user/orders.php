<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../data.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '请先登录']);
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['user_username'] ?? '';

// 获取订单列表
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $orders = readJson('orders');
    
    // 筛选当前用户的订单
    $userOrders = array_filter($orders, function($order) use ($username) {
        return isset($order['username']) && $order['username'] === $username;
    });
    
    // 按时间倒序
    usort($userOrders, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    echo json_encode(['success' => true, 'orders' => array_values($userOrders)]);
    exit;
}

// 更新订单状态
if ($action === 'update_status') {
    $orderId = $_POST['order_id'] ?? '';
    $status = $_POST['status'] ?? '';
    
    if (empty($orderId) || empty($status)) {
        echo json_encode(['success' => false, 'message' => '参数不完整']);
        exit;
    }
    
    $orders = readJson('orders');
    $found = false;
    
    foreach ($orders as &$order) {
        if ($order['order_id'] === $orderId && isset($order['username']) && $order['username'] === $username) {
            $order['status'] = $status;
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

// 删除当前用户所有订单
if ($action === 'delete_all') {
    $orders = readJson('orders');
    
    // 保留非当前用户的订单
    $remainingOrders = array_filter($orders, function($order) use ($username) {
        return !isset($order['username']) || $order['username'] !== $username;
    });
    
    writeJson('orders', array_values($remainingOrders));
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的操作']);
