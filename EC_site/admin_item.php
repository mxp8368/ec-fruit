<?php
/*
*   商品管理ページ
*/
require_once 'include/conf/const.php';
require_once 'include/model/function.php';
$err_msg = [];
$success_msg = []; 
$item_data = [];

// DB接続
$link = get_db_connect();

$sql_kind = '';
$update_stock = '';
$change_status = '';
$item_id = '';
$sql = '';

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

if (get_request_method() === 'POST') {
    $sql_kind = get_post_data('sql_kind');

    if ($sql_kind === 'insert') {
        
        $new_name = get_post_data('new_name');
        $new_price = get_post_data('new_price');
        $new_stock = get_post_data('new_stock');
        $new_status = get_post_data('new_status');
        
        $err_msg = check_name($new_name, $err_msg);
        $err_msg = check_price($new_price, $err_msg);
        $err_msg = check_stock($new_stock, $err_msg);
        $err_msg = check_status($new_status, $err_msg);
    
       list($new_img, $err_msg) = file_upload($err_msg);
        
        if (count($err_msg) === 0) {
            mysqli_autocommit($link, false);
            if (insert_item_table($link, $item_id, $new_name, $new_price, $new_stock, $new_status, $new_img) !== TRUE) {
                $err_msg[] = 'INSERT失敗';
            }
            if (count($err_msg) === 0) {
                $success_msg[] = '追加成功';
                mysqli_commit($link);
            } else {
                mysqli_rollback($link);
            }
        }
        
    } else if ($sql_kind === 'update') {
        $update_stock = get_post_data('update_stock');
        $item_id = get_post_data('id');
        $err_msg = check_stock($update_stock, $err_msg);
        
        if (count($err_msg) === 0) {
            if (update_stock($link, $item_id, $update_stock) === TRUE) {
                $success_msg[] = '在庫数変更成功';
            } else {
                $err_msg[] = '在庫数変更エラー';
            }
        }
            
    } else if($sql_kind === 'change') {
        $change_status = get_post_data('change_status');
        $item_id = get_post_data('id');
        $err_msg = check_status($change_status, $err_msg);
        
        if (count($err_msg) === 0) {
            if (change_status($link, $item_id, $change_status) === TRUE) {
                $success_msg[] = 'ステータス変更成功';
            } else {
                $err_msg[] = 'ステータス変更エラー';
            }
        }
    
    } else if($sql_kind === 'delete') {
        $item_id = get_post_data('id');
        $err_msg = check_item_id($item_id, $err_msg);
        
        if (count($err_msg) === 0) {
            if (delete_item($link, $item_id) === TRUE) {
                $success_msg[] = '商品削除成功';
            } else {
                $err_msg[] = '商品削除エラー';
            }
        }
    }
}

list($item_data, $err_msg) = get_item_data($link, $err_msg);
$item_data = entity_assoc_array($item_data);

close_db_connect($link);

include_once 'include/view/admin_item.php';
