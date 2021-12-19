<?php


?>



<?= yii2fullcalendar\yii2fullcalendar::widget([
    'options' => [
        'lang' => 'e',
        //... more options to be defined here!
    ],
    'events' => \yii\helpers\Url::to(['/timetrack/default/jsoncalendar'])
]);
?>

$this->widget('ext.JFullCalendar.JFullCalendar', array(
'options'=>array(
'header'=>array(
'left'=>'prev,next',
'center'=>'title',
'right'=>'today'
)
)));
