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
  $musicid = utf8_encode($_GET["id"]);

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

$query = "SELECT * FROM \"music\" WHERE music_id LIKE '" . $musicid ."';";
$result = sqlsrv_query($conn, $query);
if($result === false) {
  print('<p>クエリーが失敗しました。</p>');
  foreach(sqlsrv_errors() as $error)
    print("Error:  " . $error["message"]);
  sqlsrv_close($conn);
  exit();
}
$row = sqlsrv_fetch_array($result);
$video = $row["video_url"];

echo '
<section class="each_menu">
  <div class="wrap">
    <div class="music_main">
      <h1>' . $row["artist"] . ' - ' . $row["song"] .'</h1>
      <img src="img/music/' . $musicid . '.jpg">
    </div>
      <div class="youtube_video">
      <iframe src="https://www.youtube.com/embed/' . $video .'" frameborder="0" allowfullscreen></iframe>
      </div>
  </div>
</section>';


  $query = "SELECT * FROM \"history\" WHERE music_id LIKE '" . $musicid ."' AND user_id LIKE '". $userid ."' order by history_id desc;";
  $result = sqlsrv_query($conn, $query);
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }

  while($row = sqlsrv_fetch_array($result)) {
    echo '<section class="each_menu">
      <div class="wrap">
        <a href="./video.php?id='. $row["history_id"] .'"></a>
        <div class="left_menu_music">
          <div class="chart">
            <canvas class="myChart" width="200" height="200"></canvas>
            <div class="count">
              <em>' . $row["score"] .'</em>
            </div>
          </div>
        </div>
        <div class="right_menu_music">
          <p>'.date_format($row["date"], "Y-m-d<br>H:i:s").'</p>
        </div>
      </div>
    </section>';
  }
?>
	<!-- Script -->
	<script src="js/lib/jquery.min.js"></script>
	<script src="js/drawerNav.js"></script>
</body>
</html>
