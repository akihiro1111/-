<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ショッピングカート</title>
        <style>
            .body {
                width: 960px;
                margin: 0 auto;
            }
            h {
                margin-left: 100px;
            }
            a {
                text-decoration: none;
            }
            ul {
                list-style: none;
            }
            .shouhin {
                border-top: solid 1px;
                border-bottom: solid 1px;
                width: 960px;
            }
            .cart_price {
                margin-left: 700px;
            }
            .cart_stock {
                margin-left: 150px;
            }
            .form {
                margin-left: 550px;
                width: 50px;
                position: absolut
            }
            .cart_amount {
                margin-left: 350px;
                width: 140px;
            }
            p {
                margin-left: 600px;
            }
            .goukei {
                margin-left: 500px;
            }
            .kingaku {
                color: #FF0000;
                margin-left: 600px;
            }
            .buy {
                display: block;
                color: #ffffff;
                background-color: #FF8A00;
                width: 960px;
                height: 60px;
                border: none;
                font-size: 30px;
            }
        </style>
    </head>
    <body class="body">
        <h1>ショッピングカート</h1>
        <a href="./shop_shouhin.php">買い物を続ける</a>
        <a href="./logout.php">ログアウト</a>
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
        <div class="head">
            <span class="cart_price">価格</span>
            <span class="cart_stock">数量</span>
        </div>
<?php foreach ($cart_shouhin as $read) { ?>
        <ul>
            <li>
                <div class="shouhin">
                    <img src="./img/<?php print $read['img']; ?>">
                    <p><?php print $read['item_name']; ?></p>
                    <form  class="form" method="post">
                        <input type="submit" name="sakujo" value="削除">
                        <p class="kingaku">￥<?php print $read['price']; ?></p>
                        <div class="cart_amount">
                            <input type="text" name="amount" value="<?php print $read['amount']; ?>" size="5">個
                            <input type="submit" name="henkou" value="変更">
                        </div>
                        <input type="hidden" name="item_id" value="<?php print $read['item_id']; ?>">
                        <input type="hidden" name="status" value="<?php print $read['status']; ?>">
                    </form>
                </div>
            </li>
        </ul>
<?php } ?>
        <span class="goukei">合計（税込）</span>
        <span class="kingaku">￥<?php print $sum; ?></span>
        <form method="post" action="./shouhin_result.php">
            <input class="buy" type="submit" value="購入する">
        </form>
    </body>
</html>