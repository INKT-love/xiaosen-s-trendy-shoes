<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登录']);
    exit;
}

require_once __DIR__ . '/../data.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// 获取商品列表
if ($action === 'list') {
    $products = readJson('products');
    echo json_encode(['success' => true, 'products' => $products]);
    exit;
}

// 创建商品
if ($action === 'create') {
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $image = $_POST['image'] ?? '';
    $tags = array_map('trim', explode(',', $_POST['tags'] ?? ''));
    $is_new = isset($_POST['is_new']) && $_POST['is_new'] === '1';
    $jump_tag = $_POST['jump_tag'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if (empty($name) || $price <= 0 || empty($image)) {
        echo json_encode(['success' => false, 'message' => '请填写完整信息']);
        exit;
    }

    $products = readJson('products');
    $newId = count($products) > 0 ? max(array_column($products, 'id')) + 1 : 1;

    $products[] = [
        'id' => $newId,
        'name' => $name,
        'price' => $price,
        'image' => $image,
        'tags' => array_filter($tags),
        'is_new' => $is_new,
        'jump_tag' => trim($jump_tag),
        'description' => $description
    ];

    writeJson('products', $products);
    echo json_encode(['success' => true]);
    exit;
}

// 更新商品
if ($action === 'update') {
    $id = intval($_POST['id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $image = $_POST['image'] ?? '';
    $tags = array_map('trim', explode(',', $_POST['tags'] ?? ''));
    $is_new = isset($_POST['is_new']) && $_POST['is_new'] === '1';
    $jump_tag = $_POST['jump_tag'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($id <= 0 || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => '请填写完整信息']);
        exit;
    }

    // 图片始终更新：允许清空图片
    $image = trim($image ?? '');

    $products = readJson('products');
    $found = false;

    foreach ($products as &$p) {
        if ($p['id'] === $id) {
            // 清理已被删除的图片文件
            $oldImages = !empty($p['image']) ? explode(',', $p['image']) : [];
            $newImages = !empty($image) ? explode(',', $image) : [];
            $uploadDir = dirname(dirname(__FILE__)) . '/..';
            foreach ($oldImages as $oldImg) {
                $oldImg = trim($oldImg);
                if ($oldImg && strpos($oldImg, '/uploads/') === 0 && !in_array($oldImg, $newImages)) {
                    $filePath = $uploadDir . $oldImg;
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $p['name'] = $name;
            $p['price'] = $price;
            // 始终更新图片字段（允许空值表示清空图片）
            $p['image'] = $image;
            $p['tags'] = array_filter($tags);
            $p['is_new'] = $is_new;
            $p['jump_tag'] = trim($jump_tag);
            $p['description'] = $description;
            $found = true;
            break;
        }
    }

    if ($found) {
        writeJson('products', $products);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '商品不存在']);
    }
    exit;
}

// 删除商品
if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => '无效的商品ID']);
        exit;
    }

    $products = readJson('products');
    $originalCount = count($products);
    
    // 找到要删除的商品，获取其图片路径
    $imagesToDelete = [];
    foreach ($products as $p) {
        if ($p['id'] === $id) {
            if (!empty($p['image'])) {
                $images = explode(',', $p['image']);
                foreach ($images as $img) {
                    $img = trim($img);
                    if ($img && strpos($img, '/uploads/') === 0) {
                        $imagesToDelete[] = dirname(dirname(__FILE__)) . '/..' . $img;
                    }
                }
            }
            break;
        }
    }
    
    $products = array_values(array_filter($products, function($p) use ($id) {
        return $p['id'] !== $id;
    }));

    if (count($products) < $originalCount) {
        // 删除商品图片文件
        foreach ($imagesToDelete as $imgPath) {
            if (file_exists($imgPath)) {
                @unlink($imgPath);
            }
        }
        writeJson('products', $products);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '商品不存在']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的操作']);
