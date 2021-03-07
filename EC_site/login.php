<?php
/*
*   ログインページ
*/
require_once 'include/conf/const.php';
require_once 'include/model/function.php';
$err_msg = array(); //エラーメッセージ
// セッション開始
session_start();
// セッション変数からログイン済みか確認
if (isset($_SESSION['user_id']) === TRUE) {
    // ログイン済みの場合、ホームページへリダイレクト
    header('Location: top.php');
    exit;
}

if (get_request_method() === 'POST') {
    $user_name = get_post_data('user_name'); 
    $password = get_post_data('password'); 
    
    $err_msg = check_login_user_name($user_name, $err_msg);
    $err_msg = check_login_password($password, $err_msg);
    
    if (count($err_msg) === 0) {
        $link = get_db_connect();
        $data = get_user_id($link, $user_name, $password);
        close_db_connect($link);
        if (count($data) === 0) {
            $err_msg[] = 'ユーザー名あるいはパスワードが違います';
        } 
    }
    
    if (count($err_msg) === 0) {
        $_SESSION['user_id'] = $data[0]['id'];
        if ($user_name === 'admin') {
        header('Location: admin_item.php');
        } else {
        header('Location: top.php');
        }
        exit;
    }
}

include_once 'include/view/login.php';