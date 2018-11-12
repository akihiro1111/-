<?php
    //ユーザー情報があるかどうかチェック
    function user_table_check($user_jouhou, $user_name, $user_id) {
        global $err_msg;
        if (count($user_jouhou) === 0) {
            $err_msg[] = 'ユーザー名、又はパスワードが違います';
        } else {
            $user_id = $user_jouhou[0]['user_id'];
            //ログイン日時処理
            user_login_date($link, $user_name);
            //DB切断
            close_db_connect($link);
            //セッションに保存
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            header('Location: http://codecamp21491.lesson8.codecamp.jp//shop_shouhin.php');
            exit;
        }
    }
    //管理用ユーザーログイン処理
    function kanri_yu_za_login($user_name, $passwd, $user_id) {
        if ($user_name === 'admin' && $passwd === 'admin') {
            //管理ページへリダイレクト
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            header('Location: http://codecamp21491.lesson8.codecamp.jp//shop_tool.php');
            exit;
        }
    }
    //ユーザー情報取得
    function user_table_select($link, $user_name, $passwd) {
        global $err_msg;
        $hash = crypt($passwd, $user_name);
        $data = array();
        $sql = "SELECT user_id,user_name,password
                FROM user_table
                WHERE user_name = '$user_name'
                AND password = '$hash'";
        return get_as_array($link, $sql);
    }
    //ユーザーログイン日時取得
    function user_login_date($link, $user_name) {
        global $err_msg, $data;
        $sql = "UPDATE user_table
                SET update_date = '$data'
                WHERE user_name = '$user_name'";
        if (mysqli_query($link, $sql) !== TRUE) {
            $err_msg[] = 'user_table: UPDATE失敗'. $sql;
        }
    }