<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/model/yu_za_touroku_function.php';
    require_once '../include/model/yu_za_login_function.php';
    
    $err_msg     = array();
    $data        = date('Y-m-d H:i:s');
    $user_name   = '';
    $passwd      = '';
    $user_id     = '';
    $user_jouhou = array();
    //セッション開始
    session_start();
    
    $request_method = request_method();
    $link = get_db_connect();
    if ($request_method === 'POST') {
        //ログインボタン押されたら処理開始
        if (isset($_POST['login']) === TRUE) {
            //postデータ取得
            $user_name = get_post_data('user_name');
            $passwd = get_post_data('passwd');
            //管理用ユーザーでログインした場合
            kanri_yu_za_login($user_name, $passwd, $user_id);
            //エラーチェック
            user_name_check($user_name);
            password_check($passwd);
            //エラーがなければ
            if (count($err_msg) === 0) {
                //user_tableに登録してあるユーザーデータ取得
                $user_jouhou = user_table_select($link, $user_name, $passwd);
                //user_tableに登録してあればログイン
                user_table_check($user_jouhou, $user_name, $user_id);
            }
        }
    }
    //セッション変数からログイン済みか確認
    if (isset($_SESSION['user_id']) === TRUE) {
        //ログイン済みの場合ショップへリダイレクト
        header('Location: http://codecamp21491.lesson8.codecamp.jp//shop_shouhin.php');
        exit;
    }
    include '../include/view/yu_za_login_view.php';