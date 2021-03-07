<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>商品管理</title>
    <link type="text/css" rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>FRUIT SHOP 管理ページ</h1>
    <div>
    <a class="nemu" href="logout.php">ログアウト</a>
    <a href="admin_users.php">ユーザ管理ページ</a>
    </div>
<?php foreach($success_msg as $value) { ?>
        <p class="success_msg"><?php print $value;?></p>
<?php } ?>
<?php foreach($err_msg as $value) { ?>
        <p class="err_msg"><?php print $value;?></p>
<?php } ?>
    <section>
        <h2>商品の登録</h2>
        <form method="post" enctype="multipart/form-data">
            <div>
                <label>商品名: <input type="text" name="new_name"></label>
            </div>
            <div>
                <label>値　段: <input type="text" name="new_price"></label>
            </div>
            <div>
                <label>個　数: <input type="text" name="new_stock"></label>
            </div>
            <div>
                <label>商品画像: <input type="file"name="new_img"></label>
            </div>
            <div>
                <label>ステータス:
                <select name="new_status">
                    <option value="0">非公開</option>
                    <option value="1">公開</option>
                </select>
                </label>
            </div>
            <div>
                <input type="submit" value="商品を登録する">
                <input type="hidden" name="sql_kind" value="insert">
            </div>
        </form>
    </section>
    <section>
        <h2>商品情報の一覧・変更</h2>
        <table>
            <caption>商品一覧</caption>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
            
<?php foreach ($item_data as $value) { ?>
<?php if((int)$value['status'] === 0) { ?>
            <tr class = "status_false">
<?php } else  { ?>
            <tr>
<?php }  ?>
                <form method="post">
                    <td><img src="images/uploaded_img/<?php print $value['img'] ; ?>"></td>
                    <td><?php print $value['name']; ?></td>
                    <td><?php print $value['price']?></td>
                    <td>
                    <input type="text" name="update_stock" value="<?php print $value['stock']?>">
                    <input type="submit" value="変更">
                    </td>
                    <input type="hidden" name="sql_kind" value="update">
                    <input type="hidden" name="id" value="<?php print $value['id']?>">
                </form>
                
                <form method="post">
                    <td>
<?php if((int)$value['status'] === 1) { ?>
                            <input type="submit" value="公開->非公開">
                            <input type ="hidden" name = "change_status" value = "0">
<?php } else if ((int)$value['status'] === 0) { ?>
                            <input type="submit" value="非公開->公開">
                            <input type ="hidden" name = "change_status" value = "1">
<?php }  ?>
                    </td>   
                    <input type="hidden" name="sql_kind" value="change">
                    <input type="hidden" name="id" value="<?php print $value['id'] ?>">
                </form>
                
                <form method="post">
                    <td>
                        <input type="submit" value="削除する">
                        <input type="hidden" name="id" value="<?php print $value['id']; ?>">
                        <input type="hidden" name="sql_kind" value="delete">
                    </td>    
                </form>   
            </tr>
<?php }  ?>
        </table>
    </section>
</body>
</html>