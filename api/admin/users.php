<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登录']);
    exit;
}

require_once __DIR__ . '/../data.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// 获取用户列表
if ($action === 'list') {
    $users = readJson('users');
    $dataPath = dirname(dirname(__DIR__)) . '/data';
    
    // 补充每个用户的详细信息
    foreach ($users as &$u) {
        $userInfoPath = $dataPath . '/users/' . $u['id'] . '.json';
        $userInfo = [];
        if (file_exists($userInfoPath)) {
            $userInfo = json_decode(file_get_contents($userInfoPath), true) ?: [];
        }
        $u['nickname'] = $userInfo['nickname'] ?? '';
        $u['avatar'] = $userInfo['avatar'] ?? '';
        $u['address'] = $userInfo['address'] ?? ['name' => '', 'phone' => '', 'detail' => ''];
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
    exit;
}

// 更新用户
if ($action === 'update') {
    $id = intval($_POST['id'] ?? 0);
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => '无效的用户ID']);
        exit;
    }

    $users = readJson('users');
    $found = false;

    // 检查用户名是否已被占用
    if (!empty($username)) {
        foreach ($users as $u) {
            if ($u['username'] === $username && $u['id'] !== $id) {
                echo json_encode(['success' => false, 'message' => '用户名已被占用']);
                exit;
            }
        }
    }

    foreach ($users as &$u) {
        if ($u['id'] === $id) {
            if (!empty($password)) {
                $u['password'] = $password;
            }
            if (!empty($username)) {
                $u['username'] = $username;
            }
            $u['role'] = $role;
            $found = true;
            break;
        }
    }

    if ($found) {
        writeJson('users', $users);
        
        // 同时更新用户详细信息
        $dataPath = dirname(dirname(__DIR__)) . '/data';
        $userInfoPath = $dataPath . '/users/' . $id . '.json';
        $userInfo = [];
        if (file_exists($userInfoPath)) {
            $userInfo = json_decode(file_get_contents($userInfoPath), true) ?: [];
        }
        
        if (isset($_POST['nickname'])) {
            $userInfo['nickname'] = $_POST['nickname'];
        }
        if (isset($_POST['avatar'])) {
            $userInfo['avatar'] = $_POST['avatar'];
        }
        if (isset($_POST['address'])) {
            $userInfo['address'] = json_decode($_POST['address'], true);
        }
        
        file_put_contents($userInfoPath, json_encode($userInfo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '用户不存在']);
    }
    exit;
}

// 删除用户
if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    $currentAdminId = intval($_SESSION['admin_id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => '无效的用户ID']);
        exit;
    }

    // 禁止删除自己
    if ($id === $currentAdminId) {
        echo json_encode(['success' => false, 'message' => '不能删除当前登录的管理员']);
        exit;
    }

    $users = readJson('users');
    $foundIndex = null;

    foreach ($users as $index => $u) {
        if ($u['id'] === $id) {
            $foundIndex = $index;
            break;
        }
    }

    if ($foundIndex === null) {
        echo json_encode(['success' => false, 'message' => '用户不存在']);
        exit;
    }

    // 禁止删除管理员账号
    if (($users[$foundIndex]['role'] ?? 'user') === 'admin') {
        echo json_encode(['success' => false, 'message' => '不能删除管理员账号']);
        exit;
    }

    unset($users[$foundIndex]);
    $users = array_values($users);
    writeJson('users', $users);

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的操作']);
