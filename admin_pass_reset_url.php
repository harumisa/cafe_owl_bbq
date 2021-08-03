<?php
  if(empty($_GET['token'])) {
    echo '無効なアクセスです。このページを表示できません。<br/>';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM tokens WHERE token=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_GET['token'];
    $stmt->execute($data);
    $token = $stmt->fetch(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  $reference_time = strtotime('-3600 second');

  if (empty($token)) {
    echo '無効なアクセスです。最初からやり直してください。<br/>';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  } elseif ($reference_time > strtotime($token['datetime'])) {
    try {
      $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
      $user = 'root';
      $password = 'root';
      $dbh = new PDO($dsn, $user, $password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      $dbh->beginTransaction();
  
      $sql = 'DELETE FROM tokens WHERE id=?';
      $stmt = $dbh->prepare($sql);
      $data = array();
      $data[] = $token['id'];
      $stmt->execute($data);
  
      $dbh->commit();
  
    } catch (Exception $e) {
      $dbh->rollBack();
  
      return 'ただいま障害によりエラーが発生しております。';
      exit();
    }
  
    $dbh = null;

    echo '無効なアクセスです。最初からやり直してください。<br/>';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  } else {
    session_start();
    $_SESSION['token'] = true;
    header('Location: admin_pass_reset_setting.php?token='.$token['token'].'');
  }
?>