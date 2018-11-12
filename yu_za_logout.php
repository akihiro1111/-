<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    //セッション開始
    session_start();
    //セッション名取得
    $session_name = session_name();
    //セッション変数をすべて削除
    $_SESSION = array();
    //ユーザーのCookieに保存されているセッションIDを削除
    if (isset($_COOKIE[$session_name])) {
        //sessionに関連する設定を取得
        $params = session_get_cookie_params();
        
        setcookie($session_name, '', time() - 42000,
                  $params["path"], $params["domain"],
                  $params["secure"], $params["httponly"]);
    }
    //セッションIDを無効化
    session_destroy();
    //ログアウト処理したらログインページへ
    header('Location: http://codecamp21491.lesson8.codecamp.jp//yu_za_login.php');
    exit;