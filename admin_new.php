<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    echo 'このページをご覧になるにはログインしてください。<br />';
    echo '<a href="admin_login.php">ログインページへ</a>';
    exit();
  } elseif ($_SESSION['login'] !== 0) {
    echo 'このページは権限が付与された人のみ閲覧できます。<br/>';
    echo '<a href="admin.php">adminメニューへ戻る</a>';
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
  <p class="return-link"><a href="admin_index.php">管理者一覧へ戻る</a></p>
  <div class="admin-main">
    <div class="admin-field">
      <p class="admin-field-title">管理者新規登録</p>
      <form method="post" action="admin_create.php" class="admin-form">
        <p>氏名<span class="admin-inputcolor">(※必須)</span></p>
        <input type="text" name="name" class="fieldwidth" placeholder="例）山田太郎"><br/>
        <p>メールアドレス<span class="admin-inputcolor">(※必須)</span></p>
        <input type="text" name="email" class="fieldwidth"><br/>
        <p>パスワード<span class="admin-inputcolor">(※必須)</span></p>
        <input type="password" name="password" class="fieldwidth"><br/>
        <p>役名<span class="admin-inputcolor">(※必須)</span></p>
        <select name="role" class="fieldwidth">
          <option value="0">責任者</option>
          <option value="1">従業員</option>
        </select><br/>
        <br/>
        <input type="submit" value="登録する">
      </form>
    </div>
  </div>
</body>
</html>