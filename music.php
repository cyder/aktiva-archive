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
  $mysqli->query("SET NAMES utf8");
  $mysqli->select_db($db['dbname']);

  $userid = $_SESSION["USERID"];
  $musicid = $_GET["id"];

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

$query = "SELECT * FROM music WHERE music_id = '" . $musicid ."';";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
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


	$query = "SELECT * FROM history WHERE music_id = '" . $musicid ."' AND user_id = '". $userid ."' order by history_id desc;";
  $result = $mysqli->query($query);
  while ($row = $result->fetch_assoc()) { 
    echo '<section class="each_menu">
    <div class="wrap">
      <div class="left_menu_music">
        <a href="./video.php?id='. $row["history_id"] .'"></a><img src="http://i.ytimg.com/vi/'. $video .'/maxresdefault.jpg">
      </div>
      <div class="right_menu_music">
        <p>'.$row["date"].'</p>
        <h1>Score: ' . $row["score"] .'</h1>';
  echo '</div></div></section>';
  }
?>
	<!-- Script -->
	<script src="js/lib/jquery.min.js"></script>
	<script src="js/drawerNav.js"></script>
</body>
</html>