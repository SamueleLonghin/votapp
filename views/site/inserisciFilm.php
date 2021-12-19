<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\models\LoginForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\file\FileInput;
use kartik\widgets\SwitchInput;

$this->title = 'Inserisci Film';
?>


<!--<script>-->
<!--    function uploadPhotos($url) {-->
<!--        alert("ciao");-->
<!--    }-->
<!--</script>-->

<div>
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'inserisi-film',
        'layout' => 'horizontal',
        //'options' => ['enctype' => 'multipart/form-data'],
        //'enableAjaxValidation' => true,
        //'validationUrl' => Url::toRoute('ajaxfilmvalidation')
    ]); ?>

    <?= $form->field($model, 'Id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'Titolo') ?>
    <?= $form->field($model, 'Autore') ?>
    <?= $form->field($model, 'Ordine') ?>
    <?= $form->field($model, 'Durata') ?>


    <?= $form->field($model, 'Descrizione')->textarea() ?>



    <?= $form->field($model, 'FileImmagine')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'onchange' => "uploadPhotos('#{imageUploadUrl}')",
        ],
        'pluginOptions' => [
            'initialPreview' => $model->Image,
            'showPreview' => true,
            'initialPreviewAsData' => true,
            'initialPreviewConfig' =>[
                ['size' => '044'],]
        ],
        'resizeImages' => true,

    ]) ?>




    <?= $form->field($model, 'IsFilm')->widget(SwitchInput::classname(), [
        'value' => 1,
        'pluginOptions' => [
            'handleWidth' => 100,
            'onText' => 'Film',
            'offText' => 'Altro Evento',
        ],'containerOptions' => [
            'class' => false,
        ]
    ]) ?>
    <?= $form->field($model, 'IsVisible')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'handleWidth' => 100,
            'onText' => '<span class="glyphicon glyphicon-eye-open"></span>',
            'offText' => '<span class="glyphicon glyphicon-eye-close"></span>',
        ],'containerOptions' => [
            'class' => false,
        ]    ]) ?>


    <!--        --><?php //echo
    //         $form->field($model, 'Proiezione')->widget(DateTimePicker::classname(), [
    //                'options' => ['placeholder' => 'Enter event time ...'],
    //                'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
    //                'pluginOptions' => [
    //                    'autoclose' => true,
    //                    'format' => 'yyyy-mm-dd hh:ii',
    //                ]
    //            ]
    //        )
    //        echo $form->field($model, 'Fine')->widget(DateTimePicker::classname(), [
    //                'options' => ['placeholder' => 'Enter event time ...'],
    //                'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
    //                'pluginOptions' => [
    //                    'autoclose' => true,
    //                    'format' => 'yyyy-mm-dd hh:ii',
    //                ],
    //            ]
    //        ) ?>




    <?= $form->field($model, 'IdEvento')->widget(Select2::classname(), [
        'data' => $eventi,
        'options' => ['placeholder' => 'Select an Event'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton($model->Id == -1 ? 'Inserisci' : 'Salva modifiche', ['class' => 'btn btn-votapp', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

