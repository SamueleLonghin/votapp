<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use \lavrentiev\widgets\toastr\NotificationFlash;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-26KPP7QX9H"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-26KPP7QX9H');
    </script>
    <script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon"/>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title>Votapp</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            !Yii::$app->user->isGuest ? (
            ['label' => 'I Miei Eventi', 'url' => ['/site/eventi']]
            ) : (''),
            !Yii::$app->user->isGuest ? (
            ['label' => 'Le Mie Locations', 'url' => ['/site/locations']]
            ) : (''),
            !Yii::$app->user->isGuest ? (
            ['label' => 'Inserisci Film', 'url' => ['/site/inseriscifilm']]
            ) : (''),
            !Yii::$app->user->isGuest || true ? (
            [
                'label' => 'Estrazione',
                'linkOptions' => ['onclick' => "window.open('".\yii\helpers\Url::toRoute(['site/estrazione'])."','Codice Magico','width=auto,height=auto')"],
            ]
            ) : (''),
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>


<footer class="footer" style="height: auto">
    <div class="container">
        <?php
        if (isset($this->params['eventi'])) { ?>

            <div class="" id="Prossimamente">
                <h4>I nostri prossimi eventi</h4>
                <?php foreach ($this->params['eventi'] as $evento) { ?>
                    <a class="btn" href="<?=\yii\helpers\Url::toRoute(['index','Giorno'=>date("Y-m-d", strtotime($evento['Inizio']))])?>">
<!--                        https://votapp.tk/index.php?Giorno=<?=date("Y-m-d", strtotime($evento['Inizio']))?>-->
                        <h4 class="eventi-successivi4"> <?= $evento['Nome'] ?></h4>
                        <h5 class="eventi-successivi5"><?= date("d / m / Y", strtotime($evento['Inizio'])) ?></h5>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
        <p class="pull-left">&copy; Votapp <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>


<?php $this->endBody() ?>
</body>
<?= NotificationFlash::widget([
    'options' => [
        "closeButton" => true,
        "debug" => false,
        "newestOnTop" => false,
        "progressBar" => true,
        "positionClass" => NotificationFlash::POSITION_TOP_CENTER,
        "preventDuplicates" => false,
        "onclick" => null,
        "showDuration" => "300",
        "hideDuration" => "1000",
        "timeOut" => "5000",
        "extendedTimeOut" => "1000",
        "showEasing" => "swing",
        "hideEasing" => "linear",
        "showMethod" => "fadeIn",
        "hideMethod" => "fadeOut"
    ]
]) ?>
</html>
<?php $this->endPage() ?>
