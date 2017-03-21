<!DOCTYPE html>
<html>
<head>
	<title>Aktiva-Archive</title>
	<meta charset="UTF-8" />
	<meta name="keywords" content="Global Studios, グローバルスタジオ" />
 	<meta name="description" content="" />
 	<meta name="author" content="Global Studios" />
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<link rel="shortcut icon" href="img/favicon.ico" />
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel = "stylesheet" type = "text/css" href = "css/style.css">
</head>

<body>
<?php
  session_start();

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

  $userid = utf8_encode($_SESSION["USERID"]);
  $historyid = utf8_encode($_GET["id"]);

echo '
<header>
		<div class="siteheader shadow1">
			<a href=""><img src="img/logo.png"></a>
			<nav>
				<ul id="menu">
					<li><a href="">USER ID: '. $userid .'</a></li>
					<li><a href="">アーカイブリスト</a></li>
					<li><a href="">Aktiva-Archiveについて</a></li>
					<li><a href="logout.php">ログアウト</a></li>
				</ul>
				<button id="menubutton"><span></span></button>
			</nav>
		</div>
</header>
<section id="header-back"></section>';



	$query = "SELECT * FROM \"history\" WHERE history_id LIKE '" . $historyid ."' AND user_id LIKE '". $userid ."';";
  $result = sqlsrv_query($conn, $query);
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }
  $row = sqlsrv_fetch_array($result);

  $query = "SELECT * FROM \"music\" WHERE music_id LIKE '" . $row["music_id"] ."';";
  $result = sqlsrv_query($conn, $query);
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }
  $row = sqlsrv_fetch_array($result);

  echo '<section class="each_menu"><div class="wrap">';
  echo '<a href="./music.php?id='. $row["music_id"] .'"></a>';
  echo '<h1>&lt; '.$row["artist"].' - ' . $row["song"] .'</h1>';
  echo '</div></section>';


  $query = "SELECT * FROM \"history\" WHERE history_id LIKE '" . $historyid ."' AND user_id LIKE '". $userid ."';";
  $result = sqlsrv_query($conn, $query);
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }
  $row = sqlsrv_fetch_array($result);

  echo '<section class="each_menu"><div class="wrap">';
//  echo '<div class="youtube_video">
//        <iframe src="https://www.youtube.com/embed/' . $row["video_url"] .'" frameborder="0" allowfullscreen></iframe>
//        </div>';
  echo '</div></section>';

  echo '<section class="each_menu"><div class="wrap">';
  echo '<h1>Total Score: '.$row["score"].'</h1>';
  echo '<h1>'.$row["comment"].'</h1>';
  echo '</div></section>';

  echo '<section class="each_menu"><div class="wrap">';
  $pieces = explode("/", $row["part"]);
  for ($i=0; $i < sizeof($pieces) - 1 ; $i+=2) {
    echo "<h1>".$pieces[$i].": ".$pieces[$i+1]."</h1>";
  }
  echo '</div></section>';


  echo '<section class="each_menu"><div class="wrap">';
  $pieces = explode("/", $row["body"]);
  $body = array("右腕", "左腕", "右足", "左足");
  for ($i=0; $i < sizeof($pieces); $i++) {
    echo "<h1>".$body[$i].": ".$pieces[$i]."</h1>";
  }
  echo '</div></section>';

  echo '<section class="each_menu"><div class="wrap">';
  echo '<h1>タイミング: '.$row["timing"].'</h1>';
  echo '<h1>表情: '.$row["expression"].' / 8 </h1>';
  echo '</div></section>';
?>
	<!-- Script -->
	<script src="js/lib/jquery.min.js"></script>
	<script src="js/drawerNav.js"></script>
</body>
</html>
