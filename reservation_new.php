<?php
  if(empty($_POST['check']) || $_POST['check'] !== 'true') {
    header('Location: reservation_terms.php');
    exit();
  }

  session_start();
  session_regenerate_id(true);

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM products WHERE category = 0';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $seat_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM products WHERE category = 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $bbqset_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM products WHERE category = 2';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $drink_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM options';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $option_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <div class="reservation-main">
    <div class="formback">
      <div class="formarea">
        <form method="post" action="reservation_confirm.php">
          <p class="formarea-title"><span class="formspan3">ご</span>予約条件を入力してください<span class="formspan1 formspan2">（必須）</span></p>
          <div class="formarea-block">
            <div class="conditions-block">
              <p>大人(中学生以上)</p>
              <div class="formarea-block">
                <select name="adult" class="inputfield fieldwidth3">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>名様
              </div>
            </div>
            <div class="conditions-block">
              <p>お子様</p>
              <div class="child-block">
                <p>小学生</p>
                <select name="schoolchildren" class="inputfield fieldwidth3">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>名様　
                <p>小学生未満</p>
                <select name="preschooler" class="inputfield fieldwidth3">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>名様
              </div>
            </div>
          </div>
          <?php
            if (isset($_SESSION['vali_check']['people_count']) && $_SESSION['vali_check']['people_count'] === 1) {
              echo '<p class="vali-return-msg vali-return-msg-excess">※ご利用人数は2名様以上または10名様以内をご指定下さい(10名様以上でご利用をご希望の場合は直接お電話にてお申込み下さい)</p>';
            } else {
              echo '<p class="vali-return-msg"></p>';
            }
          ?>
          <div class="formarea-block">
            <div class="conditions-block">
              <p class="fieldtitle">お日にち</p>
              <p class="vali-return-msg"></p>
              <div class="formarea-block">
                <input type="date" name="date" id="date" class="inputfield fieldwidth5" min="<?=date('Y-m-d') ?>" max="<?=date('Y-m-d', strtotime('+2 month')) ?>" required><br/>
              </div>
            </div>
            <div class="conditions-block">
              <p class="fieldtitle">お時間</p>
              <div id="no-time-msg">
                <p class="vali-return-msg"></p>
              </div>
              <div class="formarea-block">
                <select name="time" id="time" class="inputfield fieldwidth5">
                  <!-- ajax貼り付け場所 -->
                </select>
              </div>
            </div>
          </div>
          <div class="courseplan">
            <p class="formarea-title"><span class="formspan3">プ</span>ランをお選びください<span class="formspan1 formspan2">（必須）</span></p>
            <select name="plan" id="plan" class="inputfield fieldwidth1">
              <option value="BBQ">BBQプラン</option>
              <option value="席だけ">席だけプラン</option>
            </select>
            <div class="bbq">
              <p class="formarea-title"><span class="formspan3">コ</span>ースをお選びください<span class="formspan1">（<span class="formspan2">*</span>は必須項目）</span></p>
              <div class="precautions">
                ⚠️最低お一人様につき、BBQセット一つのご注文をお願いしております。<br/>
                ⚠️キッズコースは小学生の方までのメニューとなりますのでご注意ください。<br/>
                ⚠️小学生未満のお子さまでお食事メニューをご注文されない場合は入場料が必要となります。<br/>
                ⚠️価格は全て税込です。
              </div>
              <p class="fieldtitle">BBQセット<span class="formspan2">*</span></p>
              <?php
                if (isset($_SESSION['vali_check']['bbqset_count']) && $_SESSION['vali_check']['bbqset_count'] === 1) {
                  echo '<p class="vali-return-msg vali-return-msg-excess">※お一人様につきBBQセット一つのご注文をお願いいたします</p>';
                } else {
                  echo '<p class="vali-return-msg"></p>';
                }
              ?>
              <div id="bbqset">
                <div class="formarea-block">
                  <select name="bbqset-name0" class="inputfield fieldwidth4">
                    <option value="-">-----</option>
                    <?php foreach($bbqset_products as $bbqset_product): ?>
                      <option value="<?=$bbqset_product['id']-1 ?>"><?="{$bbqset_product['name']}　　¥{$bbqset_product['price']}" ?></option>
                    <?php endforeach ?>
                  </select>
                  <select name="bbqset-quantity0" class="inputfield fieldwidth3">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select>
                </div>
              </div>
              <p id="bbqset-coursetoadd">＋　他のメニューを追加する</p>
              <p class="fieldtitle">ドリンク</p>
              <p class="vali-return-msg"></p>
              <div id="drink">
                <div class="formarea-block">
                  <select name="drink-name0" class="inputfield fieldwidth4">
                    <option value="-">-----</option>
                    <?php foreach($drink_products as $drink_product): ?>
                      <option value="<?=$drink_product['id']-1 ?>"><?="{$drink_product['name']}　　¥{$drink_product['price']}" ?></option>
                    <?php endforeach ?>
                  </select>
                  <select name="drink-quantity0" class="inputfield fieldwidth3">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select>
                </div>
              </div>
              <p id="drink-coursetoadd">＋　他のメニューを追加する</p>
              <p class="fieldtitle">オプション</p>
              <p class="vali-return-msg"></p>
              <div id="option">
                <div class="formarea-block">
                  <select name="option-name0" class="inputfield fieldwidth4">
                    <option value="-">-----</option>
                    <?php foreach($option_products as $option_product): ?>
                      <option value="<?=$option_product['id']-1 ?>"><?="{$option_product['name']}　　¥{$option_product['price']}" ?></option>
                    <?php endforeach ?>
                  </select>
                  <select name="option-quantity0" class="inputfield fieldwidth3">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
              </div>
              <p id="option-coursetoadd">＋　他のメニューを追加する</p>
            </div>
          </div>
          <p class="formarea-title"><span class="formspan3">代</span>表者の方のご連絡先を入力してください<span class="formspan1">（<span class="formspan2">*</span>は必須項目）</span></p>
          <p class="fieldtitle">お名前<span class="formspan2">*</span></p>
          <div class="formarea-block">
            <div class="fieldwidth1">
              <?php
                if (isset($_POST['name']) && empty($_POST['name'])) {
                  echo '<p class="vali-return-msg">※お名前(漢字)は必須項目です</p>';
                } elseif (isset($_SESSION['vali_check']['name_count']) && $_SESSION['vali_check']['name_count'] === 1) {
                  echo '<p class="vali-return-msg">※お名前(漢字)は10文字以内でご入力下さい</p>';
                } else {
                  echo '<p class="vali-return-msg"></p>';
                }
              ?>
              <input type="text" name="name" class="inputfield" placeholder="漢字（例：山田太郎）" value="<?php if(!empty($_POST['name'])) echo $_POST['name']; ?>">
            </div>
            <div class="fieldwidth1">
              <?php
                if (isset($_POST['name_kana']) && empty($_POST['name_kana'])) {
                  echo '<p class="vali-return-msg">※お名前(ふりがな)は必須項目です</p>';
                } elseif (isset($_SESSION['vali_check']['name_kana_count']) && $_SESSION['vali_check']['name_kana_count'] === 1) {
                  echo '<p class="vali-return-msg vali-return-msg-excess">※お名前(ふりがな)は20文字以内でご入力下さい</p>';
                } else {
                  echo '<p class="vali-return-msg"></p>';
                }
              ?>
              <input type="text" name="name_kana" class="inputfield" placeholder="ふりがな（例：やまだたろう）" value="<?php if(!empty($_POST['name_kana'])) echo $_POST['name_kana']; ?>">
            </div>
          </div>
          <p class="fieldtitle">ご住所<span class="formspan2">*</span></p>
          <div class="formarea-block">
            <div class="fieldwidth1">
              <?php
                if (isset($_POST['address_prefectures']) && empty($_POST['address_prefectures'])) {
                  echo '<p class="vali-return-msg">※ご住所(都道府県)は必須項目です</p>';
                } elseif (isset($_SESSION['vali_check']['address_prefectures_count']) && $_SESSION['vali_check']['address_prefectures_count'] === 1) {
                  echo '<p class="vali-return-msg">※都道府県名の文字数が超過しています</p>';
                } else {
                  echo '<p class="vali-return-msg"></p>';
                }
              ?>
              <input type="text" name="address_prefectures" class="inputfield" placeholder="都道府県" value="<?php if(!empty($_POST['address_prefectures'])) echo $_POST['address_prefectures']; ?>">
            </div>
            <div class="fieldwidth1">
              <?php
                if (isset($_POST['address_city']) && empty($_POST['address_city'])) {
                  echo '<p class="vali-return-msg">※ご住所(市郡区町村)は必須項目です</p>';
                } else {
                  echo '<p class="vali-return-msg"></p>';
                }
              ?>
              <input type="text" name="address_city" class="inputfield" placeholder="市郡区町村" value="<?php if(!empty($_POST['address_city'])) echo $_POST['address_city']; ?>">
            </div>
          </div>
          <div class="fieldwidth2">
            <?php
              if (isset($_POST['address_subsequent']) && empty($_POST['address_subsequent'])) {
                echo '<p class="vali-return-msg">※ご住所(それ以降のご住所)は必須項目です</p>';
              } else {
                echo '<p class="vali-return-msg"></p>';
              }
            ?>
            <input type="text" name="address_subsequent" class="inputfield" placeholder="それ以降のご住所" value="<?php if(!empty($_POST['address_subsequent'])) echo $_POST['address_subsequent']; ?>">
          </div>
          <p class="fieldtitle fieldtitle-excess">日中にご連絡の繋がるお電話番号<span class="formspan2">*</span></p>
          <div class="fieldwidth2">
            <?php
              if (isset($_POST['phone']) && empty($_POST['phone'])) {
                echo '<p class="vali-return-msg">※お電話番号は必須項目です</p>';
              } elseif (isset($_SESSION['vali_check']['phone_regular_expressions']) && $_SESSION['vali_check']['phone_regular_expressions'] === 1) {
                echo '<p class="vali-return-msg">※お電話番号は半角数字でご入力下さい</p>';
              } else {
                echo '<p class="vali-return-msg"></p>';
              }
            ?>
            <input type="text" name="phone" class="inputfield" value="<?php if(!empty($_POST['phone'])) echo $_POST['phone']; ?>">
          </div>
          <p class="fieldtitle">メールアドレス<span class="formspan2">*</span></p>
          <div class="fieldwidth2">
            <?php
              if (isset($_POST['email']) && empty($_POST['email'])) {
                echo '<p class="vali-return-msg">※メールアドレスは必須項目です</p>';
              } elseif (isset($_SESSION['vali_check']['email_regular_expressions']) && $_SESSION['vali_check']['email_regular_expressions'] === 1) {
                echo '<p class="vali-return-msg">※メールアドレスは正しくご入力下さい</p>';
              } else {
                echo '<p class="vali-return-msg"></p>';
              }
            ?>
            <input type="text" name="email" class="inputfield" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
          </div>
          <p class="fieldtitle">法人名・団体名</p>
          <div class="fieldwidth2">
            <p class="vali-return-msg"></p>
            <input type="text" name="group_name" class="inputfield" value="<?php if(!empty($_POST['group_name'])) echo $_POST['group_name']; ?>">
          </div>
          <input type="hidden" name="check" value="true">
          <input type="submit" id="submitbtn" class="submitbtn-after" value="次へ進む">
        </form>
      </div>
    </div>
  </div>
  <script>
    $(function() {

      $('#date').on('change', function() {
        $('#time').children().remove();
        $.post({
          url: 'ajax_reservation_new.php',
          data: { 'date': $('#date').val() },
          dataType: 'json'
        })
        .done(function(data) {
          if (data.length !== 0) {
            $.each(data, function(key,item) {
              $('#time').append(`<option value="${item}">${item}</option>`);
            })
          } else {
            $('#no-time-msg').children().remove();
            $('#no-time-msg').append(`<p class="vali-return-msg">※ご指定いただいたお日にちは空きがございません</p>`);
            $('#date').on('change', function() {
              $('#no-time-msg').children().remove();
              $('#no-time-msg').append(`<p class="vali-return-msg"></p>`);
            })
          }
        })
        .fail(function() {
          alert('読み込みエラー');
        });
      });


      var seatHtml = `<div class="seat">
                        <p class="formarea-title"><span class="formspan3">コ</span>ースをお選びください<span class="formspan1 formspan2">（必須）</span></p>
                        <p class="precautions">⚠️価格は全て税込です。</p>
                        <select name="seat" class="inputfield fieldwidth4">
                          <?php foreach($seat_products as $seat_product): ?>
                            <option value="<?=$seat_product['id'] ?>"><?="{$seat_product['name']}　　¥{$seat_product['price']}" ?></option>
                          <?php endforeach ?>
                        </select>
                        <input type="hidden" name="seat-quantity" value="1">
                      </div>`

      var bbqHtml = `<div class="bbq">
                      <p class="formarea-title"><span class="formspan3">コ</span>ースをお選びください<span class="formspan1">（<span class="formspan2">*</span>は必須項目）</span></p>
                      <div class="precautions">
                        ⚠️最低お一人様につき、BBQセット一つのご注文をお願いしております。<br/>
                        ⚠️キッズコースは小学生の方までのメニューとなりますのでご注意ください。<br/>
                        ⚠️小学生未満のお子さまでお食事メニューをご注文されない場合は入場料が必要となります。<br/>
                        ⚠️価格は全て税込です。
                      </div>
                      <p class="fieldtitle">BBQセット<span class="formspan2">*</span></p>
                      <p class="vali-return-msg"></p>
                      <div id="bbqset">
                        <div class="formarea-block">
                          <select name="bbqset-name0" class="inputfield fieldwidth4">
                            <option value="-">-----</option>
                            <?php foreach($bbqset_products as $bbqset_product): ?>
                              <option value="<?=$bbqset_product['id']-1 ?>"><?="{$bbqset_product['name']}　　¥{$bbqset_product['price']}" ?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="bbqset-quantity0" class="inputfield fieldwidth3">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                          </select>
                        </div>
                      </div>
                      <p id="bbqset-coursetoadd">＋　他のメニューを追加する</p>
                      <p class="fieldtitle">ドリンク</p>
                      <p class="vali-return-msg"></p>
                      <div id="drink">
                        <div class="formarea-block">
                          <select name="drink-name0" class="inputfield fieldwidth4">
                            <option value="-">-----</option>
                            <?php foreach($drink_products as $drink_product): ?>
                              <option value="<?=$drink_product['id']-1 ?>"><?="{$drink_product['name']}　　¥{$drink_product['price']}" ?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="drink-quantity0" class="inputfield fieldwidth3">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                          </select>
                        </div>
                      </div>
                      <p id="drink-coursetoadd">＋　他のメニューを追加する</p>
                      <p class="fieldtitle">オプション</p>
                      <p class="vali-return-msg"></p>
                      <div id="option">
                        <div class="formarea-block">
                          <select name="option-name0" class="inputfield fieldwidth4">
                            <option value="-">-----</option>
                            <?php foreach($option_products as $option_product): ?>
                              <option value="<?=$option_product['id']-1 ?>"><?="{$option_product['name']}　　¥{$option_product['price']}" ?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="option-quantity0" class="inputfield fieldwidth3">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
                        </div>
                      </div>
                      <p id="option-coursetoadd">＋　他のメニューを追加する</p>
                    </div>`

      var bbqSetHtml = `<div class="after-bbqset">
                          <select name="before1" class="inputfield fieldwidth4">
                            <option value="-">-----</option>
                            <?php foreach($bbqset_products as $bbqset_product): ?>
                              <option value="<?=$bbqset_product['id']-1 ?>"><?="{$bbqset_product['name']}　　¥{$bbqset_product['price']}" ?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="before2" class="inputfield fieldwidth3">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                          </select>
                        </div>`

      var drinkHtml = `<div class="after-drink">
                        <select name="before1" class="inputfield fieldwidth4">
                          <option value="-">-----</option>
                          <?php foreach($drink_products as $drink_product): ?>
                            <option value="<?=$drink_product['id']-1 ?>"><?="{$drink_product['name']}　　¥{$drink_product['price']}" ?></option>
                          <?php endforeach ?>
                        </select>
                        <select name="before2" class="inputfield fieldwidth3">
                          <option value="0">0</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                        </select>
                      </div>`

      var optionHtml = `<div class="after-option">
                          <select name="before1" class="inputfield fieldwidth4">
                            <option value="-">-----</option>
                            <?php foreach($option_products as $option_product): ?>
                              <option value="<?=$option_product['id']-1 ?>"><?="{$option_product['name']}　　¥{$option_product['price']}" ?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="before2" class="inputfield fieldwidth3">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
                        </div>`

      $('#plan').on('change', function(){
        $('.bbq').remove();
        if ($(this).val() == '席だけ') {
          $('.courseplan').append(seatHtml);
        } else {
          $('.seat').remove();
        };
        if ($(this).val() == 'BBQ') {
          $('.courseplan').append(bbqHtml);

          $('#bbqset-coursetoadd').on('click', function(){
            $('#bbqset').append(bbqSetHtml)
            $('select[name="before1"]').attr('name', 'bbqset-name' + ($('.after-bbqset').length));
            $('select[name="before2"]').attr('name', 'bbqset-quantity' + ($('.after-bbqset').length));
          });
          $('#drink-coursetoadd').on('click', function(){
            $('#drink').append(drinkHtml);
            $('select[name="before1"]').attr('name', 'drink-name' + ($('.after-drink').length));
            $('select[name="before2"]').attr('name', 'drink-quantity' + ($('.after-drink').length));
          });
          $('#option-coursetoadd').on('click', function(){
            $('#option').append(optionHtml);
            $('select[name="before1"]').attr('name', 'option-name' + ($('.after-option').length));
            $('select[name="before2"]').attr('name', 'option-quantity' + ($('.after-option').length));
          });
        } else {
          $('.bbq').remove();
        };
      });

      $('#bbqset-coursetoadd').on('click', function(){
        $('#bbqset').append(bbqSetHtml);
        $('select[name="before1"]').attr('name', 'bbqset-name' + ($('.after-bbqset').length));
        $('select[name="before2"]').attr('name', 'bbqset-quantity' + ($('.after-bbqset').length));
      });
      $('#drink-coursetoadd').on('click', function(){
        $('#drink').append(drinkHtml);
        $('select[name="before1"]').attr('name', 'drink-name' + ($('.after-drink').length));
        $('select[name="before2"]').attr('name', 'drink-quantity' + ($('.after-drink').length));
      });
      $('#option-coursetoadd').on('click', function(){
        $('#option').append(optionHtml);
        $('select[name="before1"]').attr('name', 'option-name' + ($('.after-option').length));
        $('select[name="before2"]').attr('name', 'option-quantity' + ($('.after-option').length));
      });
    });
  </script>
</body>
</html>