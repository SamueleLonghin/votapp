<?php


use app\models\Evento;
use app\models\Film;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use app\models\LoginForm;
//use yii\bootstrap\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
use kartik\widgets\SwitchInput;
//use kartik\widgets\ColorInput;
use kartik\color\ColorInput;
use yii\helpers\Url;


YiiAsset::register($this);


$form = ActiveForm::begin([
    'id' => 'inserisi-film',
//    'layout' => 'horizontal',
    'type' => ActiveForm::TYPE_HORIZONTAL,
//    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute('ajaxvalidaevento')

]); ?>
<div class="form-group">

    <?= $form->field($model, 'Id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'Nome') ?>
    <?= $form->field($model, 'Descrizione') ?>
    <script>
        function GeneraCodice() {
            var codiceForse = 10000 + Math.floor(Math.random() * 89999);

            $.get("index.php?r=site%2Fesistecodiceevento&c=" + codiceForse, function (data, status) {
                if (data > 0)
                    GeneraCodice();
                else
                    document.getElementById('evento-codice').value = codiceForse;
            });
        }
    </script>
    <?=
    $form->field($model, 'Codice', [

        'addon' => [
            'append' => ['content' => '<a class="btn btn-votapp" onclick="GeneraCodice()">Genera</a>', 'asButton' => true],

        ]

    ]);

    ?>

    <?= $form->field($model, 'Inizio')->widget(
        DateTimePicker::classname(),
        [
            'options' => ['placeholder' => 'Seleziona il giorno e l\'ora d\'inizio'],
            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii',
            ]
        ]
    ) ?>
    <?= $form->field($model, 'Fine')->widget(
        DateTimePicker::classname(),
        [
            'options' => ['placeholder' => 'Seleziona il giorno e l\'ora di fine'],
            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii',
            ]
        ]
    ) ?>
    <?= $form->field($model, 'Posizione')->widget(Select2::classname(), [
        'data' => $locations,
        'options' => ['placeholder' => 'Seleziona la Location'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <?= $form->field($model, 'Visibilita')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
//            'handleWidth' => 100,
            'indeterminateToggle' => ['label'=>'&lt;i class="glyphicon glyphicon-remove-sign">&lt;/i>'],
            'onText' => '<span class="glyphicon glyphicon-eye-open"></span>',
            'offText' => '<span class="glyphicon glyphicon-eye-close"></span>',
        ],'containerOptions' => [
            'class' => false,
        ]
    ]) ?>
    <?= $form->field($model, 'Colore')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Seleziona il colore di sfondo'],
        'useNative' => true,

    ]);
    ?>

    <!--    <div class="col-lg-offset-1 col-lg-11">-->
    <?= Html::submitButton($model->Id == -1 ? 'inserisci' : 'Salva modifiche', ['class' => 'btn btn-votapp']) ?>
    <!--    </div>-->


</div>
</div>
<a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
         aria-controls="collapseExample">
    <img src="<?=Url::base('https') ?>/img/chart_ico.png" style="width: 70px; height: 70px; margin-left: 45%">
</a>
<div class="collapse" id="collapseExample">
    <div class="card card-body">
        <?= $pie; ?>
    </div>
</div>
<div class="container">
    <h1>

    <a align="centre"  class="btn btn-votapp btn-plus" href="<?=Url::toRoute ('gestioneevento')?>&Id=-1">
        <p class="glyphicon glyphicon-plus" style="font-size:40px"></p>
        <p class="glyphicon glyphicon-film" style="font-size:40px"></p>
    </a>
    </h1>
    <?php ActiveForm::end();

    if (is_array($model->Films)) {
        foreach ($model->Films as $film) {
            Film::disegnaPiccolo($film);
        }
    }
    ?>

