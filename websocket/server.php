<?php
$host = '0.0.0.0';
$port = 8080;

$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($server, $host, $port);
socket_listen($server);
socket_set_nonblock($server);

$clients = [];
$handshaked = new SplObjectStorage();

echo "Servidor WebSocket iniciado en $host:$port\n";

while (true) {
    $readSockets = $clients;
    $readSockets[] = $server;
    $write = $except = null;

    if (socket_select($readSockets, $write, $except, 0, 10) > 0) {
        foreach ($readSockets as $socket) {
            if ($socket === $server) {
                // Nuevo cliente
                $client = socket_accept($server);
                if ($client) {
                    socket_set_nonblock($client);
                    $clients[] = $client;
                    echo "Nuevo cliente conectado\n";
                }
            } else {
                $buffer = '';
                $bytes = @socket_recv($socket, $buffer, 2048, 0);

                if ($bytes === false || $bytes === 0) {
                    // Cliente desconectado
                    echo "Cliente desconectado\n";
                    if ($handshaked->contains($socket)) {
                        $handshaked->detach($socket);
                    }
                    socket_close($socket);
                    $clients = array_filter($clients, fn($s) => $s !== $socket);
                    continue;
                }

                if (!$handshaked->contains($socket)) {
                    if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $buffer, $matches)) {
                        $key = trim($matches[1]);
                        $accept = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

                        $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                                   "Upgrade: websocket\r\n" .
                                   "Connection: Upgrade\r\n" .
                                   "Sec-WebSocket-Accept: $accept\r\n\r\n";

                        socket_write($socket, $upgrade);
                        $handshaked->attach($socket);
                        echo "Handshake completado con cliente\n";
                    }
                } else {
                    $gps = unmask($buffer);
                    if ($gps && json_decode($gps) !== null) {
                        echo "GPS recibido: $gps\n";
                    } else {
                        echo "Datos no JSON recibidos o fragmentados, ignorados.\n";
                    }


                    foreach ($clients as $client) {
                        if ($client !== $server && $client !== $socket && $handshaked->contains($client)) {
                            socket_write($client, mask($gps));
                        }
                    }
                }
            }
        }
    }
}

function unmask($payload) {
    $length = ord($payload[1]) & 127;
    if ($length === 126) {
        $masks = substr($payload, 4, 4);
        $data = substr($payload, 8);
    } elseif ($length === 127) {
        $masks = substr($payload, 10, 4);
        $data = substr($payload, 14);
    } else {
        $masks = substr($payload, 2, 4);
        $data = substr($payload, 6);
    }

    $text = '';
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }
    return $text;
}

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

    return $b1 . $header . $text;
}
