<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../data.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => '请输入用户名和密码']);
    exit;
}

if (strlen($username) < 3 || strlen($username) > 20) {
    echo json_encode(['success' => false, 'message' => '用户名长度需在3-20个字符之间']);
    exit;
}

if (strlen($password) < 6 || strlen($password) > 50) {
    echo json_encode(['success' => false, 'message' => '密码长度需在6-50个字符之间']);
    exit;
}

$users = readJson('users');

foreach ($users as $user) {
    if ($user['username'] === $username) {
        echo json_encode(['success' => false, 'message' => '用户名已存在']);
        exit;
    }
}

$newUser = [
    'id' => generateId('users'),
    'username' => $username,
    'password' => $password,
    'role' => 'user',
    'created_at' => date('Y-m-d H:i:s')
];

$users[] = $newUser;
writeJson('users', $users);

// 创建用户信息文件
$userInfo = [
    'id' => $newUser['id'],
    'username' => $newUser['username'],
    'nickname' => '',
    'avatar' => '',
    'address' => [
        'name' => '',
        'phone' => '',
        'detail' => ''
    ],
    'created_at' => $newUser['created_at']
];
$userInfoPath = dirname(dirname(__DIR__)) . '/data/users/' . $newUser['id'] . '.json';
file_put_contents($userInfoPath, json_encode($userInfo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo json_encode(['success' => true, 'message' => '注册成功', 'user' => [
    'id' => $newUser['id'],
    'username' => $newUser['username'],
    'nickname' => '',
    'avatar' => '',
    'address' => [
        'name' => '',
        'phone' => '',
        'detail' => ''
    ],
    'role' => $newUser['role']
]]);
