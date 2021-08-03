<?php
  if(empty($_POST['date'])) {
    header('Location: reservation_terms.php');
    exit();
  }

  header("Content-Type: application/json; charset=UTF-8");

  $possible_time = [];
  $date = filter_input(INPUT_POST, "date");

  $time_list = ['10:00-13:00', '12:00-15:00', '14:00-17:00', '16:00-19:00', '18:00-21:00'];

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT time FROM orders WHERE phase = 0 AND date = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $date, PDO::PARAM_STR);
    $stmt->execute();
    $possible_time = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
    echo 'ただいま障害によりエラーが発生しております。';
    exit();
  }

  $dbh = null;

  foreach ($possible_time as $possible_time) {
    foreach ($time_list as $key=>$time) {
      if ($possible_time['time'] === $time) {
        array_splice($time_list, $key, 1);
      }
    }
  }

  echo json_encode($time_list);
  exit;
?>