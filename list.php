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
  //$userid = $_GET["user"];
  $num_fetch = 2; //number of scores fetched from the database

  //Get the max number of music id
  $query = "SELECT MAX(music_id) FROM \"music\"";
  $result = sqlsrv_query($conn, $query);
  if($result === false) {
    print('<p>クエリーが失敗しました。</p>');
    foreach(sqlsrv_errors() as $error)
      print("Error:  " . $error["message"]);
    sqlsrv_close($conn);
    exit();
  }
  $num_music = sqlsrv_fetch_array($result)["0"];

  $text = array();
  for ($i = 1; $i <= $num_music; $i++) {
    $j = 0;
    $text[$i] = $i;

    $query = "SELECT * FROM \"history\" WHERE user_id LIKE '". $userid . "' AND music_id LIKE '". $i ."' ORDER BY history_id DESC OFFSET 0 ROWS FETCH NEXT ". $num_fetch." ROWS ONLY";
    $result = sqlsrv_query($conn, $query);
    if($result === false) {
      print('<p>クエリーが失敗しました。</p>');
      foreach(sqlsrv_errors() as $error)
        print("Error:  " . $error["message"]);
      sqlsrv_close($conn);
      exit();
    }

    while ($row = sqlsrv_fetch_array($result)) {
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
		$query = "SELECT * FROM \"music\" WHERE music_id LIKE '" . $pieces[0] ."'";
  	$result = sqlsrv_query($conn, $query);
    if($result === false) {
      print('<p>クエリーが失敗しました。</p>');
      foreach(sqlsrv_errors() as $error)
        print("Error:  " . $error["message"]);
      sqlsrv_close($conn);
      exit();
    }
  	$row = sqlsrv_fetch_array($result);

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
