<?php
ini_set( 'display_errors', 1 ); //display error messages
//require 'password.php';
// セッション開始
session_start();

// エラーメッセージの初期化
$errorMessage = "";
$db_hashed_pwd = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
  // １．ユーザIDの入力チェック
  if (empty($_POST["userid"])) {
    $errorMessage = "ユーザIDが未入力です。";
  } else if (empty($_POST["password"])) {
    $errorMessage = "パスワードが未入力です。";
  }

  // ２．ユーザIDとパスワードが入力されていたら認証する
  if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
    // mysqlへの接続
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

    // 入力値のサニタイズ
    $userid = utf8_encode($_POST["userid"]);

    // クエリの実行
    $query = "SELECT * FROM \"user\" WHERE user_id LIKE '" . $userid . "'";
    $result = sqlsrv_query($conn, $query);
    if($result === false) {
      print('<p>クエリーが失敗しました。</p>');
      foreach(sqlsrv_errors() as $error)
        print("Error:  " . $error["message"]);
      sqlsrv_close($conn);
      exit();
    }

    while ($row = sqlsrv_fetch_array($result)) {
      // パスワード(暗号化済み）の取り出し
      $db_hashed_pwd = password_hash($row['password'], PASSWORD_DEFAULT);
    }

    // データベースの切断
    sqlsrv_close($conn);

    // ３．画面から入力されたパスワードとデータベースから取得したパスワードのハッシュを比較します。
    //if ($_POST["password"] == $pw) {
    if (password_verify($_POST["password"], $db_hashed_pwd)) {
      // ４．認証成功なら、セッションIDを新規に発行する
      session_regenerate_id(true);
      $_SESSION["USERID"] = $_POST["userid"];
      header("Location: list.php");
      exit;
    }
    else {
      // 認証失敗
      $errorMessage = "ユーザIDあるいはセキュリティコードに誤りがあります。";
    }
  } else {
    // 未入力なら何もしない
  }
}

?>
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
  echo '
<header>
    <div class="siteheader shadow1">
      <a href=""><img src="img/logo.png"></a>
      <nav>
        <ul id="menu">
          <li><a href="">Aktiva-Archiveについて</a></li>
          <li><a href="login.php">ログインしていません。</a></li>
        </ul>
        <button id="menubutton"><span></span></button>
      </nav>
    </div>
</header>
<section id="header-back"></section>';
  ?>
  <!-- $_SERVER['PHP_SELF']はXSSの危険性があるので、actionは空にしておく -->
  <!--<form id="loginForm" name="loginForm" action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">-->
  <section class="each_menu"><div class="wrap">
  <h2>Aktiva-Card表面のユーザIDと裏面のセキュリティコードを入力してください。</h2><br />
  <form id="loginForm" name="loginForm" action="" method="POST">
  <div><?php echo $errorMessage ?></div>
  <label for="userid">ユーザID</label><br /><input type="text" id="userid" name="userid" value="">
  <br>
  <label for="password">セキュリティコード</label><br /><input type="password" id="password" name="password" value="">
  <br /><br />
  <input type="submit" id="login" name="login" value="ログイン" class="login_button">
  </form>
</div>
  </section>
  </body>
  <!-- Script -->
  <script src="js/lib/jquery.min.js"></script>
  <script src="js/drawerNav.js"></script>
</html>
