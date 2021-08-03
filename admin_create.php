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
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
    header('Location: admin_new.php');
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

    $sql = 'INSERT INTO admins (name, email, password, role) VALUES (?, ?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['name'];
    $data[] = $_POST['email'];
    $data[] = $_POST['password'];
    $data[] = $_POST['role'];
    $stmt->execute($data);

    $dbh->commit();

  } catch (Exception $e) {
    $dbh->rollBack();

    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  header('Location: admin_index.php');
?>