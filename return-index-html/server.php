<?php
// 1. 80ポートで待ち受け開始
$server = stream_socket_server("tcp://0.0.0.0:80");

echo "Server running at http://localhost:80/\n";

while (true) {
    // 2. ブラウザからの接続を待機（accept）
    $client = stream_socket_accept($server);

    // 3. ブラウザからのリクエストを受信（今回は中身を解析せず読み飛ばすだけ）
    $request = fread($client, 1024);
    echo "--- Request Received ---\n" . $request;

    // 4. index.html の中身を読み込む
    $file = 'index.html';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $status = "200 OK";
    } else {
        $content = "File Not Found";
        $status = "404 Not Found";
    }

    // 5. HTTPレスポンスを組み立てて送信
    fwrite($client, "HTTP/1.1 $status\r\n");
    fwrite($client, "Content-Type: text/html; charset=UTF-8\r\n");
    fwrite($client, "Content-Length: " . strlen($content) . "\r\n");
    fwrite($client, "Connection: close\r\n");
    fwrite($client, "\r\n"); // ヘッダーとボディの区切り
    fwrite($client, $content);

    // 6. 接続を閉じる
    fclose($client);
}