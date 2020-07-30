<?php
  if($_SERVER['SERVER_NAME']=='localhost'){
   try {
     $db = new PDO('mysql:dbname=admin_db;host=localhost;charset=utf8', 'root', 'root');}
     catch(PDOException $e) {
     echo 'DB接続エラー: ' . $e->getMessage();
     }
   } else {
   try{
     function dbConnect(){
     $db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
     $db['dbname'] = ltrim($db['path'], '/');
     $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
     $user = $db['user'];
     $password = $db['pass'];
     $options = array(
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY =>true,
   );
   $dbh = new PDO($dsn,$user,$password,$options);
   return $dbh;
  }
} catch(PDOException $e) {
 echo 'DB接続エラー: ' . $e->getMessage();
}
}

?>
