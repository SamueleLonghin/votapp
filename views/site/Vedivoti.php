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
$this->title = 'Voti';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form-group">
        <?= GridView::widget([
            'dataProvider' => $voti,
            'columns' => [
                'id',
                'name',
                'created_at:datetime',
                // ...
            ],
        ]) ?>
    </div>


</div>
