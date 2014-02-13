<div class="sub-form">

    <?php foreach($form as $field): ?>

    <div class="line">
        <?= $field->html(); ?>
    </div>

    <?php endforeach; ?>

</div>