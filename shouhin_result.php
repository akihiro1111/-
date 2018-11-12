<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/model/shop_shouhin_function.php';
    require_once '../include/model/shop_cart_function.php';
    require_once '../include/model/shop_result_function.php';
    
    $err_msg = array();
    $msg     = array();
    $sum     = 0;
    $amount  = '';
    $price   = '';
    $item_id = '';
    $data    = date('Y-m-d H:i:s');
    $cart_shouhin = array();
    //セッション開始
    session_start();
    //ログインチェック
    login_check();
    
    $user_id        = $_SESSION['user_id'];
    $request_method = request_method();
    $link           = get_db_connect();
    if ($request_method === 'POST') {
        //購入商品情報取得
        $cart_shouhin = cart_in_shouhin($link, $user_id);
        $cart_shouhin = entity_assoc_array($cart_shouhin);
        //ステータス,在庫数変更されていた時
        check_status_stock($cart_shouhin);
        //エラーがなければ
        if (count($err_msg) === 0) {
            //合計金額
            $sum = cart_goukei_kingaku($cart_shouhin);
            //トランザクション
            mysqli_autocommit($link, false);
            foreach ($cart_shouhin as $value) {
                $item_id   = $value['item_id'];
                $amount    = $value['amount'];
                //購入した商品の在庫数変更
                stock_table_update($link, $amount, $item_id);
                //購入したカートの商品をカートから削除
                cart_shouhin_sakujo($link, $item_id, $user_id);
            }
            if (count($err_msg) === 0) {
                //トランザクション確定
                mysqli_commit($link);
            } else {
                //処理取り消し
                mysqli_rollback($link);
            }
        }
    }
    
    include '../include/view/shouhin_result_view.php';