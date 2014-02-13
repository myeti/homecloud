<!doctype html>
<html>
<head>
    <title>Your App</title>
    <?= self::meta(); ?>
    <?= self::css('public/css/layout', 'public/css/content'); ?>
    <?= self::js('public/js/jquery-2.1.0.min', 'public/js/main'); ?>
</head>
<body>

    <?= self::content(); // display here the template's content ?>

</body>
</html>