<?php


use app\models\Evento;
use app\models\Film;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use app\models\LoginForm;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
use yii\web\JsExpression;

YiiAsset::register($this);


$form = ActiveForm::begin([
    'id' => 'inserisi-film',
    'layout' => 'horizontal',
]); ?>
    <div class="form-group">

        <?= $form->field($model, 'Id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'Nome') ?>
        <?=$form->field($model, 'Indirizzo')?>
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton($model->Id == -1 ? 'inserisci' : 'Modifica', ['class' => 'btn btn-votapp', 'name' => 'login-button']) ?>
        </div>
    </div>

<?php ActiveForm::end();
