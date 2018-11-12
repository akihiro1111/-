<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/model/yu_za_login_function.php';
    require_once '../include/model/yu_za_touroku_function.php';
    
    $err_msg     = array();
    $msg         = array();
    $data        = date('Y-m-d H:i:s');
    $user_name   = '';
    $passwd      = '';
    $user_jouhou = array();
    
    $request_method = request_method();
    $link = get_db_connect();
    if ($request_method === 'POST') {
        if (isset($_POST['touroku']) === TRUE) {
            //postデータ取得
            $user_name = get_post_data('user_name');
            $passwd = get_post_data('passwd');
            //エラーチェック
            user_name_check($user_name);
            password_check($passwd);
            //DBのユーザー名情報取得
            $user_jouhou = user_name_jouhou($link, $user_name);
            //同じユーザー名があるか
            user_name_kensaku($user_jouhou);
            //エラーがなければ
            if (count($err_msg) === 0) {
                //トランザクション
                mysqli_autocommit($link, false);
                //user_tableへユーザー名とパスワードをinsert
                user_table_insert($link, $user_name, $passwd);
                //トランザクション成否判定
                touroku_transaction_check($link);
            }
        }
        close_db_connect($link);
    }
    include '../include/view/yu_za_touroku_view.php';