<?php

// enforce strict typing
declare(strict_types=1);

function json_response(array $data, int $status = 200): void {
    // Set the HTTP response status code (e.g. 200, 400, 404, 500)
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
     // Convert the PHP array into a JSON string and output it
    echo json_encode($data);
    exit;
}

function get_json_input(): array {

    // Read the raw HTTP request body
    // This is where fetch() sends its JSON payload
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function require_fields(array $data, array $fields): array {
    $errors = [];
    foreach ($fields as $f) {
        if (!isset($data[$f]) || trim((string)$data[$f]) === '') {
            $errors[$f] = 'Required';
        }
    }
    return $errors;
}

function json_error(string $message, int $status = 400, array $extra = []): void {
    json_response(array_merge([
        'ok' => false,
        'error' => $message
    ], $extra), $status);
}
