<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }
  if(!isset($_GET['id'])) {
    header('Location: reservation_index.php');
    exit();
  }

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM orders WHERE id = '.$_GET['id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    switch($order['plan']) {
      case '席だけ':
        $sql = 'SELECT * FROM product_orders WHERE category = 0 AND order_id = '.$_GET['id'];
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $seat_order = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;

      case 'BBQ':
        $sql = 'SELECT * FROM product_orders WHERE category = 1 AND order_id = '.$_GET['id'];
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $bbqset_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM product_orders WHERE category = 2 AND order_id = '.$_GET['id'];
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $drink_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM option_orders WHERE order_id = '.$_GET['id'];
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $option_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
  
      default:
        echo 'エラー<br/>';
        echo '<a href="reservation_index.php">予約一覧へ戻る</a>';
        break;
    }

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
  <link rel="stylesheet" type="text/css" href="css/reservation.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <div class="show-main">
    <p class="show-title">予約内容／フェーズ：
    <?php
      switch($order['phase']) {
        case '0':
          echo '<span class="phasecolor-blue">予約中</span>';
          break;

        case '1':
          echo '<span class="phasecolor-green">来店済</span>';
          break;

        case '2':
          echo '<span class="phasecolor-red">キャンセル</span>';
          break;
    
        default:
          echo '';
          break;
      }
    ?>
    </p>
    <div class="show-order">
      <p class="show-order-item">⚫︎予約ID：<span class="show-order-item-span"><?=$order['id'] ?>番</span></p>
      <p class="show-order-item">⚫︎人数(大人)：<span class="show-order-item-span"><?=$order['adult'] ?>名様</span></p>
      <p class="show-order-item">⚫︎人数(小学生)：<span class="show-order-item-span"><?=$order['schoolchildren'] ?>名様</span></p>
      <p class="show-order-item">⚫︎人数(小学生未満)：<span class="show-order-item-span"><?=$order['preschooler'] ?>名様</span></p>
      <p class="show-order-item">⚫︎利用日：<span class="show-order-item-span"><?=$order['date'] ?></span></p>
      <p class="show-order-item">⚫︎時間：<span class="show-order-item-span"><?=$order['time'] ?></span></p>
      <p class="show-order-item">⚫︎プラン内容：<span class="show-order-item-span"><?=$order['plan'] ?></span></p>
      <p class="show-order-item">⚫︎代表者氏名：<span class="show-order-item-span"><?="{$order['name']} ({$order['name_kana']})" ?>様</span></p>
      <p class="show-order-item">⚫︎代表者住所：<span class="show-order-item-span"><?="{$order['address_prefectures']} {$order['address_city']} {$order['address_subsequent']}" ?></span></p>
      <p class="show-order-item">⚫︎代表者電話番号：<span class="show-order-item-span"><?=$order['phone'] ?></span></p>
      <p class="show-order-item">⚫︎代表者メールアドレス：<span class="show-order-item-span"><?=$order['email'] ?></span></p>
      <?php
        if (!empty($order['group_name'])) {
          echo '<p class="show-order-item">⚫︎法人・団体名：<span class="show-order-item-span">'.$order['group_name'].'</span></p>';
        } else {
          echo '<p class="show-order-item">⚫︎法人・団体名：<span class="show-order-item-span">なし</span></p>';
        }
      ?>
    </div>
    <p class="show-title">予約商品一覧</p>
    <?php
      switch($order['plan']) {
          case '席だけ':
            echo '<p class="show-order-item">■席だけ</p>
                  <table class="show-productorder-box">
                    <tr>
                      <th>商品名</th>
                      <th>単価</th>
                      <th>注文個数</th>
                    </tr>';
                    foreach($seat_order as $seat_order) {
                      echo '<tr>
                              <td>'.$seat_order['name'].'</td>
                              <td>'.$seat_order['price'].'</td>
                              <td>'.$seat_order['quantity'].'</td>
                            </tr>';
                    }
            echo '</table>';
            break;

          case 'BBQ':
            echo '<p class="show-order-item">■BBQセット</p>
                  <table class="show-productorder-box">
                    <tr>
                      <th>商品名</th>
                      <th>単価</th>
                      <th>注文個数</th>
                    </tr>';
                    foreach($bbqset_orders as $bbqset_order) {
                      echo '<tr>
                              <td>'.$bbqset_order['name'].'</td>
                              <td>'.$bbqset_order['price'].'</td>
                              <td>'.$bbqset_order['quantity'].'</td>
                            </tr>';
                    }
            echo '</table>';
            if (!empty($drink_orders)) {
              echo '<p class="show-order-item">■ドリンク</p>
                    <table class="show-productorder-box">
                    <tr>
                      <th>商品名</th>
                      <th>単価</th>
                      <th>注文個数</th>
                    </tr>';
                    foreach($drink_orders as $drink_order) {
                      echo '<tr>
                              <td>'.$drink_order['name'].'</td>
                              <td>'.$drink_order['price'].'</td>
                              <td>'.$drink_order['quantity'].'</td>
                            </tr>';
                    }
              echo '</table>';
            }
            if (!empty($option_orders)) {
              echo '<p class="show-order-item">■オプション</p>
                    <table class="show-productorder-box">
                    <tr>
                      <th>商品名</th>
                      <th>単価</th>
                      <th>注文個数</th>
                    </tr>';
                    foreach($option_orders as $option_order) {
                      echo '<tr>
                              <td>'.$option_order['name'].'</td>
                              <td>'.$option_order['price'].'</td>
                              <td>'.$option_order['quantity'].'</td>
                            </tr>';
                    }
              echo '</table>';
            }
            break;

          default:
            false;
            break;
      }
    ?>
    <p class="show-order-item">総合計金額：<span class="show-order-item-span">¥<?=$order['total_price'] ?></span></p>
  </div>
</body>
</html>