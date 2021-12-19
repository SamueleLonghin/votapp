<?php

use app\models\Film;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */

?>
<div class="site-index">
    <div class="gallery-container">
        <?php
        foreach ($films as $film) {
            Film::disegna($film);
        }
        ?>
    </div>
</div>


