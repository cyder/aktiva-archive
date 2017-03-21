<?php

  $connectionInfo = [
    "UID"                    => "atsushi@aktiva-archive",
    "pwd"                    => "Shigoto4510",
    "Database"               => "aktiva-archive-db",
    "CharacterSet"           => "UTF-8"
  ];
  $serverName = "tcp:aktiva-archive.database.windows.net,1433";

  $conn = sqlsrv_connect($serverName, $connectionInfo);
  if($conn === false) {
    print('<p>データベースへの接続に失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }

  $userid = utf8_encode($_GET["user"]);
  $num_fetch = 2; //number of scores fetched from the database

  //Get the max number of music id
  $query = "SELECT MAX(music_id) FROM \"music\"";
  $result = sqlsrv_query($conn, $query)
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }
  $num_music = sqlsrv_fetch_array($result)["0"];

  for ($i = 1; $i <= $num_music; $i++) {
    $j = 0;
    $text = $i;

    $query = "SELECT * FROM \"history\" WHERE user_id LIKE '". $userid . "'  AND music_id LIKE '". $i ."' order by history_id DESC OFFSET 0 ROWS FETCH NEXT ". $num_fetch." ROWS ONLY";
    $result = sqlsrv_query($conn, $query)
    if($result === false) {
      print('<p>クエリーが失敗しました。</p>');
      foreach(sqlsrv_errors() as $error)
        print("Error:  " . $error["message"]);
      sqlsrv_close($conn);
      exit();
    }

    while ($row = sqlsrv_fetch_array($result)) {
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
