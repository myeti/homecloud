<?php self::layout('views/layout') ?>

<div class="form login-form">
    <form action="/auth/login" method="post">

        <h1>Login</h1>

        <div class="line">
            <input type="text" name="username" placeholder="username" />
        </div>

        <div class="line">
            <input type="password" name="password" placeholder="password" />
        </div>

        <div class="line">
            <button type="submit">Go</button>
        </div>

    </form>
</div>