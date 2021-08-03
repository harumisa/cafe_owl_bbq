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
  <link rel="stylesheet" type="text/css" href="/css/base.css">
</head>
<body>
  <div class="admin-main">
    <p class="admin-name"><span class="admin-name-size"><?=$_SESSION['name'] ?></span>さんログイン中</p>
    <div class="admin-field">
      <p class="admin-field-title">adminメニュー</p>
      <a href="reservation_index.php">予約一覧を見る</a>
      <a href="admin_edit.php?id=<?=$_SESSION['id'] ?>">My情報を編集する</a>
      <a href="admin_index.php">管理者一覧を見る</a>
      <a href="admin_logout.php">ログアウトする</a>
    </div>
  </div>
</body>
</html>