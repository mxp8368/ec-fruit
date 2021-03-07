<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログインページ</title>
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
    <div class="login">
        <form method="post" action="login.php">
        <div><input type="text" name="user_name" placeholder="ユーザー名"></div>
        <div><input type="password" name="password" placeholder="パスワード">
        <div><input type="submit" value="ログイン">
<?php foreach($err_msg as $value) { ?>
        <p class="err_msg"><?php print $value;?></p>
<?php } ?>
        <div class="account_create">
        <a href="register.php">ユーザーの新規作成</a>
    </div>
    </div>
  </div>
  </form>
</div>
</div>
</body>
</html>