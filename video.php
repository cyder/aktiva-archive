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
  $historyid = $_GET["id"];

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



	$query = "SELECT * FROM history WHERE history_id = '" . $historyid ."' AND user_id = '". $userid ."';";
  $result = $mysqli->query($query);
  $row = $result->fetch_assoc();

  $query = "SELECT * FROM music WHERE music_id = '" . $row["music_id"] ."';";
  $result = $mysqli->query($query);
  $row = $result->fetch_assoc();

  echo '<section class="each_menu"><div class="wrap">';
  echo '<a href="./music.php?id='. $row["music_id"] .'"></a>';
  echo '<h1>&lt; '.$row["artist"].' - ' . $row["song"] .'</h1>';
  echo '</div></section>';


  $query = "SELECT * FROM history WHERE history_id = '" . $historyid ."' AND user_id = '". $userid ."';";
  $result = $mysqli->query($query);
  $row = $result->fetch_assoc();

  echo '<section class="each_menu"><div class="wrap">';
  echo '<div class="youtube_video">
        <iframe src="https://www.youtube.com/embed/' . $row["video_url"] .'" frameborder="0" allowfullscreen></iframe>
        </div>';
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