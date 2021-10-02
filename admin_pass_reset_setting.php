<?php
  session_start();
  session_regenerate_id(true);
  if(isset($_SESSION['token']) === false && empty($_GET['token'])) {
    echo '不正なアクセスです。このページを表示できません。';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }
  $_SESSION = array();
  if (isset($_COOKIE[session_name()]) === true) {
    setcookie(session_name(),'',time()-42000,'/');
  }
  session_destroy();

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT admins.id FROM admins INNER JOIN tokens ON admins.id = tokens.admin_id WHERE tokens.token=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_GET['token'];
    $stmt->execute($data);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cafe OWL BBQ</title>
  <link rel="stylesheet" type="text/css" href="css/base.css">
</head>
<body>
  <div class="admin-main">
    <div class="admin-field">
      <p class="admin-field-title">パスワードの再設定</p>
      <form method="post" action="admin_pass_reset_complete.php" class="admin-form">
        <input type="hidden" name="id" value="<?=$admin['id'] ?>">
        <input type="hidden" name="token" value="<?=$_GET['token'] ?>">
        <p>新しいパスワードを入力してください。<span class="admin-inputcolor">(※必須)</span></p>
        <input type="password" name="password" class="fieldwidth"><br/>
        <br/>
        <input type="submit" value="変更する">
      </form>
    </div>
  </div>
</body>
</html>