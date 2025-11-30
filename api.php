<?php
// api.php - helper to call the Go API at http://localhost:8080
function api_request($method, $path, $data = null) {
    $base = 'http://localhost:8080';
    $url = rtrim($base, '/') . '/' . ltrim($path, '/');

    $ch = curl_init();
    $headers = ['Accept: application/json'];

    if ($method === 'GET' && $data && is_array($data)) {
        $url .= '?' . http_build_query($data);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
        $payload = json_encode($data ?: new stdClass());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($payload);
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $resp = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        return ['error' => $err, 'status' => 500];
    }

    $decoded = json_decode($resp, true);
    return ['status' => $code, 'body' => $decoded, 'raw' => $resp];
}

?>
