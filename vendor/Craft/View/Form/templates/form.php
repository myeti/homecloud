<form action="<?= $form->url ?>" method="<?= $form->method ?>">

    <?php foreach($form as $field): ?>

    <div class="line">
        <?= $field->html(); ?>
    </div>

    <?php endforeach; ?>

    <div class="line">
        <button type="submit">Submit</button>
    </div>

</form>