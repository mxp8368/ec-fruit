<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ショッピングカートページ</title>
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
        <h1 class="title">ショッピングカート</h1>
<?php if (empty($cart_data) === TRUE) { ?>
        <p>カートは空です。</p>
<?php } ?>
<?php foreach ($success_msg as $value) { ?>
        <p class="success_msg"><?php print $value; ?></p>
<?php } ?>
<?php foreach ($err_msg as $value) { ?>
        <p class="err_msg"><?php print $value; ?></p>
<?php } ?>

        <div class="cart_list_title">
            <span class="cart_list_price">価格</span>
            <span class="cart_list_num">数量</span>
        </div>

        <ul class="cart_list">
<?php foreach ($cart_data as $value) { ?>
            <li>
            <div class="cart_item">
                <img class="cart_item_img" src="images/uploaded_img/<?php print $value['img'];?>">
                <span class="cart_item_name"><?php print $value['name'];?></span>
                <form class="cart_item_del" action="cart.php" method="post">
                    <input type="submit" value="削除">
                    <input type="hidden" name="cart_id" value="<?php print $value['id']; ?>">
                    <input type="hidden" name="sql_kind" value="delete_cart">
                </form>
                <span class="cart_item_price">￥<?php print $value['price']; ?></span>
                <form class="form_selected_amount" id="form_selected_amount" action="cart.php" method="post">
                    <input type="text" class="cart_item_num2" min="0" name="select_amount" value="<?php echo $value['amount']; ?>">個&nbsp;
                    <input type="submit" value="変更する">
                    <input type="hidden" name="cart_id" value="<?php print $value['id']; ?>">
                    <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                    <input type="hidden" name="sql_kind" value="change_cart">
                </form>
            </div>
            </li>
<?php } ?>
        </ul>
        <div class="buy_sum_box">
            <span class="buy_sum_title">合計</span>
            <span class="buy_sum_price">￥<?php print amount_price($cart_data); ?></span>
        </div>
        <div>
            <form action="check_out.php" method="post">
                <input class="buy_btn" type="submit" value="購入する">
            </form>
        </div>
    </div>    
</body>
</html>