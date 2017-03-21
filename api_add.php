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

  $query = "insert into history(user_id, music_id, date, score, part, body, timing, expression, comment) values('" . $_GET["user"] . "', '" . $_GET["song"]. "', '" . $_GET["date"] . "', '" . $_GET["total"] . "', '" . $_GET["part"] . "' ,'" .  $_GET["body"] . "', '" . $_GET["timing"] . "', '" . $_GET["expression"] . "', '" . $_GET["comment"] . "');";
  $result = sqlsrv_query($conn, $query);
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }

  echo "1";
?>
