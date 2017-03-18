<?php
session_start();

if (isset($_SESSION["USERID"])) {
  $errorMessage = "ログアウトしました。";
}
else {
  $errorMessage = "セッションがタイムアウトしました。";
}
// セッション変数のクリア
$_SESSION = array();
// クッキーの破棄は不要
//if (ini_get("session.use_cookies")) {
//    $params = session_get_cookie_params();
//    setcookie(session_name(), '', time() - 42000,
//        $params["path"], $params["domain"],
//        $params["secure"], $params["httponly"]
//    );
//}
// セッションクリア
@session_destroy();
?>

<!doctype html>
<html>
  <head>
    <title>Mai-Archive</title>
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
  echo '
<header>
    <div class="siteheader shadow1">
      <a href=""><img src="img/logo.png"></a>
      <nav>
        <ul id="menu">
          <li><a href="">Mai-Archiveについて</a></li>
          <li><a href="login.php">ログインしていません。</a></li>
        </ul>
        <button id="menubutton"><span></span></button>
      </nav>
    </div>
</header>
<section id="header-back"></section>';

  ?>
  <section class="each_menu">
    <div class="wrap">
      <a href="login.php"></a><h1>&lt; ログイン画面に戻る。</h1>
    </div>
  </section>

  <section class="each_menu">
  <div class="wrap">
    <div><?php echo $errorMessage; ?></div>
  </div>
  </section>
  </body>
  <!-- Script -->
  <script src="js/lib/jquery.min.js"></script>
  <script src="js/drawerNav.js"></script>
</html>