<?php
declare(strict_types=1);

header('Content-Type: application/json');

$seriesFile = __DIR__ . '/../../data/series.json';

function readSeries() {
    global $seriesFile;
    $content = file_get_contents($seriesFile);
    return $content ? json_decode($content, true) : [];
}

function writeSeries($data) {
    global $seriesFile;
    return file_put_contents($seriesFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';
    
    if ($action === 'list') {
        $series = readSeries();
        echo json_encode(['success' => true, 'series' => $series]);
        exit;
    }
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $slug = trim($_POST['slug'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $display = trim($_POST['display'] ?? '');
        $display_cn = trim($_POST['display_cn'] ?? '');
        $enabled = isset($_POST['enabled']) && $_POST['enabled'] === '1';
        $hot_tags = isset($_POST['hot_tags']) ? array_filter(array_map('trim', explode(',', $_POST['hot_tags']))) : [];
        
        if (empty($slug) || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'slug和名称不能为空']);
            exit;
        }
        
        $series = readSeries();
        
        if ($action === 'create') {
            // 检查slug是否已存在
            foreach ($series as $s) {
                if ($s['slug'] === $slug) {
                    echo json_encode(['success' => false, 'message' => 'slug已存在']);
                    exit;
                }
            }
            $series[] = [
                'slug' => $slug,
                'name' => $name,
                'display' => $display ?: $name . ' SERIES',
                'display_cn' => $display_cn ?: $name . '系列',
                'enabled' => $enabled,
                'hot_tags' => $hot_tags
            ];
        } else {
            // update
            $id = $_POST['id'] ?? null;
            foreach ($series as &$s) {
                if ($s['slug'] === $id || (isset($s['id']) && $s['id'] == $id)) {
                    $s['name'] = $name;
                    $s['display'] = $display ?: $name . ' SERIES';
                    $s['display_cn'] = $display_cn ?: $name . '系列';
                    $s['enabled'] = $enabled;
                    $s['hot_tags'] = $hot_tags;
                    break;
                }
            }
        }
        
        if (writeSeries($series)) {
            echo json_encode(['success' => true, 'message' => '保存成功']);
        } else {
            echo json_encode(['success' => false, 'message' => '保存失败']);
        }
        exit;
    }
    
    if ($action === 'delete') {
        $slug = $_POST['slug'] ?? '';
        $series = readSeries();
        $originalCount = count($series);
        $series = array_values(array_filter($series, function($s) use ($slug) {
            return $s['slug'] !== $slug;
        }));
        
        if (count($series) < $originalCount) {
            if (writeSeries($series)) {
                echo json_encode(['success' => true, 'message' => '删除成功']);
            } else {
                echo json_encode(['success' => false, 'message' => '删除失败']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '未找到该系列']);
        }
        exit;
    }
    
    if ($action === 'toggle') {
        $slug = $_POST['slug'] ?? '';
        $series = readSeries();
        
        foreach ($series as &$s) {
            if ($s['slug'] === $slug) {
                $s['enabled'] = !$s['enabled'];
                break;
            }
        }
        
        if (writeSeries($series)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => '操作失败']);
        }
        exit;
    }
}

echo json_encode(['success' => false, 'message' => '无效的请求']);
