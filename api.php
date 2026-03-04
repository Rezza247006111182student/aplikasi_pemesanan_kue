<?php
// Simple API router for /api/v1/*
require_once __DIR__ . '/app/Controllers/Api/KueApiController.php';
require_once __DIR__ . '/app/Controllers/Api/AuthApiController.php';
require_once __DIR__ . '/app/Database.php';

$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// remove query string and base path
$path = parse_url($uri, PHP_URL_PATH);
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$base = rtrim($scriptName, '/');
$path = preg_replace('#^' . preg_quote($base) . '#', '', $path);
$path = ltrim($path, '/');

$segments = explode('/', $path);
// expect api/v1/... -> segments[0]=api, [1]=v1, [2]=resource
if (count($segments) < 3 || $segments[0] !== 'api' || $segments[1] !== 'v1') {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit();
}

$resource = isset($segments[2]) ? $segments[2] : '';
$id = isset($segments[3]) ? $segments[3] : null;

switch ($resource) {
    case 'kue':
        $ctrl = new KueApiController();
        if ($method === 'GET' && $id === null) {
            $ctrl->index();
        } elseif ($method === 'GET' && $id !== null) {
            $ctrl->show($id);
        } elseif ($method === 'POST' && $id === null) {
            $ctrl->create();
        } elseif ($method === 'PUT' && $id !== null) {
            $ctrl->update($id);
        } elseif ($method === 'DELETE' && $id !== null) {
            $ctrl->delete($id);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit();
        }
        break;

    case 'auth':
        $ctrl = new AuthApiController();
        if ($method === 'POST' && isset($segments[3]) && $segments[3] === 'login') {
            $ctrl->login();
        } elseif ($method === 'POST' && isset($segments[3]) && $segments[3] === 'logout') {
            $ctrl->logout();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit();
        }
        break;

    case 'orders':
        require_once __DIR__ . '/app/Controllers/Api/OrderApiController.php';
        $ctrl = new OrderApiController();
        if ($method === 'GET' && $id === null) {
            $ctrl->index();
        } elseif ($method === 'GET' && $id !== null) {
            $ctrl->show($id);
        } elseif ($method === 'POST' && $id === null) {
            $ctrl->create();
        } elseif ($method === 'PUT' && $id !== null) {
            $ctrl->update($id);
        } elseif ($method === 'DELETE' && $id !== null) {
            $ctrl->delete($id);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        exit();
}
