<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品購入結果</title>
        <style>
            .main {
                width: 960px;
                margin: 0 auto;
            }
            .cart_price {
                margin-left: 580px;
            }
            .cart_stock {
                margin-left: 70px;
            }
            .goukei {
                margin-left: 460px;
            }
            .kingaku {
                color: #FF0000;
            }
            .image {
                margin: 10px;
                height: 200px;
                width: 200px;
            }
            a {
                text-decoration: none;
            }
            header {
                background: #FFD5EC;
                height: 130px;
            }
            h1 {
                text-align: center;
            }
            .logo {
                margin-left: 200px;
            }
            .cart {
                width: 50px;
                height: 50px;
                float: right;
            }
            .logout {
                float: right;
                margin: 10px;
            }
            .name {
                position: absolute;
                margin:10px;
            }
            .shouhin {
                margin: 0 auto;
                border-top: solid 1px;
                border-bottom: solid 1px;
                width: 960px;
            }
            .price {
                position: absolute;
                margin-left: 360px;
                color: #FF0000;
                margin-top: 10px;
            }
            .amount {
                position: absolute;
                margin-left: 480px;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <header>
            <a href="shop_shouhin.php">
                <img class="logo" src="https://estore.co.jp/wp-content/uploads/2014/04/iihana_com_img.png">
            </a>
            <a href="shop_cart.php">
                <img class="cart" src="https://www.lensoff.jp/img/wave/common/ico_05.png">
            </a>
            <a class="logout" href="./logout.php">ログアウト</a>
        </header>
        <div class="main">
        <h1>購入商品一覧</h1>
<?php if (count($cart_shouhin) > 0 && count($err_msg) === 0) { ?>
        <h2>ご購入ありがとうございました！</h2>
<?php } else if (count($cart_shouhin) === 0) { ?>
        <p style="color: red">商品がありません</p>
<?php } ?>
<?php if (count($err_msg) > 0) { ?>
    <?php foreach ($err_msg as $value) { ?>
            <p><?php print $value; ?></p>
    <?php } ?>
<?php } ?>
        <div class="box">
            <span class="cart_price">価格</span>
            <span class="cart_stock">数量</span>
        </div>
<?php if (count($err_msg) === 0) { ?>
    <?php foreach ($cart_shouhin as $value) { ?>
            <div class="shouhin">
                <img class="image" src="./img/<?php print $value['img']; ?>">
                <span class="name"><?php print $value['item_name']; ?></span>
                <span class="price">￥<?php print $value['price']; ?></span>
                <span class="amount"><?php print $value['amount']; ?></span>
            </div>
    <?php } ?>
<?php } ?>
            <span class="goukei">合計（税込）：</span>
            <span class="kingaku">￥<?php print $sum; ?></span>
        </div>
    </body>
</html>