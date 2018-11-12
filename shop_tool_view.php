<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品管理画面</title>
    </head>
    <style>
        h2 {
            border-top: solid 1px;
            padding: 10px;
        }
        table,tr,th,td {
            border: solid 1px;
            text-align: center;
        }
        img {
            height: 300px;
            width: 300px;
        }
    </style>
    <body>
        <h1>ショップ商品管理</h1>
        <a href="./logout.php">ログアウト</a>
        <a href="./yu_za_list.php">ユーザー管理ページ</a>
<?php if (count($err_msg) > 0) { ?>
    <?php foreach ($err_msg as $read) { ?>
        <p><?php print $read; ?></p>
    <?php } ?>
<?php } ?>
<?php if (count($err_msg) === 0) { ?>
    <?php foreach ($msg as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>
<?php } ?>
        <h2>新規商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            名前：<input type="text" name="item_name" value=""><br>
            値段：<input type="text" name="price" value=""><br>
            個数：<input type="text" name="stock" value=""><br>
            <input type="file" name="file"><br>
            <select name="status">
                <option value="0">非公開</option>
                <option value="1">公開</option>
            </select><br>
            <input type="submit" name="tuika" value="商品追加">
        </form>
        <h2>商品情報</h2>
        <table>
            <caption>商品一覧</caption>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
<?php foreach ($shop_item_list as $item_jouhou) { ?>
            <tr>
                <td><img src="./img/<?php print $item_jouhou['img']; ?>"></td>
                <td><?php print $item_jouhou['item_name']; ?></td>
                <td><?php print $item_jouhou['price']; ?></td>
                <td>
                    <form method="post">
                        <input type="text" name="update" value="<?php print $item_jouhou['stock']; ?>" size="10">
                        <input type="hidden" name="item_id" value="<?php print $item_jouhou['item_id']; ?>">
                        <input type="submit" name="henkou" value="変更">
                    </form>
                </td>
                <td>
                    <form method="post">
<?php if ((int)$item_jouhou['status'] === 1) { ?>
                        <input type="submit" name="koukai" value="公開→非公開">
<?php } ?>
<?php if ((int)$item_jouhou['status'] === 0) { ?>
                        <input type="submit" name="koukai" value="非公開→公開">
<?php } ?>
                        <input type="hidden" name="chenge_status" value="<?php print $item_jouhou['status']; ?>">
                        <input type="hidden" name="item_id" value="<?php print $item_jouhou['item_id']; ?>">
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="submit" name="sakujo" value="削除">
                        <input type="hidden" name="item_id" value="<?php print $item_jouhou['item_id']; ?>">
                    </form>
                </td>
            </tr>
<?php } ?>
        </table>
    </body>
</html>