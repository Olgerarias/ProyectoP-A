<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$mysqli = new mysqli('localhost', 'root', '', 'guitar_store');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$request = $_GET['request']; // Add this line to get the request type

switch ($method) {
    case 'GET':
        if ($request == 'guitars') {
            $result = $mysqli->query('SELECT * FROM guitars');
            $guitars = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($guitars);
        } elseif ($request == 'users') {
            $result = $mysqli->query('SELECT * FROM users');
            $users = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($users);
        }
        break;
    case 'POST':
        if ($request == 'guitars') {
            $stmt = $mysqli->prepare('INSERT INTO guitars (brand, model, price) VALUES (?, ?, ?)');
            $stmt->bind_param('ssd', $input['brand'], $input['model'], $input['price']);
            $stmt->execute();
            echo json_encode(['id' => $stmt->insert_id]);
        } elseif ($request == 'users') {
            $stmt = $mysqli->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $input['name'], $input['email'], password_hash($input['password'], PASSWORD_DEFAULT));
            $stmt->execute();
            echo json_encode(['id' => $stmt->insert_id]);
        }
        break;
    case 'PUT':
        if ($request == 'guitars') {
            $stmt = $mysqli->prepare('UPDATE guitars SET brand=?, model=?, price=? WHERE id=?');
            $stmt->bind_param('ssdi', $input['brand'], $input['model'], $input['price'], $input['id']);
            $stmt->execute();
            echo json_encode($input);
        } elseif ($request == 'users') {
            $stmt = $mysqli->prepare('UPDATE users SET name=?, email=?, password=? WHERE id=?');
            $stmt->bind_param('sssi', $input['name'], $input['email'], password_hash($input['password'], PASSWORD_DEFAULT), $input['id']);
            $stmt->execute();
            echo json_encode($input);
        }
        break;
    case 'DELETE':
        if ($request == 'guitars') {
            $stmt = $mysqli->prepare('DELETE FROM guitars WHERE id=?');
            $stmt->bind_param('i', $input['id']);
            $stmt->execute();
            echo json_encode(['result' => 'success']);
        } elseif ($request == 'users') {
            $stmt = $mysqli->prepare('DELETE FROM users WHERE id=?');
            $stmt->bind_param('i', $input['id']);
            $stmt->execute();
            echo json_encode(['result' => 'success']);
        }
        break;
}

$mysqli->close();
?>
