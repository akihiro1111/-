<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品一覧</title>
        <style>
            h1 {
                border-bottom: solid 1px;
            }
            a {
                text-decoration: none;
            }
            .shouhin {
                width: 150px;
                margin: 30px;
                float: left;
                text-align: center;"
            }
            .submit {
                clear: both;
                margin: 0 auto;
                display: block;
                background-color: #00A7F7;
                color: #ffffff;
                border: none;
            }
            .image {
                height: 130px;
            }
        </style>
    </head>
    <body>
        <h1>商品一覧</h1>
<?php foreach ($msg as $value) { ?>
        <p><?php print $value; ?></p>
<?php } ?>
<?php foreach ($err_msg as $value) { ?>
        <p><?php print $value; ?></p>
<?php } ?>
<?php foreach ($shouhin_list as $shouhin) { ?>
        <form method="post">
            <div class="shouhin">
                <img class="image" src="./img/<?php print $shouhin['img']; ?>">
                <p><?php print $shouhin['item_name']; ?></p>
                <p><?php print $shouhin['price']; ?>円</p>
    <?php if ($shouhin['stock'] >= 1) { ?>
                <input class="submit" type="submit" name="cart_in" value="カートに入れる">
    <?php } else { ?>
                <p style="color: red;">売り切れ</p>
    <?php } ?>
            </div>
             <input type="hidden" name="item_id" value="<?php print $shouhin['item_id']; ?>">
        </form>
<?php } ?>
        <a href="./shop_cart.php">カートページへ</a>
        <a href="./logout.php">ログアウト</a>
    </body>
</html>