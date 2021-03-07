<?php
/*
*   check outページ
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
    $cart_data = get_cart_data($link, $user_id);
    if ($cart_data === FALSE) {
        $err_msg = 'カードテータ取得失敗';
    } else {
        $cart_data = entity_assoc_array($cart_data);
    }
    if (count($err_msg) === 0) {
        $err_msg = purchase($link, $user_id, $cart_data, $err_msg);
    }
}

$user_name = get_login_user_name($link, $user_id);

close_db_connect($link);

include_once 'include/view/check_out.php';