<?php
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'image')->widget(FileInput::classname(), [
'options' => ['accept' => 'image/*'],
])?>

<button>Submit</button>

<?php ActiveForm::end() ?>
