<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cafe OWL BBQ</title>
  <link rel="stylesheet" type="text/css" href="/css/base.css">
</head>
<body>
  <p class="return-link"><a href="admin_login.php">戻る</a></p>
  <div class="login-main">
    <div class="login-field">
      <p>パスワードを忘れた場合は再設定が必要です。</p>
      <p>登録済みのメールアドレスに再設定用のURLを送ります。</p>
      <br/>
      <form method="post" action="admin_pass_reset_confirm.php" class="login-form">
        <p>adminに登録済みのメールアドレスを入力してください。</p>
        <input type="text" name="email" class="fieldwidth"><br/>
        <br/>
        <input type="submit" value="送信">
      </form>
    </div>
  </div>
</body>
</html>