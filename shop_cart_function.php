<?php
    //カートに入っている商品情報取得
    function cart_in_shouhin($link, $user_id) {
        $sql = "SELECT cart_id,user_id,amount,item_table.item_id,item_name,price,img,status,stock
                FROM cart_table
                JOIN item_table
                ON cart_table.item_id = item_table.item_id
                JOIN stock_table
                ON stock_table.item_id = item_table.item_id
                WHERE user_id =" . $user_id;
        return get_as_array($link, $sql);
    }
    //カートの商品削除処理
    function cart_shouhin_sakujo($link, $item_id, $user_id) {
        global $msg, $err_msg;
        $sql = "DELETE FROM cart_table
                WHERE item_id = '$item_id'
                AND user_id =" . $user_id;
        if (mysqli_query($link, $sql) !== TRUE) {
            $err_msg[] = 'カートの商品削除失敗';
        }
    }
    //カートの数量変更処理
    function cart_amount_henkou($link, $amount, $item_id, $user_id) {
        global $err_msg, $msg, $data;
        $sql = "UPDATE cart_table
                SET amount = '$amount',update_date = '$data'
                WHERE item_id = '$item_id'
                AND user_id =" . $user_id;
        if (mysqli_query($link, $sql) === TRUE) {
            $msg[] = '購入予定商品数量変更しました';
        } else {
            $err_msg[] = '数量変更失敗';
        }
    }
    //合計金額計算
    function cart_goukei_kingaku($cart_shouhin) {
        $sum = 0;
        for ($i=0;$i<count($cart_shouhin);$i++) {
            $sum += ($cart_shouhin[$i]['price'] * TAX) * $cart_shouhin[$i]['amount'];
            $sum = entity_str($sum);
        }
        return $sum;
    }
    //amountチェック
    function amount_check($amount) {
        global $err_msg;
        if (preg_match('/^[1-9][0-9]*$/', $amount) !== 1) {
            $err_msg[] = '数量が正しくありません';
        }
    }