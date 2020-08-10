<?php
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES);
}

function makeLink($value) {
  return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%.!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
}

$url = "https://api.gnavi.co.jp/RestSearchAPI/v3/?keyid=ef9d3d0a43fbc70df95b66d57d520cd8&pref=PREF42";

// cURLセッションを初期化
$ch = curl_init();

// オプションを設定
curl_setopt($ch, CURLOPT_URL, $url); // 取得するURLを指定
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 実行結果を文字列で返す
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // サーバー証明書の検証を行わない

// URLの情報を取得
$response = curl_exec($ch);
// 取得結果を表示
var_dump($response);

$result = json_decode($response, true);
// セッションを終了
curl_close($conn);
?>
