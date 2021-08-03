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

  try {
    $dsn = 'mysql:dbname=cafe_owl_bbq;host=localhost';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM admins';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <link rel="stylesheet" type="text/css" href="/css/base.css">
</head>
<body>
  <div class="adminindex-main">
    <p class="admin-name"><span class="admin-name-size"><?=$_SESSION['name'] ?></span>さんログイン中</p>
    <p class="return-link"><a href="admin.php">adminメニューへ戻る</a></p>
    <p class="adminindex-title">管理者一覧</p>
    <div class="adminindex-field">
      <div class="admin-add">
        <a href="admin_new.php">新しく管理者を登録する</a>
      </div>
      <table>
        <tr>
        <th>管理者ID</th>
          <th>氏名</th>
          <th>役名</th>
          <th></th>
          <th></th>
        </tr>
        <?php foreach($admins as $admin): ?>
          <tr>
            <td><?=$admin['id'] ?></td>
            <td><?=$admin['name'] ?></td>
            <?php
              switch($admin['role']) {
                case '0':
                  echo '<td>責任者</td>';
                  break;

                case '1':
                  echo '<td>従業員</td>';
                  break;
            
                default:
                  echo '<td></td>';
                  break;
              }
              
              if ($admin['id'] !== $_SESSION['id']) {
                echo '<td class="adminindex-link-center"><a href="admin_role_edit.php?id='.$admin['id'].'">役名を変更</a></td>
                      <td>
                        <form method="post" action="admin_delete.php" class="adminindex-link-center">
                          <input type="hidden" name="id" value="'.$admin['id'].'">
                          <input type="submit" onclick="return del_pop()" value="削除" style="cursor: pointer; border: none; background: none; color: #0000ff; font-size: 16px;">
                        </form>
                      </td>';
              } else {
                echo '<td class="adminindex-link-center">役名を変更</td>
                      <td>
                        <p style="text-align: center;">削除</p>
                      </td>';
              }
            ?>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
  <script>
    function del_pop() {
      return confirm("削除します。よろしいですか？");
    }
  </script>
</body>
</html>