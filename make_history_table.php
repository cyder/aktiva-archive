<?php 
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

  $query = "CREATE TABLE `history_" . $userid ."` (
  `id` int(11) NOT NULL,
  `date` text NOT NULL,
  `artist` text NOT NULL,
  `song` text NOT NULL,
  `prev_score` int(11) NOT NULL,
  `prev_score2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
  $result = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }

  $query = "ALTER TABLE `history_" . $userid . "`
  ADD PRIMARY KEY (`id`);";
  $result = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }

  $query = "ALTER TABLE `history_" . $userid . "`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
  $result = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  
  echo "<h1>Made the User History Table: history_" . $userid . "</h1>";
?>