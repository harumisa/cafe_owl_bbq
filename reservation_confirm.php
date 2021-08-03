<?php
  try {
    $vali_true = 0;

    $vali_check['people_count'] = 0;
    $vali_check['name_count'] = 0;
    $vali_check['name_kana_count'] = 0;
    $vali_check['address_prefectures_count'] = 0;
    $vali_check['phone_regular_expressions'] = 0;
    $vali_check['email_regular_expressions'] = 0;
    $vali_check['bbqset_count'] = 0;

    session_start();
    $_SESSION['vali_check'] = $vali_check;

    foreach ($_POST as $key=>$val) {
      $_POST[$key] = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
    }

    $name_count = mb_strlen($_POST['name']);
    $name_kana_count = mb_strlen($_POST['name_kana']);
    $address_prefectures_count = mb_strlen($_POST['address_prefectures']);

    $vali_address = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";

    if(!empty($_POST['name']) || !empty($_POST['name_kana']) || !empty($_POST['address_prefectures']) || !empty($_POST['address_city']) ||
       !empty($_POST['address_subsequent']) || !empty($_POST['phone']) || !empty($_POST['email']) || isset($_POST['group_name'])) {
      $vali_true++;
    }
    if(10 < ($_POST['adult']) + ($_POST['schoolchildren']) + ($_POST['preschooler']) || ($_POST['adult']) + ($_POST['schoolchildren']) + ($_POST['preschooler']) < 2) {
      $_SESSION['vali_check']['people_count'] = 1;
    } else {
      $vali_true++;
    }
    if(10 < $name_count) {
      $_SESSION['vali_check']['name_count'] = 1;
    } else {
      $vali_true++;
    }
    if(20 < $name_kana_count) {
      $_SESSION['vali_check']['name_kana_count'] = 1;
    } else {
      $vali_true++;
    }
    if(4 < $address_prefectures_count) {
      $_SESSION['vali_check']['address_prefectures_count'] = 1;
    } else {
      $vali_true++;
    }
    if(preg_match('/^[0-9]+$/', $_POST['phone']) == 0) {
      $_SESSION['vali_check']['phone_regular_expressions'] = 1;
    } else {
      $vali_true++;
    }
    if(preg_match($vali_address, $_POST['email']) == 0) {
      $_SESSION['vali_check']['email_regular_expressions'] = 1;
    } else {
      $vali_true++;
    }

    $bbqset_name_count = 0;
    $bbqset_quantity_count = 0;
    $drink_name_count = 0;
    $drink_quantity_count = 0;
    $option_name_count = 0;
    $option_quantity_count = 0;

    $bbqset_namesandquantities = [];
    $drink_namesandquantities = [];
    $option_namesandquantities = [];

    foreach($_POST as $key=>$val) {
      if (strpos($key, 'bbqset-name') !== false) {
        if ($val !== '-') {
          $bbqset_namesandquantities[$bbqset_name_count]['bbqset_products_id'] = $val-1;
        } else {
          $bbqset_namesandquantities[$bbqset_name_count]['bbqset_products_id'] = '-';
        }
        $bbqset_name_count++;
      } elseif (strpos($key, 'bbqset-quantity') !== false) {
        $bbqset_namesandquantities[$bbqset_quantity_count]['quantity'] = $val;
        $bbqset_quantity_count++;
      } elseif (strpos($key, 'drink-name') !== false) {
        if ($val !== '-') {
          $drink_namesandquantities[$drink_name_count]['drink_products_id'] = $val-1;
        } else {
          $drink_namesandquantities[$drink_name_count]['drink_products_id'] = '-';
        }
        $drink_name_count++;
      } elseif (strpos($key, 'drink-quantity') !== false) {
        $drink_namesandquantities[$drink_quantity_count]['quantity'] = $val;
        $drink_quantity_count++;
      } elseif (strpos($key, 'option-name') !== false) {
        if ($val !== '-') {
          $option_namesandquantities[$option_name_count]['option_products_id'] = $val-1;
        } else {
          $option_namesandquantities[$option_name_count]['option_products_id'] = '-';
        }
        $option_name_count++;
      } elseif (strpos($key, 'option-quantity') !== false) {
        $option_namesandquantities[$option_quantity_count]['quantity'] = $val;
        $option_quantity_count++;
      } else {
        false;
      }
    }

    for ($i=count($bbqset_namesandquantities)-1; $i>=0; $i--) {
      if ($bbqset_namesandquantities[$i]['bbqset_products_id'] === '-' || $bbqset_namesandquantities[$i]['quantity'] == 0) {
        array_splice($bbqset_namesandquantities, $i, 1);
      }
    }
    for ($i=count($drink_namesandquantities)-1; $i>=0; $i--) {
      if ($drink_namesandquantities[$i]['drink_products_id'] === '-' || $drink_namesandquantities[$i]['quantity'] == 0) {
        array_splice($drink_namesandquantities, $i, 1);
      }
    }
    for ($i=count($option_namesandquantities)-1; $i>=0; $i--) {
      if ($option_namesandquantities[$i]['option_products_id'] === '-' || $option_namesandquantities[$i]['quantity'] == 0) {
        array_splice($option_namesandquantities, $i, 1);
      }
    }

    $bbqset_products_ids = array_unique(array_column($bbqset_namesandquantities, 'bbqset_products_id'));
    $bbqset_namesandquantities = array_intersect_key($bbqset_namesandquantities, $bbqset_products_ids);
    $drink_products_ids = array_unique(array_column($drink_namesandquantities, 'drink_products_id'));
    $drink_namesandquantities = array_intersect_key($drink_namesandquantities, $drink_products_ids);
    $option_products_ids = array_unique(array_column($option_namesandquantities, 'option_products_id'));
    $option_namesandquantities = array_intersect_key($option_namesandquantities, $option_products_ids);

    if ($_POST['plan'] === '席だけ') {
      $vali_true++;
    } else {
      if(empty($bbqset_namesandquantities)) {
        $_SESSION['vali_check']['bbqset_count'] = 1;
      } else {
        $vali_true++;
      }
    }

    if ($vali_true !== 8) {
      header('Location: reservation_new.php', true, 307);
      exit();
    } else {
      false;
    }

    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM products';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($option_namesandquantities != null) {
      $sql = 'SELECT * FROM options';
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      false;
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
  <link rel="stylesheet" type="text/css" href="/css/reservation.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <div class="reservation-main">
    <div class="confirm">
      <p class="confirm-title">ご予約内容の確認</p>
      <div class="confirm-box">
        <p class="confirm-box-title">ご利用人数</p>
        <div class="confirm-box-text confirm-box-block">
          <div class="adult-box">
            <p class="confirm-item-name">大人</p>
            <p class="confirm-item-text"><?=$_POST['adult'] ?>名様</p>
          </div>
          <div>
            <p class="confirm-item-name">お子様</p>
            <div class="children-box-block">
              <div class="schoolchildren-box">
                <div class="confirm-item-text">
                  <p>小学生：<?=$_POST['schoolchildren'] ?>名様</p>
                </div>
              </div>
              <div>
                <div class="confirm-item-text">
                  <p>小学生未満：<?=$_POST['preschooler'] ?>名様</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="confirm-box">
        <p class="confirm-box-title">ご利用日時</p>
          <div class="confirm-box-text confirm-box-block">
            <div class="date-box">
              <p class="confirm-item-name">お日にち</p>
              <p class="confirm-item-text"><?=$_POST['date'] ?></p>
            </div>
            <div>
              <p class="confirm-item-name">お時間</p>
              <p class="confirm-item-text"><?=$_POST['time'] ?>入店</p>
            </div>
        </div>
      </div>
      <div class="confirm-box">
        <p class="confirm-box-title">ご注文内容</p>
        <div class="confirm-box-text">
          <p class="confirm-item-name">プラン</p>
          <p class="confirm-item-text"><?=$_POST['plan'] ?>プラン</p>
          <p class="confirm-item-name">コース</p>
          <div class="confirm-item-text">
          <?php
            $total_price = 0;

            if ($_POST['plan'] === '席だけ') {
              echo "⚫︎ {$products[$_POST['seat']-1]['name']}　¥{$products[$_POST['seat']-1]['price']}　•••　個数：{$_POST['seat-quantity']}";
              echo '<br/>';

              $product_orders[0]['name'] = $products[$_POST['seat']-1]['name'];
              $product_orders[0]['price'] = $products[$_POST['seat']-1]['price'];
              $product_orders[0]['category'] = $products[$_POST['seat']-1]['category'];
              $product_orders[0]['quantity'] = $_POST['seat-quantity'];

              $total_price = $products[$_POST['seat']-1]['price'];

            } else {
              $bbqset_total_price = 0;
              $drink_total_price = 0;
              $option_total_price = 0;
              $takeover_count = 0;

              // BBQセット
              echo '<p>BBQセット</p>';
              echo '<div class="menu-box">';
            
              foreach($bbqset_namesandquantities as $key=>$val) {
                echo "⚫︎ {$products[$val['bbqset_products_id']]['name']}　¥{$products[$val['bbqset_products_id']]['price']}　•••　個数：{$val['quantity']}";
                echo '<br/>';

                $product_orders[$key]['name'] = $products[$val['bbqset_products_id']]['name'];
                $product_orders[$key]['price'] = $products[$val['bbqset_products_id']]['price'];
                $product_orders[$key]['category'] = $products[$val['bbqset_products_id']]['category'];
                $product_orders[$key]['quantity'] = $val['quantity'];
                $takeover_count++;

                $bbqset_total_price += ($products[$val['bbqset_products_id']]['price'])*($val['quantity']);
              }

              echo '</div>';

              // ドリンク
              if ($drink_namesandquantities != null) {
                echo "<p>ドリンク</p>";
                echo '<div class="menu-box">';

                foreach($drink_namesandquantities as $key=>$val) {
                  echo "⚫︎ {$products[$val['drink_products_id']]['name']}　¥{$products[$val['drink_products_id']]['price']}　•••　個数：{$val['quantity']}";
                  echo '<br/>';

                  $product_orders[$key+$takeover_count]['name'] = $products[$val['drink_products_id']]['name'];
                  $product_orders[$key+$takeover_count]['price'] = $products[$val['drink_products_id']]['price'];
                  $product_orders[$key+$takeover_count]['category'] = $products[$val['drink_products_id']]['category'];
                  $product_orders[$key+$takeover_count]['quantity'] = $val['quantity'];
              
                  $drink_total_price += ($products[$val['drink_products_id']]['price'])*($val['quantity']);
                }

                echo '</div>';
              } else {
                false;
              }
            
              // オプション
              if ($option_namesandquantities != null) {
                echo '<p>オプション</p>';
                echo '<div class="menu-box">';

                $option_orders = [];
              
                foreach($option_namesandquantities as $key=>$val) {
                  echo "⚫︎ {$options[$val['option_products_id']]['name']}　¥{$options[$val['option_products_id']]['price']}　•••　個数：{$val['quantity']}";
                  echo '<br/>';

                  $option_orders[$key]['name'] = $options[$val['option_products_id']]['name'];
                  $option_orders[$key]['price'] = $options[$val['option_products_id']]['price'];
                  $option_orders[$key]['quantity'] = $val['quantity'];
              
                  $option_total_price += ($options[$val['option_products_id']]['price'])*($val['quantity']);
                }

                echo '</div>';
              } else {
                false;
              }
            }
          ?>
          </div>
          <p class="confirm-item-name">総合計</p>
          <div class="confirm-item-text">
          <?php
            if (!isset($_POST['seat'])) {
              $total_price = $bbqset_total_price + $drink_total_price + $option_total_price;
            }
            echo "¥{$total_price}";
          ?>
          </div>
        </div>
      </div>
      <div class="confirm-box">
        <p class="confirm-box-title">表者の方のご連絡先</p>
        <div class="confirm-box-text">
          <p class="confirm-item-name">お名前</p>
          <p class="confirm-item-text"><?=$_POST['name'] ?> (<?=$_POST['name_kana'] ?>)　様</p>
          <p class="confirm-item-name">ご住所</p>
          <p class="confirm-item-text"><?=$_POST['address_prefectures'] ?> <?=$_POST['address_city'] ?> <?=$_POST['address_subsequent'] ?></p>
          <p class="confirm-item-name">日中にご連絡の繋がるお電話番号</p>
          <p class="confirm-item-text"><?=$_POST['phone'] ?></p>
          <p class="confirm-item-name">メールアドレス</p>
          <p class="confirm-item-text"><?=$_POST['email'] ?></p>
          <p class="confirm-item-name">法人名・団体名</p>
          <p class="confirm-item-text"><?=$_POST['group_name'] ?></p>
        </div>
      </div>
      <div class="form-block">
        <form method="post" action="reservation_create.php">
          <input type="hidden" name="adult" value="<?=$_POST['adult'] ?>">
          <input type="hidden" name="schoolchildren" value="<?=$_POST['schoolchildren'] ?>">
          <input type="hidden" name="preschooler" value="<?=$_POST['preschooler'] ?>">
          <input type="hidden" name="date" value="<?=$_POST['date'] ?>">
          <input type="hidden" name="time" value="<?=$_POST['time'] ?>">
          <input type="hidden" name="plan" value="<?=$_POST['plan'] ?>">
          <?php foreach ($product_orders as $key=>$product_orders): ?>
            <input type="hidden" name="product_orders[<?=$key ?>][name]" value="<?=$product_orders['name'] ?>">
            <input type="hidden" name="product_orders[<?=$key ?>][price]" value="<?=$product_orders['price'] ?>">
            <input type="hidden" name="product_orders[<?=$key ?>][category]" value="<?=$product_orders['category'] ?>">
            <input type="hidden" name="product_orders[<?=$key ?>][quantity]" value="<?=$product_orders['quantity'] ?>">
          <?php endforeach ?>
          <?php
            if ($option_namesandquantities != null) {
              foreach ($option_orders as $key=>$option_orders) {
                echo '<input type="hidden" name="option_orders['.$key.'][name]" value="'.$option_orders['name'].'">';
                echo '<input type="hidden" name="option_orders['.$key.'][price]" value="'.$option_orders['price'].'">';
                echo '<input type="hidden" name="option_orders['.$key.'][quantity]" value="'.$option_orders['quantity'].'">';
              }
            } else {
              false;
            }
          ?>
          <input type="hidden" name="total_price" value="<?=$total_price ?>">
          <input type="hidden" name="name" value="<?=$_POST['name'] ?>">
          <input type="hidden" name="name_kana" value="<?=$_POST['name_kana'] ?>">
          <input type="hidden" name="address_prefectures" value="<?=$_POST['address_prefectures'] ?>">
          <input type="hidden" name="address_city" value="<?=$_POST['address_city'] ?>">
          <input type="hidden" name="address_subsequent" value="<?=$_POST['address_subsequent'] ?>">
          <input type="hidden" name="phone" value="<?=$_POST['phone'] ?>">
          <input type="hidden" name="email" value="<?=$_POST['email'] ?>">
          <input type="hidden" name="group_name" value="<?=$_POST['group_name'] ?>">
          <input type="hidden" name="phase" value="0">
          <input type="submit" id="submitbtn" class="submitbtn-after" onclick="return reservation_pop()" value="予約を確定する">
        </form>
        <form method="post" action="reservation_new.php">
          <input type="hidden" name="name" value="<?=$_POST['name'] ?>">
          <input type="hidden" name="name_kana" value="<?=$_POST['name_kana'] ?>">
          <input type="hidden" name="address_prefectures" value="<?=$_POST['address_prefectures'] ?>">
          <input type="hidden" name="address_city" value="<?=$_POST['address_city'] ?>">
          <input type="hidden" name="address_subsequent" value="<?=$_POST['address_subsequent'] ?>">
          <input type="hidden" name="phone" value="<?=$_POST['phone'] ?>">
          <input type="hidden" name="email" value="<?=$_POST['email'] ?>">
          <input type="hidden" name="group_name" value="<?=$_POST['group_name'] ?>">
          <input type="hidden" name="check" value="true">
          <input type="submit" id="submitbtn" class="submitbtn-after" value="戻る">
        </form>
      </div>
    </div>
  </div>
  <script>
      function reservation_pop() {
        return confirm("この内容で予約を確定します。よろしいですか？");
      }
  </script>
</body>
</html>