<?php
  session_start();
  session_regenerate_id(true);
  $_SESSION = array();
  if (isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(),'',time()-42000,'/');
  }
  session_destroy();

  if(!isset($_POST['adult']) || !isset($_POST['schoolchildren']) || !isset($_POST['preschooler']) || empty($_POST['date']) || empty($_POST['time']) || empty($_POST['plan']) ||
     empty($_POST['product_orders']) || empty($_POST['total_price']) || empty($_POST['name']) || empty($_POST['name_kana']) || empty($_POST['address_prefectures']) ||
     empty($_POST['address_city']) || empty($_POST['address_subsequent']) || empty($_POST['phone']) || empty($_POST['email']) || !isset($_POST['group_name']) || !isset($_POST['phase'])) {
    echo '不正なアクセスです。このページを表示できません。<br/>';
    echo '<a href="reservation_terms.php">戻る</a>';
    exit();
  }

  try {
    $_POST['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
    $_POST['name_kana'] = htmlspecialchars($_POST['name_kana'], ENT_QUOTES, "UTF-8");
    $_POST['address_prefectures'] = htmlspecialchars($_POST['address_prefectures'], ENT_QUOTES, "UTF-8");
    $_POST['address_city'] = htmlspecialchars($_POST['address_city'], ENT_QUOTES, "UTF-8");
    $_POST['address_subsequent'] = htmlspecialchars($_POST['address_subsequent'], ENT_QUOTES, "UTF-8");
    $_POST['phone'] = htmlspecialchars($_POST['phone'], ENT_QUOTES, "UTF-8");
    $_POST['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
    $_POST['group_name'] = htmlspecialchars($_POST['group_name'], ENT_QUOTES, "UTF-8");

    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbh->beginTransaction();

    $sql = 'INSERT INTO orders (adult, schoolchildren, preschooler, date, time, plan, total_price, name, name_kana, address_prefectures, address_city, address_subsequent, phone, email, group_name, phase) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $_POST['adult'];
    $data[] = $_POST['schoolchildren'];
    $data[] = $_POST['preschooler'];
    $data[] = $_POST['date'];
    $data[] = $_POST['time'];
    $data[] = $_POST['plan'];
    $data[] = $_POST['total_price'];
    $data[] = $_POST['name'];
    $data[] = $_POST['name_kana'];
    $data[] = $_POST['address_prefectures'];
    $data[] = $_POST['address_city'];
    $data[] = $_POST['address_subsequent'];
    $data[] = $_POST['phone'];
    $data[] = $_POST['email'];
    $data[] = $_POST['group_name'];
    $data[] = $_POST['phase'];
    $stmt->execute($data);

    $sql = 'SELECT LAST_INSERT_ID()';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $order_lastid = $rec['LAST_INSERT_ID()'];

    foreach($_POST['product_orders'] as $key=>$product_order) {
      $sql = 'INSERT INTO product_orders (order_id, name, price, category, quantity) VALUES (?, ?, ?, ?, ?)';
      $stmt = $dbh->prepare($sql);
      $data = array();
      $data[] = $order_lastid;
      $data[] = $product_order['name'];
      $data[] = $product_order['price'];
      $data[] = $product_order['category'];
      $data[] = $product_order['quantity'];
      $stmt->execute($data);
    }

    if (!empty($_POST['option_orders'])) {
      foreach($_POST['option_orders'] as $key=>$option_order) {
        $sql = 'INSERT INTO option_orders (order_id, name, price, quantity) VALUES (?, ?, ?, ?)';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $order_lastid;
        $data[] = $option_order['name'];
        $data[] = $option_order['price'];
        $data[] = $option_order['quantity'];
        $stmt->execute($data);
      }
    } else {
      false;
    }

    $sql = 'SELECT * FROM orders WHERE name = ? AND email = ?';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $_POST['name'];
    $data[] = $_POST['email'];
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

    $dbh->commit();

  } catch (Exception $e) {
    $dbh->rollBack();

    echo 'ただいま障害によりエラーが発生しております。';
    echo '大変お手数ですが最初からやり直して下さい。';
    echo '<a href="index.php">トップページへ戻る</a>';
    exit();
  }

  $dbh = null;

  mb_language('Japanese');
  mb_internal_encoding('UTF-8');

  $to = $_POST['email'];
  $subject = 'ご予約完了のお知らせ';
  $message = "{$order['name']}　様

この度は当店をご利用いただき誠にありがとうございます。
ご予約が完了いたしましたのでご連絡いたします。

＜ご予約内容＞
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

お客様のご来店を心よりお待ちしております。
当日は気をつけて会場までお越し下さいませ！

※当日はご予約時間の10分前までに受付をお願いいたします。
※ご予約時間から30分を過ぎましてもご来店いただけない、またはご連絡が繋がらない場合は当日キャンセル扱いとさせていただき、全額を頂戴致します。
※キャンセルの場合は下記期日までに直接xxx-xxxx-xxxxまでご連絡ください。
・〜9名 2日前の17時
・10名〜19名  3日前の17時
・20名〜29名  5日前の17時
・30名〜49名 10日前の17時
・50名〜99名 2週間前の17時
・100名以上  1ヶ月前
※上記期間内でのキャンセルは無料とさせて頂きます。
※キャンセル期日が定休日とかぶる場合は繰り上げになります。
※上記以外の場合、ご注文頂いた内容の50%を、当日キャンセルの場合、全額を頂戴致します。

========================
cafe OWL BBQ
TEL：xxx-xxxx-xxxx
========================
";
  $header = 'From:';

  mb_send_mail($to, $subject, $message, $header);

  header('Location: reservation_create_complete.php');
?>
