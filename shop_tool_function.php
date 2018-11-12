<?php
    //HTMLエンティティに変換
    function entity_str($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    //HTMLエンティティに変換(2次元配列)
    function entity_assoc_array($assoc_array) {
        foreach ($assoc_array as $key => $value) {
            foreach ($value as $keys => $values) {
                $assoc_array[$key][$keys] = entity_str($values);
            }
        }
        return $assoc_array;
    }
    //配列でアイテム情報取得
    function get_as_array($link, $sql) {
        $data = array();
        //クエリ実行
        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            //セット開放
            mysqli_free_result($result);
        }
        return $data;
    }
    //商品情報取得
    function get_shop_item_jouhou($link) {
        $sql = 'SELECT item_table.item_id,item_name,price,img,stock,status
                FROM item_table
                JOIN stock_table
                ON stock_table.item_id = item_table.item_id';
        return get_as_array($link, $sql);
    }
    //status変更処理
    function chenge_status($link, $chenge_status, $status, $item_id) {
        global $err_msg, $msg, $data;
        if ((int)$chenge_status === 1) {
            $sql = "UPDATE item_table
                    SET status = 0
                    WHERE item_id =" . $item_id;
            if (mysqli_query($link,$sql) === TRUE) {
                $msg[] = 'ステータス非公開に変更しました';
            }
        } else if ((int)$chenge_status === 0) {
            $query = "UPDATE item_table
                      SET status = 1
                      WHERE item_id =" . $item_id;
            if (mysqli_query($link,$query) === TRUE) {
                $msg[] = 'ステータス公開に変更しました';
            }
        } else {
            $err_msg[] = 'ステータス変更失敗しました';
        }
        $Query = "UPDATE item_table
                  SET update_date = '$data'
                  WHERE item_id =" . $item_id;
        if (mysqli_query($link, $Query) !== TRUE) {
            $err_msg[] = 'item_table: updateエラー' . $Query;
        }
    }
    //削除ボタンが押された時の処理
    function shop_item_delete($link, $item_id) {
        global $err_msg, $msg;
        $sql = 'DELETE FROM stock_table 
                WHERE item_id =' . $item_id;
        if (mysqli_query($link, $sql) === TRUE) {
            $query = 'DELETE FROM item_table
                WHERE item_id =' . $item_id;
                if (mysqli_query($link, $query) !== TRUE) {
                    $err_msg[] = 'item_table:商品削除失敗';
                }
            $msg[] = '商品削除成功';
        } else {
            $err_msg[] = 'stock_table:商品削除失敗';
        }
    }
    //在庫数変更アップデート
    function zaiko_update($link, $update, $item_id) {
        global $err_msg, $msg, $data;
        $sql = "UPDATE stock_table SET stock = '$update' WHERE item_id =" . $item_id;
        if (mysqli_query($link, $sql) === TRUE) {
            $msg[] = '在庫数変更成功しました';
        } else {
            $err_msg[] = '在庫数変更失敗しました';
        }
        //在庫数変更したときに日時更新
        $query = "UPDATE stock_table
                  SET update_date = '$data'
                  WHERE item_id=" . $item_id;
        if (mysqli_query($link, $query) !== TRUE) {
            $err_msg[] = 'stock_table: updateエラー' . $query;
        }
    }
    //update check
    function update_check($update) {
        global $err_msg;
        if (preg_match('/^(0|[1-9][0-9]*)$/', $update) !== 1) {
            $err_msg[] = '数値を正しく入力してください';
        }
    }
    //トランザクション成否判定
    function tool_transaction_check($link) {
        global $err_msg,$msg;
        //トランザクション成否判定
        if (count($err_msg) === 0) {
            mysqli_commit($link);
            $msg[] = '商品が追加されました';
        } else {
            mysqli_rollback($link);
            $err_msg[] = '商品追加失敗しました';
        }
    }
    //商品情報追加
    function tool_insert($link, $item_name, $price, $stock, $file, $status) {
        global $err_msg, $msg, $data;
        //商品の名前、値段、個数、ステータス追加
        $query = "INSERT INTO item_table(item_name,price,img,status,created_date)
                  VALUES('$item_name','$price','$file','$status','$data')";
        //insert実行
        if (mysqli_query($link,$query) === TRUE) {
            //item_tableからitem_idのA/Iを取得
            $stock_id = mysqli_insert_id($link);
            $Query = "INSERT INTO stock_table(item_id,stock,created_date)
                      VALUES('$stock_id','$stock','$data')";
            if (mysqli_query($link, $Query) !== TRUE) {
               $err_msg[] = 'stock_table:insertエラー' . $Query;
            }
        } else {
            $err_msg[] = 'item_table:insertエラー' . $query;
        }
    }
    //status check
    function status_check($status) {
        global $err_msg;
        if (preg_match('/^[01]$/', $status) !== 1) {
            $err_msg[] = 'ステータスが正しくありません';
        }
    }
    //file check
    function file_check() {
        global $err_msg, $img_dir;
        $file = '';
        //  HTTP POST でファイルがアップロードされたか確認
       if (is_uploaded_file($_FILES['file']['tmp_name']) === TRUE) {
            //ファイルサイズ取得
            $file_size = $_FILES['file']['size'];
            //ファイルサイズチェック
            if ($file_size > 2097152 || $file_size === 0) {
                $err_msg[] = 'ファイルサイズを確認してください';
            }
            //エラーがなければ
            if (count($err_msg) ===0) {
                // 画像の拡張子取得
                $type = @exif_imagetype($_FILES['file']['tmp_name']);
                $extension = '';
                if ($type === IMAGETYPE_JPEG) {
                    $extension = 'jpeg';
                } else if ($type === IMAGETYPE_PNG) {
                    $extension = 'png';
                }
                // 拡張子チェック
                if ($extension == 'jpeg' || $extension == 'png') {
                    // ランダムな文字列を生成し保存ファイルの名前を変更
                    $file = md5(uniqid(mt_rand(), true)) . '.' . $extension;
                    // 同名ファイルが存在するか確認
                    if (is_file($img_dir . $file) !== TRUE) {
                        // ファイルを移動し保存
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $img_dir . $file) !== TRUE) {
                            $err_msg[] = 'ファイルアップロードに失敗しました';
                        }
                    } else {
                        $err_msg[] = 'ファイルアップロードに失敗しました。';
                    }
                } else {
                    $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です。';
                }
            }
        } else {
            $err_msg[] = 'ファイルを選択してください';
        }
        return $file;
    }
    //stock check
    function stock_check($stock) {
        global $err_msg;
        if (mb_strlen($stock) === 0) {
            $err_msg[] = '個数を入力してください';
        } else if (preg_match('/^(0|[1-9][0-9]*)$/', $stock) !== 1) {
            $err_msg[] = '個数を正しく入力してください';
        }
    }
    //price check
    function price_check($price) {
        global $err_msg;
        if (mb_strlen($price) === 0) {
            $err_msg[] = '値段を入力してください';
        } else if (preg_match('/^(0|[1-9][0-9]*)$/', $price) !== 1) {
            $err_msg[] = '値段は半角数字を入力してください';
        }
    }
    //drink_name check
    function item_name_check($item_name) {
        global $err_msg;
        if ($item_name === '') {
            $err_msg[] = '商品名を入力してください';
        } else if (mb_ereg_match(('^(\s|　)+$'), $item_name) === TRUE) {
            $err_msg[] = '商品名は半角、全角スペースなしで入力してください';
        }
    }
    /* リクエストメソッドを取得
    * @return str GET/POST/PUTなど
    */
    function request_method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    /**
    * POSTデータを取得
    * @param str $key 配列キー
    * @return str POST値
    */
    function get_post_data($key) {
        $str = '';
        if (isset($_POST[$key]) === TRUE) {
            $str = $_POST[$key];
        }
        return $str;
    }
    function get_db_connect() {
        // コネクション取得
        if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
            die('error: ' . mysqli_connect_error());
        }
        // 文字コードセット
        mysqli_set_charset($link, 'utf8');
        return $link;
    }
    /**
    * DBとのコネクション切断
    * @param obj $link DBハンドル
    */
    function close_db_connect($link) {
        // 接続を閉じる
        mysqli_close($link);
    }