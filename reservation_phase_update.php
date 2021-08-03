<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }
  if(!isset($_POST['id']) || !isset($_POST['phase'])) {
    header('Location: reservation_phase_edit.php');
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

    $dbh->beginTransaction();

    $sql = 'UPDATE orders SET phase=? WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['phase'];
    $data[] = $_POST['id'];
    $stmt->execute($data);

    $dbh->commit();

  } catch (Exception $e) {
    $dbh->rollBack();

    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  if ($_POST['phase'] == 2) {
    try {
      $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
      $user = 'root';
      $password = 'root';
      $dbh = new PDO($dsn, $user, $password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      $sql = 'SELECT * FROM orders WHERE id=?';
      $stmt = $dbh->prepare($sql);
      $data = array();
      $data[] = $_POST['id'];
      $stmt->execute($data);
      $order = $stmt->fetch(PDO::FETCH_ASSOC);

      switch($order['plan']) {
        case '席だけ':
          $sql = 'SELECT * FROM product_orders WHERE category = 0 AND order_id = ?';
          $stmt = $dbh->prepare($sql);
          $data = array();
          $data[] = $order['id'];
          $stmt->execute($data);
          $seat_order = $stmt->fetchAll(PDO::FETCH_ASSOC);
          break;
  
        case 'BBQ':
          $sql = 'SELECT * FROM product_orders WHERE category = 1 AND order_id = ?';
          $stmt = $dbh->prepare($sql);
          $data = array();
          $data[] = $order['id'];
          $stmt->execute($data);
          $bbqset_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
          $sql = 'SELECT * FROM product_orders WHERE category = 2 AND order_id = ?';
          $stmt = $dbh->prepare($sql);
          $data = array();
          $data[] = $order['id'];
          $stmt->execute($data);
          $drink_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
          $sql = 'SELECT * FROM option_orders WHERE order_id = ?';
          $stmt = $dbh->prepare($sql);
          $data = array();
          $data[] = $order['id'];
          $stmt->execute($data);
          $option_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
          break;
    
        default:
          break;
      }
  
    } catch (Exception $e) {
      echo 'ただいま障害によりエラーが発生しております。';
      exit();
    }
  
    $dbh = null;

    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
  
    $to = $order['email'];
    $subject = 'キャンセル手続き完了のお知らせ';
    $message = "{$order['name']}　様
  
この度は当店をご利用いただき誠にありがとうございます。
下記ご予約のキャンセルが完了いたしましたのでご連絡いたします。

＜キャンセル内容＞
------------------------------------
■ご予約受付日時：{$order['created_at']}
■ご利用日：{$order['date']}
■ご利用時間：{$order['time']} 入店
■ご利用人数：大人 {$order['adult']}名様　小学生 {$order['schoolchildren']}名様　小学生未満 {$order['preschooler']}名様
■代表者様のお名前：{$order['name']} ({$order['name_kana']})　様
■代表者様のご住所：{$order['address_prefectures']}{$order['address_city']}{$order['address_subsequent']}
■日中にご連絡の繋がるお電話番号：{$order['phone']}
■法人・団体名：{$order['group_name']}
■プラン：{$order['plan']}

《ご注文商品》
";
switch($order['plan']) {
  case '席だけ':
    $message .= "●{$seat_order['name']} ¥{$seat_order['price']} × {$seat_order['quantity']}個";
    $message .= "\r\n";
    break;

  case 'BBQ':
    $message .= "【BBQセット】";
    $message .= "\r\n";
    foreach ($bbqset_orders as $bbqset_order) {
      $message .= "●{$bbqset_order['name']} ¥{$bbqset_order['price']} × {$bbqset_order['quantity']}個";
      $message .= "\r\n";
    }
    if (!empty($drink_orders)) {
      $message .= "【ドリンク】";
      $message .= "\r\n";
      foreach ($drink_orders as $drink_order) {
        $message .= "●{$drink_order['name']} ¥{$drink_order['price']} × {$drink_order['quantity']}個";
        $message .= "\r\n";
      }
    }
    if (!empty($option_orders)) {
      $message .= "【オプション】";
      $message .= "\r\n";
      foreach ($option_orders as $option_order) {
        $message .= "●{$option_order['name']} ¥{$option_order['price']} × {$option_order['quantity']}個";
        $message .= "\r\n";
      }
    }
    break;

  default:
    break;
}
$message .= "
総合計：¥{$order['total_price']}
";
$message .=
"------------------------------------

またのご利用を心よりお待ちしております。
今後とも当店をよろしくお願い申し上げます。

========================
cafe OWL BBQ
TEL：xxx-xxxx-xxxx
========================
";
    $header = 'From:';
  
    mb_send_mail($to, $subject, $message, $header);
  } else {
    false;
  }

  header('Location: reservation_index.php');
?>