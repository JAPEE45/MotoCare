<?php
function sendMessage($number, $message) {
    $url = "https://prorestoration-enrico-worrisome.ngrok-free.dev/send_sms";

    $data = [
        "number"  => $number,
        "message" => $message
    ];

    // encode payload
    $payload = json_encode($data);
    if ($payload === false) {
        return "JSON encode error: " . json_last_error_msg();
    }

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $response = curl_exec($ch);

    // network / curl errors
    if ($response === false) {
        $err = curl_error($ch);
        curl_close($ch);
        return "cURL Error: " . $err;
    }

    // get HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // optional: treat non-2xx as error (adjust as needed)
    if ($httpCode < 200 || $httpCode >= 300) {
        return "HTTP Error: {$httpCode} - Response: " . $response;
    }

    return $response;
}

