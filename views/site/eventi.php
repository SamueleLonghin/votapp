<?php

use app\models\Evento;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;


?>
    <h1>

        <a align="centre" class="btn btn-votapp btn-plus" href="<?= Url::toRoute('gestioneevento') ?>&Id=-1">
            <p class="glyphicon glyphicon-plus" style="font-size:40px"></p>
            <p class="glyphicon glyphicon-calendar" style="font-size:40px"></p>
        </a>
    </h1>
<?php
//var_dump($list);die();
$Giorno = date_create('NOW', new \DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s');

foreach ($list as $item) {
//    var_dump($Giorno);var_dump(date_create($item['Inizio']));die();
    Evento::disegna($item,date_create($Giorno )> date_create($item['Inizio'])&&date_create($Giorno )< date_create($item['Fine']));
}