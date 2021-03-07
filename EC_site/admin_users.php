<?php
/*
*   user管理ページ
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

$user_data = get_user_data($link);
    if ($user_data === FALSE) {
        $err_msg = 'userテータ取得失敗';
    } else {
        $user_data = entity_assoc_array($user_data);
    }

close_db_connect($link);

include_once 'include/view/admin_users.php';
