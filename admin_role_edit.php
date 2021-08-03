<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br/>';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  } elseif ($_SESSION['login'] !== 0) {
    echo 'このページは権限が付与された人のみ閲覧できます。<br/>';
    echo '<a href="admin.php">adminメニューへ戻る</a>';
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
    $data[] = $_GET['id'];
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
  <link rel="stylesheet" type="text/css" href="/css/base.css">
</head>
<body>
  <p class="return-link"><a href="admin_index.php">管理者一覧へ戻る</a></p>
  <div class="admin-main">
    <div class="admin-field">
      <p class="admin-field-title"><?=$admin['name'] ?>さんの役名を変更</p>
      <form method="post" action="admin_role_update.php" class="admin-form">
        <input type="hidden" name="id" value="<?=$admin['id'] ?>">
        <p>役名</p>
        <select name="role" class="fieldwidth">
          <option value="0">責任者</option>
          <option value="1">従業員</option>
        </select><br/>
        <br/>
        <input type="submit" value="変更する">
      </form>
    </div>
  </div>
</body>
</html>