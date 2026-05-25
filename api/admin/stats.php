<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登录']);
    exit;
}

require_once __DIR__ . '/../data.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// 获取统计数据
if ($action === 'stats') {
    $products = readJson('products');
    $users = readJson('users');
    $orders = readJson('orders');
    
    $totalSales = 0;
    $paidOrders = 0;
    $pendingOrders = 0;
    $shippedOrders = 0;
    
    foreach ($orders as $o) {
        $total = floatval($o['total'] ?? 0);
        $totalSales += $total;
        if ($o['status'] === 'paid') {
            $paidOrders++;
        } elseif ($o['status'] === 'pending') {
            $pendingOrders++;
        } elseif ($o['status'] === 'shipped') {
            $shippedOrders++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalProducts' => count($products),
            'totalUsers' => count($users),
            'totalOrders' => count($orders),
            'totalSales' => $totalSales,
            'paidOrders' => $paidOrders,
            'pendingOrders' => $pendingOrders,
            'shippedOrders' => $shippedOrders
        ]
    ]);
    exit;
}

// 获取最近订单
if ($action === 'recent_orders') {
    $orders = readJson('orders');
    usort($orders, function($a, $b) {
        return strtotime($b['created_at'] ?? 0) - strtotime($a['created_at'] ?? 0);
    });
    $recentOrders = array_slice($orders, 0, 10);
    echo json_encode(['success' => true, 'orders' => $recentOrders]);
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的操作']);
