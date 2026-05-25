<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['admin_id']) && $_SESSION['admin_role'] === 'admin') {
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'role' => $_SESSION['admin_role']
        ]
    ]);
} else {
    echo json_encode(['success' => false]);
}
