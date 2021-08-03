<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  } elseif ($_SESSION['login'] !== 0) {
    echo 'このページは権限が付与された人のみ閲覧できます。<br/>';
    echo '<a href="admin.php">adminメニューへ戻る</a>';
    exit();
  }
  if(!isset($_POST['id'])) {
    header('Location: admin_index.php');
    exit();
  }

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbh->beginTransaction();

    $sql = 'DELETE FROM admins WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['id'];
    $stmt->execute($data);

    $dbh->commit();

  } catch (Exception $e) {
    $dbh->rollBack();

    return 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  header('Location: admin_index.php');
?>