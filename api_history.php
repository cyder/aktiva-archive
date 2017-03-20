<?php 
/*
  $db['host'] = "localhost";  // DB server's url
  $db['user'] = "daichi";
  $db['pass'] = "yoshitake";
  $db['dbname'] = "aktiva-archive";
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

  $userid = $_GET["user"];
  $num_fetch = 2; //number of scores fetched from the database

  //Get the max number of music id
  $query = "SELECT MAX(music_id) FROM music";
  $result = $mysqli->query($query);
  $num_music = $result->fetch_assoc()["MAX(music_id)"];

  for ($i = 1; $i <= $num_music; $i++) {
    $j = 0;
    $text = $i;
    $query = "SELECT * FROM history WHERE user_id = '". $userid . "'  AND music_id = '". $i ."' order by history_id DESC LIMIT ". $num_fetch;
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) { 
      if ($j < $num_fetch) {
        $text .= "||" . $row["score"];
        $j++;
      }
    }
    for (; $j < $num_fetch; $j++) { 
      $text .= "||-1";
    }
    echo $text;
    if ($i != $num_music) {
      echo "\n";
    }
  }
?>