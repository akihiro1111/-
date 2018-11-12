<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログインページ</title>
        <style>
            h1 {
                border-bottom: solid 1px;
                text-align: center;
            }
            form {
                text-align: center;
            }
            .pass,.login,.idou {
                margin: 10px;
            }
        </style>
    </head>
    <body>
        <h1>ログインページ</h1>
        <form method="post">
<?php if (count($err_msg) > 0) { ?>
    <?php foreach ($err_msg as $value) { ?>
            <p><?php print $value; ?></p>
    <?php } ?>
<?php } ?>
            ユーザー名:<input type="text" name="user_name" value="">
            <div class="pass">
                パスワード:<input type="password" name="passwd" value="">
                <div class="login">
                    <input type="submit" name="login" value="ログイン">
                    <div class="idou">
                        <a href="./yu_za_touroku.php">ユーザーの新規登録</a>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>