<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ユーザー管理ページ</title>
        <style>
            table,tr,th,td {
                border: solid 1px;
                width: 800px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>ユーザー管理</h1>
        <a href="./logout.php">ログアウト</a>
        <a href="./shop_tool.php">商品管理ページ</a>
        <h2>ユーザ一覧</h2>
        <table>
            <tr>
                <th>ユーザーID</th>
                <th>登録日</th>
            </tr>
<?php foreach ($yu_za_jouhou_list as $list) { ?>
            <tr>
                <td><?php print $list['user_name']; ?></td>
                <td><?php print $list['created_date']; ?></td>
            </tr>
<?php } ?>
        </table>
    </body>
</html>