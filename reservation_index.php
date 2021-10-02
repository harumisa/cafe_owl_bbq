<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM orders';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <div class="reservationindex-main">
    <p class="admin-name"><span class="admin-name-size"><?=$_SESSION['name'] ?></span>さんログイン中</p>
    <p class="return-link"><a href="admin.php">adminメニューへ戻る</a></p>
    <p class="reservationindex-title">予約一覧</p>
    <table class="index-table">
      <tr>
        <th>予約ID</th>
        <th>受付日時</th>
        <th>人数(大人)</th>
        <th>人数(小学生)</th>
        <th>人数(小学生未満)</th>
        <th>利用日</th>
        <th>時間</th>
        <th>プラン内容</th>
        <th>代表者氏名</th>
        <th>代表者電話番号</th>
        <th>フェーズ</th>
        <th></th>
      </tr>
      <?php foreach($orders as $order): ?>
        <tr>
          <td><?=$order['id'] ?></td>
          <td><?=$order['created_at'] ?></td>
          <td><?=$order['adult'] ?></td>
          <td><?=$order['schoolchildren'] ?></td>
          <td><?=$order['preschooler'] ?></td>
          <td><?=$order['date'] ?></td>
          <td><?=$order['time'] ?></td>
          <td><?=$order['plan'] ?></td>
          <td><?="{$order['name']} ({$order['name_kana']})" ?></td>
          <td><?=$order['phone'] ?></td>
          <?php
            switch($order['phase']) {
              case '0':
                echo '<td>
                        <a href="reservation_phase_edit.php?id='.$order['id'].'">予約中</a>
                      </td>';
                break;

              case '1':
                echo '<td>
                        <a href="reservation_phase_edit.php?id='.$order['id'].'">来店済</a>
                      </td>';
                break;

              case '2':
                echo '<td>
                        キャンセル
                      </td>';
                break;
          
              default:
                echo '<td></td>';
                break;
            }
          ?>
          <td><a href="reservation_show.php?id=<?=$order['id'] ?>">注文詳細</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>