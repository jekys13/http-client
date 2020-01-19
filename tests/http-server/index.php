<?php
/**
 * This script is the part of the HTTP Client project
 * Script helps to simulate HTTP Server in Unit Tests
 */

$params = [];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $params = $_GET;

    break;

    case 'POST':
        $params = $_POST;

    break;

    default:
        parse_str(
            file_get_contents('php://input'),
            $requestParams
        );

        $params = $requestParams;

    break;
}

$response = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'params' => $params
];

echo json_encode($response);
