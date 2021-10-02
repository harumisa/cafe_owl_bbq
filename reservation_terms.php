<?php
  session_start();
  session_regenerate_id(true);
  $_SESSION = array();
  if (isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(),'',time()-42000,'/');
  }
  session_destroy();
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
  <div class="reservation-main">
    <div class="termsofuse">
      <p class="termsofuse-title">ご予約にあたってのお願い</p>
      <div class="termsofuse-text">
        1. 人数分のコース(最低BBQセット1つ)をご注文ください。<br/>
        ⚠️追加食材のお持ち込みはできません。お持ち込みをされたい場合は『席だけプラン』をご利用ください。<br/>
        ⚠️BBQに必要な物は全て揃っていますので、手ぶらで来ていただいて大丈夫です！<br/>
        <br/>
        2. ご利用時間は3時間制です。<br/>
        ⚠️ドリンクはL.O.2時間<br/>
        <br/>
        3. ペット、動物の入場はご遠慮ください。<br/>
        <br/>
        4. 連絡なしに30分が経過しますと、他のお客様にお席をお譲りし、且つキャンセル扱いとなりキャンセル料が発生致します。<br/>
        ⚠️遅れられる場合はお電話連絡を頂きます様、よろしくお願い致します。<br/>
        (当日のお客様都合のキャンセルはご注文頂いた食材の満額頂戴致します。)<br/>
        <br/>
        5. 食材と人数の変更・キャンセルについて<br/>
        ・〜9名 2日前の17時<br/>
        ・10名〜19名　 3日前の17時<br/>
        ・20名〜29名　 5日前の17時<br/>
        ・30名〜49名 10日前の17時<br/>
        ・50名〜99名 2週間前の17時<br/>
        ・100名以上 1ヶ月前<br/>
        上記の期日までに、お電話連絡頂きます様お願い致します。<br/>
        ※キャンセル期日が定休日とかぶる場合は繰り上げになります。<br/>
        上記期間内でのキャンセルは無料とさせて頂きます。<br/>
        上記以外の場合、ご注文頂いた内容の50%を、当日キャンセルの場合、全額を頂戴致します。<br/>
        <br/>
        6. 雨天営業について<br/>
        テント席の為、多少の雨でしたらご案内をさせて頂きます。<br/>
        ただ、大雨や横殴りの雨といった悪天候の場合は、当日にwebでご記入いただいたお電話番号にご利用の有無のご連絡をさせて頂きます。<br/>
        ⚠️当施設からの電話連絡がない場合は、基本的にキャンセルは承っていませんのでご了承ください。<br/>
        <br/>
        7. ご予約内容の変更、キャンセル、その他のお問い合わせは、直接当施設までご連絡ください。<br/>
        <br/>
        8. 複数の日程、及び、10名様以上でのご利用をご希望の際は電話にてご予約をお願い致します。<br/>
        <br/>
        9.アウトドア体験型BBQ(炭、花火など)の持ち込みはご遠慮頂きますようお願い致します。<br/>
      </div>
    </div>
    <form method="post" action="reservation_new.php" class="termsofuse-confirm">
      <label for="checkbtn"><div class="checkbtn-box">
        <input id="checkbtn" type="checkbox" name="check" value="true"><p>上記に同意します</p>
      </div></label>
      <input type="submit" id="submitbtn" class="submitbtn-before" value="予約する" disabled>
    </form>
  </div>
  <script>
    $(function() {
      $('#checkbtn').on('change', function(){
        if ($(this).prop('checked')) {
          $('#submitbtn').prop('disabled', false);
          $('#submitbtn').removeClass('submitbtn-before').addClass('submitbtn-after');
        } else {
          $('#submitbtn').prop('disabled', true);
          $('#submitbtn').removeClass('submitbtn-after').addClass('submitbtn-before');
        }
      });
    });
  </script>
</body>
</html>
