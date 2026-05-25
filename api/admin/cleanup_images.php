<?php
/**
 * 清理无用图片 API
 * 删除 uploads 目录下不被任何商品引用的图片
 */

header('Content-Type: application/json');

$baseDir = dirname(dirname(dirname(__FILE__)));
$uploadDir = $baseDir . '/uploads';
$productsFile = $baseDir . '/data/products.json';

$response = ['success' => true, 'deleted' => 0, 'freed' => '0 KB', 'message' => ''];

// 检查 uploads 目录
if (!is_dir($uploadDir)) {
    $response['success'] = false;
    $response['message'] = 'uploads 目录不存在';
    echo json_encode($response);
    exit;
}

// 扫描 uploads 目录下的所有图片
$uploadedFiles = glob($uploadDir . '/*');
$uploadedFiles = array_filter($uploadedFiles, 'is_file');

if (empty($uploadedFiles)) {
    $response['message'] = 'uploads 目录为空';
    echo json_encode($response);
    exit;
}

// 读取商品数据，获取所有被引用的图片
$referencedImages = [];

if (file_exists($productsFile)) {
    $products = json_decode(file_get_contents($productsFile), true);
    if ($products) {
        foreach ($products as $product) {
            if (!empty($product['image'])) {
                $images = explode(',', $product['image']);
                foreach ($images as $img) {
                    $img = trim($img);
                    if ($img && strpos($img, '/uploads/') === 0) {
                        $referencedImages[] = $img;
                    }
                }
            }
        }
    }
}

$referencedImages = array_unique($referencedImages);

// 找出无用图片并删除
$totalSize = 0;
$deleted = 0;

foreach ($uploadedFiles as $file) {
    $fileName = '/uploads/' . basename($file);
    if (!in_array($fileName, $referencedImages)) {
        $size = filesize($file);
        if (@unlink($file)) {
            $totalSize += $size;
            $deleted++;
        }
    }
}

// 格式化大小
if ($totalSize > 1024 * 1024) {
    $freed = round($totalSize / 1024 / 1024, 2) . ' MB';
} else {
    $freed = round($totalSize / 1024, 2) . ' KB';
}

$response['deleted'] = $deleted;
$response['freed'] = $freed;

if ($deleted === 0) {
    $response['message'] = '没有需要清理的图片';
} else {
    $response['message'] = '清理成功';
}

echo json_encode($response);
