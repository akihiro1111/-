<?php
    //status,stock check
    function check_status_stock($cart_shouhin) {
        global $err_msg;
        foreach ($cart_shouhin as $value) {
            $item_name = $value['item_name'];
            $stock     = $value['stock'];
            $status    = $value['status'];
            $amount    = $value['amount'];
            if ((int)$status !== 1) {
                $item_name = entity_str($item_name);
                $err_msg[] = $item_name . 'は販売中止のため購入できません';
            }
            if ((int)$amount > (int)$stock) {
                $err_msg[] = '申し訳ありません。商品の在庫が不足しています';
            }
        }
    }
    //購入した商品の在庫数変更
    function stock_table_update($link, $amount, $item_id) {
        global $err_msg, $data;
        $sql = "UPDATE stock_table
                SET stock = stock - '$amount',update_date = '$data'
                WHERE item_id =" . $item_id;
        if (mysqli_query($link, $sql) !== TRUE) {
            $err_msg[] = 'stock_table: update失敗';
        }
    }