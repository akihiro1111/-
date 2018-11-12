<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/model/shop_shouhin_function.php';
    
    session_start();
    //ログインチェック
    login_check();
    
    $yu_za_jouhou_list = array();
    //データ接続
    $link = get_db_connect();
    //ユーザーデータ取得
    $sql = 'SELECT user_name,created_date
            FROM user_table';
    $yu_za_jouhou_list = get_as_array($link, $sql);
    $yu_za_jouhou_list = entity_assoc_array($yu_za_jouhou_list);
    //データベース切断
    close_db_connect($link);
    include '../include/view/yu_za_list_view.php';