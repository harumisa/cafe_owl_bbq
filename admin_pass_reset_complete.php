<?php
  if(!isset($_POST['id']) || empty($_POST['token']) || empty($_POST['password'])) {
    echo '不正なアクセスです。このページを表示できません。<br/>';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }

  try {
    foreach ($_POST as $key=>$val) {
      $_POST[$key] = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
    }

    $_POST['password'] = md5($_POST['password']);

    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbh->beginTransaction();

    $sql = 'UPDATE admins SET password=? WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['password'];
    $data[] = $_POST['id'];
    $stmt->execute($data);

    $sql = 'DELETE FROM tokens WHERE token=?';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $_POST['token'];
    $stmt->execute($data);

    $dbh->commit();

  } catch (Exception $e) {
    $dbh->rollBack();

    return 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cafe OWL BBQ</title>
  <link rel="stylesheet" type="text/css" href="/css/base.css">
</head>
<body>
  <div class="login-main">
    <div class="login-field">
      <p>パスワードの再設定が完了しました。</p>
      <p>下記よりログインして下さい。</p>
      <br/>
      <p><a href="admin_login.php">ログインページへ</a></p>
    </div>
  </div>
</body>
</html>