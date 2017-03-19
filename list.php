<!DOCTYPE html>
<html>
<head>
	<title>Y's</title>
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
  //$userid = $_GET["user"];
  $num_fetch = 2; //number of scores fetched from the database

  //Get the max number of music id
  $query = "SELECT MAX(music_id) FROM music";
  $result = $mysqli->query($query);
  $num_music = $result->fetch_assoc()["MAX(music_id)"];

  $text = array();
  for ($i = 1; $i <= $num_music; $i++) {
    $j = 0;
    $text[$i] = $i;
    $query = "SELECT * FROM history WHERE user_id = '". $userid . "'  AND music_id = '". $i ."' order by history_id DESC LIMIT ". $num_fetch;
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) { 
      if ($j < $num_fetch) {
        $text[$i] .= "||" . $row["score"];
        $j++;
      }
    }
    for (; $j < $num_fetch; $j++) { 
      $text[$i] .= "||-1";
    }
  }
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

for ($i=1; $i <= $num_music; $i++) { 
	$pieces = explode("||", $text[$i]);
	if ($pieces[1] != "-1") {
		$query = "SELECT * FROM music WHERE music_id = '" . $pieces[0] ."';";
  		$result = $mysqli->query($query);
  		$row = $result->fetch_assoc();
		echo '<section class="each_menu">
		<div class="wrap">
			<div class="left_menu">
				<a href="music.php?id=' . $pieces[0] . '"></a><img src="img/music/' . $pieces[0] . '.jpg">
			</div>
			<div class="right_menu">
				<h1>' . $row["artist"] . ' - ' . $row["song"] .'</h1>
				<p>1st Latest Score: ' . $pieces[1] . '</p>';
		if ($pieces[2] != "-1") {
			echo '<p>2nd Latest Score: ' . $pieces[2] . '</p>';
		}
		echo '</div></div></section>';
	}	
}
?>
	<!-- Script -->
	<script src="js/lib/jquery.min.js"></script>
	<script src="js/drawerNav.js"></script>
</body>
</html>