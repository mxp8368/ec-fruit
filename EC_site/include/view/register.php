<!DOCTYPE html>
<html lang="ja"><head>
  <meta charset="utf-8">
  <title>ユーザ登録ページ</title>
  <link type="text/css" rel="stylesheet" href="css/ec_site.css">
</head>
<body>
  <header>
    <div class="header_box">
        <a href="top.php">
        <p class="logo">FRUIT SHOP</p>
        </a>
        <a href="cart.php" class="cart"></a>
    </div>
  </header>
  <div class="content">
    <div class="register">
<?php foreach ($success_msg as $value) { ?>
      <p class="success_msg"><?php print $value;?></p>
<?php } ?>
<?php foreach ($err_msg as $value) { ?>
      <p class="err_msg"><?php print $value;?></p>
<?php } ?>

      <form method="post" action="register.php">
        <div>ユーザー名：<input type="text" name="user_name" placeholder="ユーザー名"></div>
        <div>パスワード：<input type="password" name="password" placeholder="パスワード">
        <div><input type="submit" value="ユーザーを新規作成する">
        <div class="login_link"><a href="login.php">ログインページに移動する</a></div>
        </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>