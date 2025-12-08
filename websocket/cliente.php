<?php
$host = 'rutasws-f6hhc6bmekbbekfe.mexicocentral-01.azurewebsites.net';
$port = 443;

$socket = stream_socket_client(
    "tls://$host:$port",
    $errno,
    $errstr,
    30,
    STREAM_CLIENT_CONNECT
);

if (!$socket) {
    die("❌ Error de conexión: $errstr ($errno)\n");
}

// ✅ HANDSHAKE WSS CORRECTO
$key = base64_encode(random_bytes(16));
$headers = "GET / HTTP/1.1\r\n" .
           "Host: $host\r\n" .
           "Upgrade: websocket\r\n" .
           "Connection: Upgrade\r\n" .
           "Sec-WebSocket-Key: $key\r\n" .
           "Sec-WebSocket-Version: 13\r\n\r\n";

fwrite($socket, $headers);
$response = fread($socket, 1500);

if (strpos($response, '101') === false) {
    die("❌ Handshake fallido:\n$response\n");
}

echo "✅ Conectado correctamente al WebSocket seguro\n";

// ✅ FUNCIÓN DE MASCARADO VÁLIDA PARA CLIENTE
function mask($text) {
    $b1 = chr(129);
    $length = strlen($text);

    if ($length <= 125) {
        $header = chr($length);
    } elseif ($length <= 65535) {
        $header = chr(126) . pack("n", $length);
    } else {
        $header = chr(127) . pack("J", $length);
    }

    $mask = random_bytes(4);
    $masked = '';

    for ($i = 0; $i < $length; $i++) {
        $masked .= $text[$i] ^ $mask[$i % 4];
    }

    return $b1 . chr(ord($header) | 0x80) . $mask . $masked;
}

// ✅ SIMULACIÓN CON ID DE COMBI (SE RESPETA)
$id_combi = 482;
$lat = 18.5481597;
$lon = -88.2885141;
$speed = 0;

while (true) {
    $timestamp = round(microtime(true) * 1000);

    $data = [
        'id_combi'  => $id_combi,
        'latitude' => $lat,
        'longitude'=> $lon,
        'speed'    => $speed,
        'timestamp'=> $timestamp
    ];

    $json = json_encode($data);
    fwrite($socket, mask($json));

    echo "📡 Enviado: $json\n";

    // Simular movimiento
    $lat += 0.00001;
    $lon += 0.00001;
    $speed = rand(10, 50);

    sleep(1);
}
