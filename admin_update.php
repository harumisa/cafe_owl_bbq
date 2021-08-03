<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br/>';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }
  if(!isset($_POST['id']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
    header('Location: admin_edit.php?id='.$_SESSION['id'].'');
    exit();
  }

  try {
    foreach ($_POST as $key=>$val) {
      $_POST[$key] = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
    }

    $_POST['password'] = md5($_POST['password']);
    if ($_POST['password_after'] !== '') {
      $_POST['password_after'] = md5($_POST['password_after']);
    } else {
      false;
    }

    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbh->beginTransaction();

    $sql = 'SELECT * FROM admins WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['id'];
    $stmt->execute($data);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_POST['password'] !== $admin['password']) {
      echo '入力されたパスワードに誤りがあります。<br/>';
      echo '<a href="admin_edit.php?id='.$_POST['id'].'">戻る</a>';
      exit();
    } elseif ($_POST['password_after'] !== '') {
      $admin_password = $_POST['password_after'];
    } else {
      $admin_password = $admin['password'];
    }

    $sql = 'UPDATE admins SET name=?, email=?, password=? WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $_POST['name'];
    $data[] = $_POST['email'];
    $data[] = $admin_password;
    $data[] = $_POST['id'];
    $stmt->execute($data);

    $dbh->commit();

  } catch (Exception $e) {
    $dbh->rollBack();

    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  header('Location: admin.php');
?>