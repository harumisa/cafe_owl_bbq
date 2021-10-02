<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }
  if(!isset($_GET['id'])) {
    header('Location: admin_index.php');
    exit();
  }

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM admins WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_SESSION['id'];
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
  <p class="return-link"><a href="admin.php">adminメニューへ戻る</a></p>
  <div class="admin-main">
    <div class="admin-field">
      <p class="admin-field-title">My情報の編集</p>
      <form method="post" action="admin_update.php" class="admin-form">
        <input type="hidden" name="id" value="<?=$admin['id'] ?>">
        <p>新しい氏名を入力してください。<span class="admin-inputcolor">(※必須)</span></p>
        <input type="text" name="name" class="fieldwidth" value="<?=$admin['name'] ?>" placeholder="例）山田太郎"><br/>
        <p>新しいメールアドレスを入力してください。<span class="admin-inputcolor">(※必須)</span></p>
        <input type="text" name="email" class="fieldwidth" value="<?=$admin['email'] ?>"><br/>
        <p>新しいパスワードを入力してください。</p>
        <p>(※パスワードを変更しない場合は何も入力しないでください。)</p>
        <input type="password" name="password_after" class="fieldwidth"><br/>
        <p>現在のパスワードを入力してください。<span class="admin-inputcolor">(※必須)</span></p>
        <input type="password" name="password" class="fieldwidth"><br/>
        <br/>
        <input type="submit" value="変更する">
      </form>
    </div>
  </div>
</body>
</html>