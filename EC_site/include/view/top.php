<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>商品一覧ページ</title>
  <link type="text/css" rel="stylesheet" href="css/ec_site.css">
</head>
<body>
    <header>
    <div class="header_box">
        <a href="top.php">
        <p class="logo">FRUIT SHOP</p>
        </a>
        <a class="nemu" href="logout.php">ログアウト</a>
        <a href="cart.php" class="cart"></a>
        <p class="nemu">ユーザー名：<?php print $user_name;?></p>
    </div>
    </header>
    <div class="content">
<?php foreach ($success_msg as $value) {?>
    <p class="success_msg"><?php print $value; ?></p>
<?php } ?>
<?php foreach ($err_msg as $value) { ?>
    <p class="err_msg"><?php print $value; ?></p>
<?php } ?>
    <ul class="item_list">
<?php foreach($item_data as $value) { ?>
        <li>
        <div class="item">
        <form action="top.php" method="post">
            <img class="item_img" src="images/uploaded_img/<?php print $value['img'];?>">
            <div class="item_info">
                <span class="item_name"><?php print $value['name'];?></span>
                <span class="item_price"><?php print $value['price'];?></span>
            </div>
<?php if($value ['stock'] > 0 ) { ?>
                <input class="cart_btn" type="submit" value="カートに入れる">
<?php } else if ($value ['stock'] <= 0) { ?>                
                <span class="red">売り切れ</span>
<?php } ?>            
            <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
            <input type="hidden" name="sql_kind" value="insert_cart">
        </form>
        </div>
<?php } ?>
         </li>
    </ul>
    </div>
</body>
</html>

