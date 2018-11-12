<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/model/shop_shouhin_function.php';
    
    $shouhin_list = array();
    $err_msg      = array();
    $msg          = array();
    $data         = date('Y-m-d H:i:s');
    //セッション開始
    session_start();
    //ログインしていなかったらログインページへリダイレクト
    login_check();
    //ユーザーID取得
    $user_id = $_SESSION['user_id'];
    //データベース接続
    $link = get_db_connect();
    //商品情報取得
    $shouhin_list = shouhin_jouhou($link);
    $shouhin_list = entity_assoc_array($shouhin_list);
    
    $request_method = request_method();
    if ($request_method === 'POST') {
        //postデータ取得
        $cart_in = get_post_data('cart_in');
        $item_id = get_post_data('item_id');
        //カートに入れるを押したら
        if ($cart_in) {
            //情報取得
            $cart_tuika_shouhin = cart_tuika_shouhin($link, $item_id, $user_id);
            //カートに入れられるかどうか（在庫とステータスチェック）
            cart_in_check_status_stock($cart_tuika_shouhin);
            //エラーがなければ
            if (count($err_msg) === 0) {
                update_or_insert($link, $user_id, $item_id, $cart_tuika_shouhin);
            }
        }
        close_db_connect($link);
    }
    
    include '../include/view/shop_shouhin_view.php';