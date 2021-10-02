<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cafe OWL BBQ</title>
  <link rel="stylesheet" type="text/css" href="css/reservation.css">
</head>
<body>
  <p class="return-link"><a href="reservation_index.php">予約一覧へ戻る</a></p>
  <div class="admin-main">
    <div class="admin-field">
      <p class="admin-field-title">予約フェーズの変更</p>
      <p>※一度キャンセルにするとお客様にメールが送信され、変更できなくなります。</p>
      <br/>
      <form method="post" action="reservation_phase_update.php" class="admin-form">
        <input type="hidden" name="id" value="<?=$_GET['id'] ?>">
        <label for="radio1"><div class="radio-block"><input id="radio1" type="radio" name="phase" value="0">予約中</div></label>
        <label for="radio2"><div class="radio-block"><input id="radio2" type="radio" name="phase" value="1">来店済</div></label>
        <label for="radio3"><div class="radio-block"><input id="radio3" type="radio" name="phase" value="2">キャンセル</div></label>
        <br/>
        <input type="submit" value="変更する">
      </form>
    </div>
  </div>
</body>
</html>