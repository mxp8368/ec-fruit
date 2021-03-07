<?php
/*
*   topページ
*/
require_once 'include/conf/const.php';
require_once 'include/model/function.php';
$err_msg = [];
$success_msg = []; 
$user_name = '';
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
    
    if ($sql_kind === 'insert_cart') {
        
        $item_id = get_post_data('item_id');
        $cart_data = get_cart_data($link, $user_id, $item_id);
        
        if (count($cart_data) === 0) {
            $result = insert_cart_table($link, $user_id, $item_id, 1);
        } else {
            $result = update_cart_table($link, $user_id, $item_id, $cart_data[0]['amount']+1);
        }
        if ($result !== TRUE) {
            $err_msg[] = 'カート登録失敗';
        } else {
            $success_msg[] = 'カートに登録しました。';
        }   
    }
}

$link = get_db_connect();

$user_name = get_login_user_name($link, $user_id);

$item_data = get_item_open_data($link);
    if ($item_data === FALSE) {
        $err_msg = '商品データ取得失敗';
    } else {
        $item_data = entity_assoc_array($item_data);
    }

close_db_connect($link);

include_once 'include/view/top.php';