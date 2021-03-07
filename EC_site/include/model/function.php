<?php
/**
* insertを実行する
*
* @param obj $link DBハンドル
* @param str SQL文
* @return bool
*/
function insert_db($link, $sql) {
   // クエリを実行する
   if (mysqli_query($link, $sql) === TRUE) {
       return TRUE;
   } else {
       return FALSE;
   }
}

/**
* DBハンドルを取得
* @return obj $link DBハンドル
*/
function get_db_connect() {
    // コネクション取得
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }
    // 文字コードセット
    mysqli_set_charset($link, DB_CHARACTER_SET);
    return $link;
}

/**
* DBとのコネクション切断
* @param obj $link DBハンドル
*/
function close_db_connect($link) {
    // 接続を閉じる
    mysqli_close($link);
}

/**
* リクエストメソッドを取得
* @return str GET/POST/PUTなど
*/
function get_request_method() {
   return $_SERVER['REQUEST_METHOD'];
}

/**
* POSTデータを取得
* @param str $key 配列キー
* @return str POST値
*/
function get_post_data($key) {
    $str = '';
    if (isset($_POST[$key]) === TRUE) {
        $str = $_POST[$key];
    }
    return $str;
}

/**
* 特殊文字をHTMLエンティティに変換する
* @param str  $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}

/**
* クエリを実行しその結果を配列で取得する
*
*/
function get_as_array($link, $sql) {
    // 返却用配列
    $data = array();
    // クエリを実行する
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            // １件ずつ取り出す
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        // 結果セットを開放
        mysqli_free_result($result);
    } else {
        return FALSE;
    }
    return $data;
}

/*
*   registerページ
*/
function check_user_name($user_name, $err_msg) {
    if ($user_name === '') {
        $err_msg[] = 'IDは6文字以上の文字を入力してください';
    } else if (preg_match('/^[0-9a-zA-Z]+$/', $user_name) !== 1) {
        $err_msg[] = 'IDは6文字以上の文字を入力してください';
    } else if (mb_strlen($user_name) < 6) {
        $err_msg[] = 'IDは6文字以上の文字を入力してください';
    }
    return $err_msg;
}

function check_password($password, $err_msg) {
    if ($password === '') {
        $err_msg[] = 'PASSWORDは6文字以上の文字を入力してください';
    } else if (preg_match('/^[0-9a-zA-Z]+$/', $password) !== 1) {
        $err_msg[] = 'PASSWORDは6文字以上の文字を入力してください';
    } else if (mb_strlen($password) < 6) {
        $err_msg[] = 'PASSWORDは6文字以上の文字を入力してください';
    }
    return $err_msg;
}

function insert_user_table($link, $user_name, $password, $created_date) {
    $sql = "INSERT INTO ec_user_table(user_name, password, created_date) 
            VALUES('" .  $user_name . "','" . $password . "','" . $created_date . "')";
    $result = insert_db($link, $sql);
    return $result;
}

function get_user_name($link, $user_name) {
    $sql = 'SELECT user_name FROM ec_user_table WHERE user_name = "' . $user_name . '"';
    return get_as_array($link, $sql);
}

/*
*   login ページ
*/
function get_user_id($link, $user_name, $password) {
    $sql = 'SELECT id FROM ec_user_table WHERE user_name =\'' . $user_name . '\' AND password =\'' . $password . '\'';
    return get_as_array($link, $sql);
}

function check_login_user_name($user_name, $err_msg) {
    if (trim($user_name) === '') {
        $err_msg[] = 'ユーザー名を入力してください。';
    } 
    return $err_msg;
}

function check_login_password($password, $err_msg) {
    if (trim($password ) === '') {
        $err_msg[] = 'パスワードを入力してください。';
    } 
    return $err_msg;
}


/*
*   topページ
*/
function get_item_open_data($link) {
    $sql = 'SELECT ec_item_table.id, name, price, img, status, stock 
            FROM ec_item_table 
            JOIN ec_stock_table
            ON ec_item_table.id = ec_stock_table.item_id
            WHERE status = 1';
    return get_as_array($link, $sql);
}

function get_login_user_name($link, $user_id) {
    $sql = 'SELECT user_name FROM ec_user_table WHERE id = ' . $user_id;
    $data = get_as_array($link, $sql);
    return $data[0]['user_name'];
}

function insert_cart_table($link, $user_id, $item_id, $amount) {
    $sql = "INSERT INTO ec_cart_table(user_id, item_id, amount, created_date) 
            VALUES('" .  $user_id . "'," . $item_id . ",'" . $amount . "', NOW())";
    $result = insert_db($link, $sql);
    return $result;
}

function update_cart_table($link, $user_id, $item_id, $amount) {
    $sql = "UPDATE ec_cart_table SET amount={$amount}
            WHERE user_id={$user_id} AND item_id={$item_id}";
    $result = insert_db($link, $sql);
    return $result;
}

/*
*   cartページ
*/
function get_cart_data($link, $user_id, $item_id=null) {
    $sql = 'SELECT ec_cart_table.id, ec_cart_table.item_id, ec_item_table.name, 
            ec_item_table.price, ec_item_table.img, ec_cart_table.amount, ec_stock_table.stock, ec_item_table.status 
            FROM ec_cart_table 
            JOIN ec_item_table ON ec_cart_table.item_id = ec_item_table.id 
            JOIN ec_stock_table ON ec_cart_table.item_id = ec_stock_table.item_id
            WHERE ec_cart_table.user_id = ' . $user_id;
    if ($item_id !== null) {
        $sql .= ' AND ec_cart_table.item_id = ' . $item_id;
    }
    return get_as_array($link, $sql);
}

function check_amount($amount, $err_msg) {
    if (trim($amount) === '') {
        $err_msg[] = '個数を入力してください';
    } else if (preg_match('/^[1-9][0-9]*$/', $amount) !== 1) {
        $err_msg[] = '個数は1以上の半角数字を入力してください';
    }
    return $err_msg;
}

function delete_cart_item($link, $cart_id) {
    $sql = 'DELETE FROM ec_cart_table WHERE id = ' . $cart_id;
    $result = insert_db($link, $sql);
    return $result;
}

function update_new_amount($link, $cart_id, $amount) {
    $sql = 'UPDATE ec_cart_table SET amount = ' . $amount . ', updated_date = NOW() WHERE id = ' . $cart_id;
    return insert_db($link, $sql);
}

function amount_price($cart_data) {
    $sum = 0;
    $i = 0;
    $cart_row_num = count($cart_data); 
    while ($i < $cart_row_num) {
        $item_price = $cart_data[$i]['price'] * $cart_data[$i]['amount'];
        $sum += $item_price;
        $i++;
    }
    return $sum;
}

/*
*   check_out ページ
*/
function purchase ($link, $user_id, $cart_data, $err_msg) {
    foreach($cart_data as $value) {
        if ($value['status'] !== '1') {
            $err_msg[] = $value['name'] . 'は販売していません';
        } else if($value['stock'] < 1) {
            $err_msg[] = $value['name'] . 'は在庫がありません';
        } else if($value['stock'] < $value['amount']) {
            $err_msg[] = $value['name'] . 'は在庫が' . $value['stock'] . '個しかありません';
        }
    }
    if (count($err_msg) === 0) {
        mysqli_autocommit($link,FALSE);
        foreach($cart_data as $value) {
            $sql = 'UPDATE ec_stock_table SET stock = stock - ' . $value['amount'] . ', updated_date = NOW() WHERE item_id = '.$value['item_id'];
            $result = mysqli_query($link,$sql);
            if ($result === FALSE) {
                $err_msg[] = '購入に失敗しました'; 
            }
        }
        if (count($err_msg) === 0) {
            $sql = 'DELETE FROM ec_cart_table WHERE user_id = ' . $user_id;
            $result = mysqli_query($link,$sql);
            if ($result === FALSE) {
                $err_msg[] = '購入に失敗しました delete cart_table error' . $sql;
            }
        }
        if (count($err_msg) === 0) {
            mysqli_commit($link);
        } else {
            mysqli_rollback($link);
        }
    }
    return $err_msg;
}


/*
*   商品管理ページ
*/
function get_item_data($link, $err_msg) {
    $sql = 'SELECT ec_item_table.id, name, price, img, status, stock 
            FROM ec_item_table 
            JOIN ec_stock_table
            ON ec_item_table.id = ec_stock_table.item_id';
   
    $item_data = get_as_array($link, $sql);
    if ($item_data === FALSE) {
        $err_msg[] = '商品の取得失敗';
        $item_data = [];   
    }
    return [$item_data, $err_msg];
}

function check_name($new_name, $err_msg) {
    if (trim($new_name) === '') {
        $err_msg[] = '商品名がありません';
    } 
    return $err_msg;
}

function check_price($new_price, $err_msg) {
    if (trim($new_price) === '') {
        $err_msg[] = '値段を入力してください';
    } else if (preg_match('/^[0-9]+$/', $new_price) !== 1) {
        $err_msg[] = '値段は０以上の半角数字を入力してください';
    }
    return $err_msg;
}

function check_stock($new_stock, $err_msg) {
    if (trim($new_stock) === '') {
        $err_msg[] = '個数を入力してください';
    } else if (preg_match('/^[0-9]+$/', $new_stock) !== 1) {
        $err_msg[] = '個数は０以上の半角数字を入力してください';
    }
    return $err_msg;
}

function check_status($status, $err_msg) {
    if ($status !== '0' && $status !== '1') {
        $err_msg[] = 'ステータスの表記がないです';
    }
    return $err_msg;
}

function check_item_id($item_id, $err_msg) {
    if (preg_match('/^[0-9]+$/', $item_id) !== 1) {
        $err_msg[] = 'item_idが不明です';
    } 
    return $err_msg;
}

function file_upload($err_msg) {
    $new_img = '';
    $tmp_file = $_FILES['new_img']['tmp_name'];
    $file_type = $_FILES['new_img']['type'];   
    $file_ext = strtolower(end(explode('.',$_FILES['new_img']['name'])));
    $extension = array('jpeg', 'jpg', 'png');
        
    if (is_uploaded_file($tmp_file) !== TRUE) {
        $err_msg[] = 'ファイルが選択されていません';
    } else if (in_array($file_ext, $extension) !== TRUE) {
        $err_msg[] = '画像ファイルはJPEG又はPNGのみ利用可能です';
    } else if (is_uploaded_file($tmp_file)) {
        $new_img = sha1(uniqid(mt_rand(), TRUE)) . '.' . $file_ext;
        if (move_uploaded_file($tmp_file, 'images/uploaded_img/' . $new_img) !== TRUE) {
            $err_msg[] = 'ファイルがアップロードできません';
        }
    }
    return [$new_img, $err_msg];
}

function insert_item_table($link, $item_id, $new_name, $new_price, $new_stock, $new_status, $new_img) {
    mysqli_autocommit($link, FALSE);
    $sql = "INSERT INTO ec_item_table(name, price, img, status, created_date) 
            VALUES('" .  $new_name . "'," . $new_price . ",'" . $new_img . "','" . $new_status . "', NOW())";
   
    $result = insert_db($link, $sql);
    if ($result !== FALSE ) {
        $item_id = mysqli_insert_id($link);
        $sql = 'INSERT INTO ec_stock_table(item_id, stock, created_date) 
                VALUES (' . $item_id . ',' . $new_stock . ', NOW())';
        $result = insert_db($link, $sql);
    }
    if ($result === TRUE) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
    return $result;
}

function update_stock($link, $item_id, $update_stock) {
    $sql = 'UPDATE ec_stock_table SET stock = ' . $update_stock . ', updated_date = NOW() WHERE item_id = ' . $item_id;
    return insert_db($link, $sql);
}

function change_status($link, $item_id, $change_status) {
    $sql = 'UPDATE ec_item_table SET status = ' . $change_status . ', updated_date = NOW() WHERE id = ' . $item_id;
    return insert_db($link, $sql);
}

function delete_item($link, $item_id) {
    mysqli_autocommit($link, FALSE);
    $sql = 'DELETE FROM ec_item_table WHERE id = ' . $item_id;
    $result = insert_db($link, $sql);
    if ($result !== FALSE ) {
        $sql = 'DELETE FROM ec_stock_table WHERE item_id = ' .$item_id;
        $result = insert_db($link, $sql);
    }
    if ($result === TRUE) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
    return $result;
}

/*
*   user 管理ページ
*/
function get_user_data($link) {
    $sql = 'SELECT user_name, created_date FROM ec_user_table';
    return get_as_array($link, $sql);
}
