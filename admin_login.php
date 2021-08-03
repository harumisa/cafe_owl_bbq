<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cafe OWL BBQ</title>
  <link rel="stylesheet" type="text/css" href="/css/base.css">
</head>
<body>
  <p class="return-link"><a href="index.php">トップページへ戻る</a></p>
  <div class="login-main">
    <div class="login-field">
      <form method="post" action="admin_login_confirm.php" class="login-form">
        <p>メールアドレスを入力してください。</p>
        <input type="text" name="email" class="fieldwidth"><br/>
        <p>パスワードを入力してください。</p>
        <input type="password" name="password" class="fieldwidth"><br/>
        <br/>
        <input type="submit" value="ログイン">
      </form>
      <br/>
      <p><a href="admin_pass_reset.php">パスワードを忘れた方はこちら</a></p>
    </div>
  </div>
</body>
</html>