<?php
  if(empty($_POST['email'])) {
    header('Location: admin_pass_reset.php');
    exit();
  }

  try {
    foreach ($_POST as $key=>$val) {
      $_POST[$key] = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
    }
    
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM admins WHERE email=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['email'];
    $stmt->execute($data);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  if (!empty($admin)) {
    $token = sha1(uniqid(rand(), true));
    $current_datetime = date('Y-m-d H:i:s');

    try {
      $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
      $user = 'root';
      $password = 'root';
      $dbh = new PDO($dsn, $user, $password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      $dbh->beginTransaction();
  
      $sql = 'INSERT INTO tokens (admin_id, token, datetime) VALUES (?, ?, ?)';
      $data = array();
      $stmt = $dbh->prepare($sql);
      $data[] = $admin['id'];
      $data[] = $token;
      $data[] = $current_datetime;
      $stmt->execute($data);
  
      $dbh->commit();
  
    } catch (Exception $e) {
      $dbh->rollBack();
  
      echo 'ただいま障害によりエラーが発生しております。';
      exit();
    }
  
    $dbh = null;

    mb_language('Japanese');
    mb_internal_encoding('UTF-8');

    $to = $admin['email'];
    $subject = 'パスワード再設定URLのお知らせ';
    $message = '
------------------------------------
▼パスワード再設定URL
http://localhost/admin_pass_reset_url.php?token='.$token.'
(URL有効期限：発行から1時間)
------------------------------------
    ';
    $header = 'From:';

    mb_send_mail($to, $subject, $message, $header);
  } else {
    false;
  }

  header('Location: admin_pass_reset_send.php');
?>