<?php
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/model/shop_shouhin_function.php';
    require_once '../include/model/shop_cart_function.php';
    require_once '../include/model/shop_result_function.php';
    
    $data           = date('Y-m-d H:i:s');
    $err_msg        = array();
    $msg            = array();
    $cart_shouhin   = array();
    $request_method = request_method();
    $link           = get_db_connect();
    //セッション開始
    session_start();
    //ログインしてなかったらログインページへリダイレクト
    login_check();
    $user_id        = $_SESSION['user_id'];
    
    $link           = get_db_connect();
    $request_method = request_method();
    if ($request_method === 'POST') {
        //postデータ取得
        $sakujo  = get_post_data('sakujo');
        $henkou  = get_post_data('henkou');
        $amount  = get_post_data('amount');
        $item_id = get_post_data('item_id');
        $status  = get_post_data('status');
        //数量を変更したとき
        if ($henkou) {
            //数量の整数チェック
            amount_check($amount);
            //在庫チェック
            check_status_stock($cart_shouhin);
            if (count($err_msg) === 0) {
                //エラーがなければ変更処理
                cart_amount_henkou($link, $amount, $item_id, $user_id);
            }
        }
        //削除ボタンが押されたら
        if ($sakujo) {
            cart_shouhin_sakujo($link, $item_id, $user_id);
        }
    }
    //カートに入っている情報取得
    $cart_shouhin = cart_in_shouhin($link, $user_id);
    $cart_shouhin = entity_assoc_array($cart_shouhin);
    //合計金額
    $sum = cart_goukei_kingaku($cart_shouhin);
    
    include '../include/view/shop_cart_view.php';
?>