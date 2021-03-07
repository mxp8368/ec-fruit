<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>購入完了ページ</title>
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
<?php foreach ($err_msg as $value) { ?>
    <p class="err_msg"><?php print $value; ?></p>
<?php } ?>
<?php if (empty($cart_data) === TRUE) { ?>
        <p class="err_msg">カートは空です。</p>
<?php } ?>
<?php if (empty($cart_data) === FALSE && count($err_msg) === 0) { ?>
        <div class="finish_msg">ご購入ありがとうございました。</div>
        <div class="cart_list_title">
            <span class="cart_list_price">価格</span>
            <span class="cart_list_num">数量</span>
        </div>
            <ul class="cart_list">
<?php foreach ($cart_data as $value) { ?>
                <li>
                <div class="cart_item">
                    <img class="cart_item_img" src="images/uploaded_img/<?php echo $value['img']; ?>">
                    <span class="cart_item_name"><?php print $value['name']; ?></span>
                    <span class="cart_item_price">￥<?php print $value['price']; ?></span>
                    <span class="finish_item_price"><?php print $value['amount']; ?></span>
                </div>
                </li>
<?php } ?>
            </ul>
        <div class="buy_sum_box">
            <span class="buy_sum_title">合計</span>
            <span class="buy_sum_price">￥<?php print amount_price($cart_data); ?></span>
        </div>
<?php } ?>
    </div>
</body>
</html>