<?php
    require_once '../include/model/shop_tool_function.php';
    require_once '../include/conf/const.php';
    require_once '../include/model/shop_shouhin_function.php';
    
    $err_msg        = array();
    $msg            = array();
    $shop_item_list = array();
    $data           = date('Y-m-d H:i:s');
    $img_dir        = './img/';
    //セッション開始
    session_start();
    //ログインチェック
    login_check();
    
    $request_method = request_method();
    $link = get_db_connect();
    if ($request_method === 'POST') {
        //postデータ取得
        $tuika         = get_post_data('tuika');
        $henkou        = get_post_data('henkou');
        $koukai        = get_post_data('koukai');
        $sakujo        = get_post_data('sakujo');
        $item_name     = get_post_data('item_name');
        $price         = get_post_data('price');
        $stock         = get_post_data('stock');
        $status        = get_post_data('status');
        $item_id       = get_post_data('item_id');
        $update        = get_post_data('update');
        $chenge_status = get_post_data('chenge_status');
        //商品追加処理
        if ($tuika) {
            //エラーチェック
            item_name_check($item_name);
            price_check($price);
            stock_check($stock);
            status_check($status);
            //エラーがなければ
            if (count($err_msg) === 0) {
                $file = file_check();
                //トランザクション
                mysqli_autocommit($link, false);
                //商品情報insert
                tool_insert($link, $item_name, $price, $stock, $file, $status);
                //トランザクション成否判定
                tool_transaction_check($link);
            }
        }
        //在庫変更処理
        if ($henkou) {
            //エラーチェック
            update_check($update);
            //エラーがなければ
            if (count($err_msg) === 0) {
                //在庫数update
                zaiko_update($link, $update, $item_id);
            }
        }
        //ステータス変更処理
        if ($koukai) {
            //ステータスchenge
            chenge_status($link, $chenge_status, $status, $item_id);
        }
        //削除ボタン押された時
        if ($sakujo) {
            //DELETE処理
            shop_item_delete($link, $item_id);
        }
    }
    //商品情報一覧表示
    $shop_item_list = get_shop_item_jouhou($link);
    $shop_item_list = entity_assoc_array($shop_item_list);
    //DB切断
    close_db_connect($link);
    
    include '../include/view/shop_tool_view.php';