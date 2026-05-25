<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../api/data.php';

// 获取请求数据
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['items'])) {
    echo json_encode(['success' => false, 'message' => '订单数据无效']);
    exit;
}

// 计算总价
$total = 0;
foreach ($input['items'] as $item) {
    $total += floatval($item['price']) * intval($item['quantity']);
}

// 生成订单号
$orderId = 'XS' . date('YmdHis') . rand(100, 999);

// 创建订单
$customer = [
    'username' => $input['customer']['username'] ?? '',
    'name'     => $input['customer']['name'] ?? '',
    'phone'    => $input['customer']['phone'] ?? '',
    'address'  => $input['customer']['address'] ?? ''
];

$order = [
    'order_id'     => $orderId,
    'items'        => $input['items'],
    'total'        => $total,
    'status'       => 'verifying',
    'created_at'   => date('Y-m-d H:i:s'),
    'customer'     => $customer,
    'username'     => $customer['username'],
    'recipient'    => $customer['name'],
    'phone'        => $customer['phone'],
    'address'      => $customer['address'],
    'payment_type' => $input['payment_type'] ?? 'wechat'
];

// 读取现有订单
$orders = readJson('orders');

// 添加新订单到列表开头
array_unshift($orders, $order);

// 保存到 JSON 文件
writeJson('orders', $orders);

echo json_encode(['success' => true, 'order_id' => $orderId]);
