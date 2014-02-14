<?php self::layout('views/_layout') ?>
<?= self::css('public/css/login') ?>

<div id="login">

    <h1>HomeCloud /</h1>

    <div class="panel panel-default">
        <div class="panel-body">

            <form action="<?= url('/login') ?>" method="post">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <button type="submit" class="btn btn-primary">Enter</button>

            </form>

        </div>
    </div>

</div>