<?php
// 1. 80ポートで待ち受ける（ソケットの作成）
$server = stream_socket_server("tcp://0.0.0.0:80");

echo "Server started at http://localhost:80\n";

while (true) {
    // 2. ブラウザからの接続を待機（accept）
    $client = stream_socket_accept($server);

    if ($client) {
        // 3. リクエスト（生テキスト）を読み取る
        $request = fread($client, 1024);
        echo "--- Request Received ---\n" . $request;

        // 4. HTTPレスポンスを組み立てる
        $response = "HTTP/1.1 200 OK\r\n";
        $response .= "Content-Type: text/html; charset=UTF-8\r\n";
        $response .= "\r\n"; // ヘッダーとボディの区切り
        $response .= "<h1>Hello from PHP Server!</h1>";
        $response .= "<p>Current Time: " . date('Y-m-d H:i:s') . "</p>";

        // 5. ブラウザに送信して接続を閉じる
        fwrite($client, $response);
        fclose($client);
    }
}