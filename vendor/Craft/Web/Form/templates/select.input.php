<select name="<?= $field->name ?>" id="<?= $field->id ?>">

    <?php foreach($field->options as $value => $text): ?>

    <?php $checked = ($value == $field->value) ? 'checked' : null; ?>
    <option value="<?= $value ?>" <?= $checked ?>><?= $text ?></option>

    <?php endforeach; ?>

</select>