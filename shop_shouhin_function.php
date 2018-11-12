<?php
    //updateかinsertを処理する
    function update_or_insert($link, $user_id, $item_id ,$cart_tuika_shouhin) {
        if (count($cart_tuika_shouhin) > 0) {
            //同じ商品をカートに入れるをしたときにamountをupdate
                amount_update($link, $user_id, $item_id);
            } else {
            //カートに追加
            cart_table_insert($link, $user_id, $item_id);
        }
    }
    //カートに入れる時に在庫とステータスをチェック
    function cart_in_check_status_stock($cart_shouhin) {
        global $err_msg;
        foreach ($cart_shouhin as $value) {
            $item_name = $value['item_name'];
            $stock     = $value['stock'];
            $status    = $value['status'];
            $amount    = $value['amount'];
            if ((int)$status !== 1) {
                $err_msg[] = entity_str($item_name) . 'は販売中止のため購入できません';
            }
            if ((int)$amount+1 > (int)$stock) {
                $err_msg[] = '申し訳ありません。商品の在庫が不足しています';
            }
        }
    }
    //同じ商品をカートに入れた時amountをアップデート
    function amount_update($link, $user_id, $item_id) {
        global $err_msg, $msg, $data;
        $sql = "UPDATE cart_table
                SET amount = amount+1,update_date = '$data'
                WHERE user_id = '$user_id'
                AND item_id =" . $item_id;
        if (mysqli_query($link, $sql) === TRUE) {
            $msg[] = 'カートに追加しました';
        } else {
            $err_msg[] = 'cart_table_amount_update: エラー' . $sql;
        }
    }
    //cart_tableへinsert
    function cart_table_insert($link, $user_id, $item_id) {
        global $err_msg, $msg, $data;
        $amount = 0;
        //cart_id A/I取得
        $cart_id = mysqli_insert_id($link);
        $sql = "INSERT INTO cart_table(cart_id,user_id,item_id,amount,created_date)
                VALUES ('$cart_id','$user_id','$item_id',1,'$data')";
        if (mysqli_query($link, $sql) === TRUE) {
            $msg[] = 'カートに追加しました';
        } else {
            $err_msg[] = 'カートに追加出来ませんでした';
        }
    }
    //カートに追加された商品情報取得
    function cart_tuika_shouhin($link, $item_id, $user_id) {
        $sql = "SELECT item_table.item_id,item_name,price,status,stock_table.stock,amount
                FROM item_table
                JOIN stock_table
                ON stock_table.item_id = item_table.item_id
                JOIN cart_table
                ON cart_table.item_id = item_table.item_id
                WHERE cart_table.item_id = '$item_id'
                AND cart_table.user_id =" . $user_id;
        return get_as_array($link, $sql);
    }
    //商品情報取得
    function shouhin_jouhou($link) {
        $sql = 'SELECT item_table.item_id,item_name,price,img,stock_table.stock
                FROM item_table
                JOIN stock_table
                ON stock_table.item_id = item_table.item_id
                WHERE status = 1';
        return get_as_array($link, $sql);
    }
    //ログインしてなかったら
    function login_check() {
        if (isset($_SESSION['user_id']) !== TRUE) {
        header ('Location: http://codecamp21491.lesson8.codecamp.jp//yu_za_login.php');
        }
    }