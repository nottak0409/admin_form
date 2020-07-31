<?php
  if($_SERVER['SERVER_NAME']=='localhost'){
  try {
     $db = new PDO('mysql:dbname=admin_db;host=localhost;charset=utf8', 'root', 'root');}
     catch(PDOException $e) {
     echo 'DB接続エラー: ' . $e->getMessage();
     }
   } else {
     try {
     function dbConnect(){
     $dsn = "mysql:dbname=xs334932_admin;host=mysql10058.xserver.jp;charset=utf8";
     $user = "xs334932_nottak";
     $password = "swan374red697";
     $options = array(
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY =>true,
   );
   $dbh = new PDO($dsn,$user,$password,$options);
   return $dbh;
 }} catch(PDOexception $e) {
   echo 'DB接続エラー: ' . $e->getMessage();
  }
}
//}

?>
