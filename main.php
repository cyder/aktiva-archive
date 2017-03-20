<?php
session_start();

// ログイン状態のチェック
if (!isset($_SESSION["USERID"])) {
  header("Location: logout.php");
  exit;
}
?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Mai-Archive</title>
  </head>
  <body>
  <h1>Mai-Archive</h1>
  <!-- ユーザIDにHTMLタグが含まれても良いようにエスケープする -->
  <h2>ようこそ<?=htmlspecialchars($_SESSION["USERID"], ENT_QUOTES); ?>さん</h2><hr />

<?php 
/*
  $db['host'] = "localhost";  // DBサーバのurl
  $db['user'] = "daichi";
  $db['pass'] = "yoshitake";
  $db['dbname'] = "mai-archive";
*/


  $db['host'] = "host";  // DB sever's url
  $db['user'] = "user";
  $db['pass'] = "pass";
  $db['dbname'] = "dbname";



  $mysqli = new mysqli($db['host'], $db['user'], $db['pass']);
  if ($mysqli->connect_errno) {
      print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
      exit();
    }

  $mysqli->select_db($db['dbname']);

  $userid = $mysqli->real_escape_string($_SESSION["USERID"]);

   // クエリの実行
    $query = "SELECT * FROM video WHERE user_name = '" . $_SESSION["USERID"] . "' order by id";
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while ($row = $result->fetch_assoc()) { 
      echo '<p>' . $row['artist'];
      echo ' - ' . $row['song'] . '</p>';
//      echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $row['url'] .'" frameborder="0" allowfullscreen></iframe>';
      echo '</p><hr />';
    }
  
?>
  <a href="logout.php">ログアウト</a>
  </body>
</html>