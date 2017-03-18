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

  $mysqli->query("SET NAMES utf8");

  $mysqli->select_db($db['dbname']);

  $query = "update history set video_url = '" . $_GET['video_url'] . "' where user_id = '" . $_GET['user'] . "' AND date = '" .$_GET['date'] . "';";

  $result = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  
  echo "1";
?>