<?php
session_start();
header('Content-Type: application/json');

unset($_SESSION['user_id']);
unset($_SESSION['user_username']);
unset($_SESSION['user_role']);

echo json_encode(['success' => true]);
