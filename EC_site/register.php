<?php
/*
*   ユーザーの新規作成
*/
require_once 'include/conf/const.php';
require_once 'include/model/function.php';
$err_msg = [];
$success_msg = []; 


if (get_request_method() === 'POST') {
    
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');
    $created_date = date('Y-m-d H:i:s');
    
    $err_msg = check_user_name($user_name, $err_msg);
    $err_msg = check_password($password, $err_msg);

    if (count($err_msg) === 0) {
        $link = get_db_connect();
        $user = get_user_name($link, $user_name);
        if (count($user) > 0 && $user[0]['user_name'] === $user_name) {
            $err_msg[] = '既に同じ名前のユーザーが存在します';
            
        } else {
            if (insert_user_table($link, $user_name, $password, $created_date) !== TRUE) { 
                $err_msg[] = 'ユーザー登録失敗';
            } else {
                $success_msg[] = 'ユーザー登録成功';
            }
        }
        close_db_connect($link);
    }
}

include_once 'include/view/register.php';