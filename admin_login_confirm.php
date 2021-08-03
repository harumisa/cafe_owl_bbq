<?php
 if(empty($_POST['email']) || empty($_POST['password'])) {
    header('Location: admin_login.php');
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

    $sql = 'SELECT * FROM admins';
    $sql .= ' WHERE email=? AND password=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['email'];
    $data[] = $_POST['password'];
    $stmt->execute($data);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
    $dbh->rollBack();

    return 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  if($admin === false) {
    return header('Location: admin_login.php');
    exit();
  } else {
    switch($admin['role']) {
      case '0':
        session_start();
        $_SESSION['login'] = 0;
        $_SESSION['id'] = $admin['id'];
        $_SESSION['name'] = $admin['name'];
        header('Location: admin.php');
        break;
  
      case '1':
        session_start();
        $_SESSION['login'] = 1;
        $_SESSION['id'] = $admin['id'];
        $_SESSION['name'] = $admin['name'];
        header('Location: admin.php');
        break;
  
      default:
        echo '認証エラーのためログインできませんでした。';
        echo '<a href="admin_login.php">ログインページに戻る</a>';
        echo '<a href="index.php">トップページに戻る</a>';
        break;
    }
  }
?>