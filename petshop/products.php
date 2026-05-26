<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;
require 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$input = json_decode(file_get_contents('php://input'), true);

function sendJson($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

if ($method === 'GET') {
    if ($id) {
        $stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        sendJson($row ?: null);
    }

    $result = $conn->query('SELECT * FROM products ORDER BY id DESC');
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    sendJson($rows);
}

$name = trim($input['name'] ?? '');
$category = trim($input['category'] ?? '');
$price = $input['price'] ?? null;
$stock = $input['stock'] ?? null;

if ($method === 'POST' || $method === 'PUT') {
    $errors = [];
    if ($name === '') $errors[] = 'name is required';
    if ($category === '') $errors[] = 'category is required';
    if (!is_numeric($price) || $price < 0) $errors[] = 'price must be a non-negative number';
    if (!is_numeric($stock) || intval($stock) < 0) $errors[] = 'stock must be a non-negative integer';

    if ($errors) {
        sendJson(['errors' => $errors], 400);
    }

    $price = number_format((float)$price, 2, '.', '');
    $stock = intval($stock);
}

if ($method === 'POST') {
    $stmt = $conn->prepare('INSERT INTO products (name, category, price, stock) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssdi', $name, $category, $price, $stock);
    if ($stmt->execute()) {
        sendJson(['id' => $conn->insert_id], 201);
    }
    sendJson(['error' => $stmt->error], 500);
}

if ($method === 'PUT') {
    if (!$id) sendJson(['error' => 'Missing id'], 400);
    $stmt = $conn->prepare('UPDATE products SET name=?, category=?, price=?, stock=? WHERE id=?');
    $stmt->bind_param('ssdii', $name, $category, $price, $stock, $id);
    if ($stmt->execute()) {
        sendJson(['updated' => true]);
    }
    sendJson(['error' => $stmt->error], 500);
}

if ($method === 'DELETE') {
    if (!$id) sendJson(['error' => 'Missing id'], 400);
    $stmt = $conn->prepare('DELETE FROM products WHERE id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        sendJson(['deleted' => true]);
    }
    sendJson(['error' => $stmt->error], 500);
}

sendJson(['error' => 'Method not allowed'], 405);
