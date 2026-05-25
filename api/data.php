<?php

/** 将相对路径转为根路径，避免在子目录或不同页面下图片 404 */
function image_url($url) {
    if ($url === null || $url === '') return $url;
    $url = (string) $url;
    if (strpos($url, 'http') === 0 || (isset($url[0]) && $url[0] === '/')) return $url;
    return '/' . $url;
}

function getDataFilePath($type) {
    // __FILE__ 是 api/data.php，dirname(__FILE__) 是 api/，再 dirname 一次是项目根目录
    $baseDir = dirname(dirname(__FILE__));
    $path = $baseDir . '/data/' . $type . '.json';
    
    return $path;
}

function readJson($type) {
    $file = getDataFilePath($type);
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

function writeJson($type, $data) {
    $file = getDataFilePath($type);
    return file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function generateId($type) {
    $items = readJson($type);
    if (empty($items)) {
        return 1;
    }
    $ids = array_column($items, 'id');
    return max($ids) + 1;
}
