<?php
    //同じユーザ名があるか検索
    function user_name_kensaku($user_jouhou) {
        global $err_msg;
        if (count($user_jouhou) > 0) {
            $err_msg[] = '入力されたユーザ名は既に別の方が登録しています';
        }
    }
    //ユーザー名情報取得
    function user_name_jouhou($link, $user_name) {
        $sql = "SELECT user_name
                FROM user_table
                WHERE user_name = '$user_name'";
        return get_as_array($link, $sql);
    }
    //トランザクション成否判定
    function touroku_transaction_check($link) {
        global $err_msg,$msg;
        //トランザクション成否判定
        if (count($err_msg) === 0) {
            mysqli_commit($link);
            $msg[] = 'ユーザー登録完了しました';
        } else {
            mysqli_rollback($link);
        }
    }
    //user_tableへユーザー情報をinsert
    function user_table_insert($link, $user_name, $passwd) {
        global $err_msg, $data;
        $hash = crypt($passwd, $user_name);
        $sql = "INSERT INTO user_table(user_name,password,created_date)
                VALUES ('$user_name','$hash','$data')";
        if (mysqli_query($link, $sql) === TRUE) {
            $msg[] = 'ユーザー登録完了しました';
        } else {
            $err_msg[] = 'ユーザー登録失敗しました';
        }
    }
    //ユーザー入力エラーチェック
    function user_name_check($user_name) {
        global $err_msg;
        if (mb_strlen($user_name) === 0) {
            $err_msg[] = 'ユーザー名を入力してください';
        } else if (preg_match('/^([a-zA-Z0-9])+$/', $user_name) !== 1) {
            $err_msg[] = 'ユーザー名は半角英数字で入力してください';
        } else if (mb_strlen($user_name) < 6) {
            $err_msg[] = 'ユーザー名は六文字以上にしてください';
        }
    }
    //パスワードエラーチェック
    function password_check($passwd) {
        global $err_msg;
        if (mb_strlen($passwd) === 0) {
            $err_msg[] = 'パスワードを入力してください';
        } else if (preg_match('/^[a-zA-Z0-9]+$/', $passwd) !== 1) {
            $err_msg[] = 'パスワードは半角英数字で入力してください';
        } else if (mb_strlen($passwd) < 6) {
            $err_msg[] = 'パスワードは六文字以上にしてください';
        }
    }