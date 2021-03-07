<?php
/*
*   cartページ
*/
require_once 'include/conf/const.php';
require_once 'include/model/function.php';
$err_msg = [];
$success_msg = []; 

// セッション開始
session_start();
// セッション変数からuser_id取得
if (isset($_SESSION['user_id']) === TRUE) {
   $user_id = $_SESSION['user_id'];
} 
else {
  // 非ログインの場合、ログインページへリダイレクト
  header('Location: login.php');
  exit;
}

$link = get_db_connect();

if (get_request_method() === 'POST') {
    $sql_kind = get_post_data('sql_kind');

    if ($sql_kind === 'delete_cart') {
        $cart_id = get_post_data('cart_id');
        
        if (count($err_msg) === 0) {
            if (delete_cart_item($link, $cart_id) === TRUE) {
                $success_msg[] = '商品を削除しました';
            } else {
                $err_msg[] = '商品削除に失敗しました';
            }
        }
    } else if ($sql_kind === 'change_cart') {
        $new_amount = get_post_data('select_amount');
        $cart_id = get_post_data('cart_id');
        $err_msg = check_amount($new_amount, $err_msg);
        
        if (count($err_msg) === 0) {
            if (update_new_amount($link, $cart_id, $new_amount) === TRUE) {
                $success_msg[] = '数量更新しました';
            } else {
                $err_msg[] = '数量更新エラー';
            }
        }
    }
}

$user_name = get_login_user_name($link, $user_id);

$cart_data = get_cart_data($link, $user_id);
    if ($cart_data === FALSE) {
        $err_msg = 'カードテータ取得失敗';
    } else {
        $cart_data = entity_assoc_array($cart_data);
    }

close_db_connect($link);

include_once 'include/view/cart.php';