<?php

use app\models\Location;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;



?>
<!--    <a class="btn btn-success" href="--><?//=Url::toRoute ('gestionelocation')?><!--&Id=-1">Inserisci</a>-->
<h1>
    <a align="centre"  class="btn btn-votapp btn-plus" href="<?=Url::toRoute ('gestionelocation')?>&Id=-1">
        <p class="glyphicon glyphicon-plus" style="font-size:40px"></p>
        <p class="glyphicon glyphicon-map-marker" style="font-size:40px"></p>
    </a>
</h1>
<?php
//var_dump($list);die();
foreach ($list as $item) {
    Location::disegna($item);
}