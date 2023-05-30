<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * @var UploadedFile file attribute
 */
class Film extends Model
{
    public $Id;
    public $Titolo;
    public $Descrizione;
    public $Autore;
    public $Image;
    public $IsFilm;
    public $IsVisible;
    public $IdEvento;
    public $FileImmagine;
    public $Ordine;
    public $Durata;


    public function rules()
    {
        return [
            [['Id', 'Titolo', 'Descrizione', 'Autore', 'Image', 'IsFilm', 'IdEvento', 'IsVisible', 'FileImmagine', 'Ordine', 'Durata'], 'safe'],
            [['Id', 'Titolo', 'IdEvento', 'IsFilm', 'Ordine'], 'required'],
            [['FileImmagine'], 'file', 'extensions' => 'jpg, gif, png,ico'],
            [['FileImmagine'], 'file', 'maxSize' => '20000000'],
            // [['Proiezione'], 'datetime','format' => 'php:Y-m-d H:i:s'],
            // ['Proiezione','validateDates'],
//            ['Proiezione', 'date', 'timestampAttribute' => 'Proiezione'],
//            ['Fine', 'date', 'timestampAttribute' => 'Fine'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Id' => \Yii::t('app', 'Id'),
            'Titolo' => \Yii::t('app', 'Titolo'),
            'Descrizione' => \Yii::t('app', 'Descrizione'),
            'Autore' => \Yii::t('app', 'Autore'),
            'IdEvento' => \Yii::t('app', 'Evento'),

        ];
    }

    public function valida()
    {
        $quanti = Yii::$app->db->createCommand("Select * 
            From Film 
            where( (IdEvento = :IdEvento 
                and Id != :Id ) 
                and ((Proiezione > :StartDate and Proiezione < :EndDate)
                OR (Fine > :StartDate and Fine < :EndDate)
                OR (Proiezione < :StartDate and Fine >:EndDate))
            )")
            ->bindValue(':Id', $this->Id)
            ->bindValue(':IdEvento', $this->IdEvento)
            ->bindValue(':StartDate', $this->Proiezione)
            ->bindValue(':EndDate', $this->Fine)
            ->queryOne();
        if ($quanti) \Yii::$app->session->setFlash('error', 'Controlla gli orari: Probabilmente stai sovraponendo gli eventi');
        return $quanti == 0;
    }

    public function Salva()
    {
        if ($this->Id == -1 || Yii::$app->db->createCommand("Select Count(*) From Film Where Id=:IdFilm")->bindValue(':IdFilm', $this->Id)->queryOne()['Count(*)'] == "0") {
            Yii::$app->db->createCommand("INSERT INTO `Film` ( `Titolo`, `Descrizione`,Autore,Image,IdEvento,IsFilm,Ordine,Durata,IsVisible) VALUES ( :Titolo, :Descrizione,:Autore,:Image,:IdEvento,:IsFilm,:Ordine,:Durata,:IsVisible)")
                ->bindValue(':Titolo', $this->Titolo)
                ->bindValue(':Descrizione', $this->Descrizione)
                ->bindValue(':Autore', $this->Autore)
                ->bindValue(':IsFilm', $this->IsFilm)
                ->bindValue(':IsVisible', $this->IsVisible)
                ->bindValue(':Image', $this->Image)
                ->bindValue(':IdEvento', $this->IdEvento)
                ->bindValue(':Ordine', $this->Ordine)
                ->bindValue(':Durata', $this->Durata)
                ->query();

            Yii::$app->session->setFlash('succes', 'Film inserito con successo');
            return true;
        } else {
            Yii::$app->db->createCommand("UPDATE `Film` Set  Titolo = :Titolo,Descrizione=:Descrizione,Autore=:Autore,IsFilm=:IsFilm, Image=:Image,IdEvento=:IdEvento,Ordine=:Ordine,Durata=:Durata,IsVisible = :IsVisible WHERE `Id` = :Id")
                ->bindValue(':Id', $this->Id)
                ->bindValue(':Titolo', $this->Titolo)
                ->bindValue(':Descrizione', $this->Descrizione)
                ->bindValue(':Autore', $this->Autore)
                ->bindValue(':IsFilm', $this->IsFilm)
                ->bindValue(':IsVisible', $this->IsVisible)
                ->bindValue(':Image', $this->Image)
                ->bindValue(':IdEvento', $this->IdEvento)
                ->bindValue(':Ordine', $this->Ordine)
                ->bindValue(':Durata', $this->Durata)
                ->query();

            Yii::$app->session->setFlash('succes', 'Film aggiornato');
            return true;
        }
        Yii::$app->session->setFlash('error', "C'è stato un probema");
        return false;
    }

    public static function getQueryById($Id)
    {
        return Yii::$app->db->createCommand("Select * From Film Where Id=:Id ")->bindValue(':Id', $Id)->queryOne();
    }

    public static function getByEvento($IdEvento)
    {
        return Yii::$app->db->createCommand("Select * From Film Where IdEvento=:IdEvento order by Ordine")->bindValue(':IdEvento', $IdEvento)->queryAll();
    }

    public static function getById($Id)
    {
        $model = new Film();
        $queryResult = self::getQueryById($Id);
        if ($queryResult) {
            foreach (self::getQueryById($Id) as $key => $value) {
                $model[$key] = $value;
            }
            return $model;
        }
        return new Film();
    }

    public static function getFilmGiornata($IdMobile, $Giorno)
    {
        $evento = Evento::getEventoGiorno($Giorno);
        $films = Yii::$app->db->createCommand("select Id,Titolo,Descrizione, Durata,Autore,COALESCE(Image,' ') as Image,IsFilm,COALESCE(Voti.Voto,'0') as Voto from Film Left JOIN Voti on Film.Id=Voti.IdFilm and Voti.IdMobile = :IdMobile WHERE IdEvento= :IdEvento and IsVisible=1 order by Ordine ")->bindValue('IdMobile', $IdMobile)->bindValue(':IdEvento', $evento['Id'])->queryAll();
        return $films;
    }

    public static function getFilmGiornataN($IdMobile, $Giorno)
    {
        $evento = Evento::getEventoGiornoN($Giorno);
        $films = Yii::$app->db->createCommand("select Id,Titolo,Descrizione, Durata,Autore,COALESCE(Image,' ') as Image,IsFilm,COALESCE(Voti.Voto,'0') as Voto from Film Left JOIN Voti on Film.Id=Voti.IdFilm and Voti.IdMobile = :IdMobile WHERE IdEvento= :IdEvento and IsVisible=1 order by Ordine ")->bindValue('IdMobile', $IdMobile)->bindValue(':IdEvento', $evento['Id'])->queryAll();
        return $films;
    }

    public static function getprossimiFilm($IdMobile, $Giorno)
    {
        $evento = Evento::getProssimoEvento($Giorno);
//        var_dump($evento);die();
        $films = Yii::$app->db->createCommand("select Id,Titolo,Descrizione, Durata,Autore,COALESCE(Image,' ') as Image,IsFilm,COALESCE(Voti.Voto,'0') as Voto from Film Left JOIN Voti on Film.Id=Voti.IdFilm and Voti.IdMobile = :IdMobile WHERE IdEvento= :IdEvento and IsVisible=1 order by Ordine ")->bindValue('IdMobile', $IdMobile)->bindValue(':IdEvento', $evento['Id'])->queryAll();
        return $films;
    }

    public function cambiaStatoVisibile()
    {
        //$model = Yii::$app->db->createCommand("Select IsVisible From Film Where Id=:Id")->bindValue(':Id', $Id)->queryOne();
        Yii::$app->session->setFlash('success', ("Lo stato del film è ora impostato su " . ($this->IsVisible == 1 ? 'Nascosto' : 'Visibile')));
        return Yii::$app->db->createCommand("Update Film Set IsVisible=:Stato Where Id=:Id ")->bindValue(':Id', $this->Id)->bindValue(':Stato', $this->IsVisible == 1 ? 0 : 1)->query();
    }

    public function delate()
    {
        Yii::$app->session->setFlash('success', 'Film eliminato con successo');
        return Yii::$app->db->createCommand(" DELETE FROM Film WHERE `Id` =:Id ")->bindValue(':Id', $this->Id)->query();
    }

    // Compress image
    public static function compressImage($source, $destination, $quality)
    {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);
    }

    public static function disegna($model)
    {
        if (trim($model['IsFilm']) || true) {
            ?>
            <div class="gallery-space-item">
                <a href="<?= Url::toRoute(['film', 'Id' => $model['Id']]) ?>">
                    <div class="sub-gallery-text" style="
                            background:
                            linear-gradient(
                            rgba(0, 0, 0, 0.65),
                            rgba(0, 0, 0, 0.5)),
                            url('<?= $model['Image'] ?>');
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: center;
                            background-image:;
                            max-height: 80vh;
                            min-height: 50vh;
                            display: flex;
                            flex-direction: column;
                            background-position-x: center !important;
                            background-position-y: center;
                            /*display: flex;*/
                            /*flex-direction: column;*/
                            ">
                        <div class="row">
                            <div class="title col-md-8 col-md-offset-2">
                                <h3><?= $model['Titolo'] ?></h3>
                                <H4>Un film di: <?= $model['Autore'] ?></H4>
                                <p>Trama: <?= $model['Descrizione'] ?></p>
                            </div>
                            <div align="center" class=" col-md-2"
                                 style="padding-right: 30px;padding-top: 10px;display:none;">
                                <span class="glyphicon glyphicon-time"></span>
                                <p> <?= date("H:I", strtotime($model['Durata'])) ?>'</p>
                            </div>
                        </div>
                        <div style="flex-grow: 3;"></div>
                        <div class="row" style="display:none;">
                            <div class="titlee col-md-2 ">
                                <p></p>
                            </div>
                            <div class="titlee col-md-2 col-md-offset-8">
                                <p></p>
                            </div>
                        </div>

                    </div>
                </a>

            </div>
            <?php
        }

    }

    public static function disegnaPiccolo($model)
    {
        ?>
        <div class="gallery-space-item">
            <a href="<?= Url::toRoute(['film', 'Id' => $model['Id']]) ?>">
                <div class="sub-gallery-text" style="
                        background:
                        linear-gradient(
                        rgba(0, 0, 0, 0.65),
                        rgba(0, 0, 0, 0.5)),
                        url('<?= $model['Image'] ?>');
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-position: center;

                        display: flex;
                        flex-direction: column;
                        background-position-x: center !important;
                        background-position-y: center;
                        /*display: flex;*/
                        /*flex-direction: column;*/
                        ">
                    <div class="row">
                        <div class="titlee col-md-2 " style="display:none;">
                            <p></p>
                        </div>
                        <div class="title col-md-7  col-md-offset-2"><!--   col-md-offset-2-->
                            <h3><?= $model['Titolo'] ?></h3>
                            <H4>Un film di: <?= $model['Autore'] ?></H4>
                            <p>Trama: <?= $model['Descrizione'] ?></p>
                        </div>
                        <div class="titlee col-md-2 " style="display:none;">
                            <p></p>
                        </div>
                        <div class="col-md-1 col-md-offset-2">
                            <a
                                    href="<?= Url::toRoute(['site/inseriscifilm', 'Id' => $model['Id']]) ?>"
                                    style="height: 100%;"
                                    class="btn btn-default"
                            >
                                    <span class="glyphicon glyphicon-edit">
                                    </span>
                            </a>

                            <a
                                    href="<?= Url::toRoute(['site/cambiastatovisibile', 'Id' => $model['Id']]) ?>"
                                    style="height: 100%;"
                                    class="btn btn-default"
                            >
                                    <span class="glyphicon glyphicon-eye-<?= $model['IsVisible'] == 0 ? 'open' : 'close' ?>">
                                    </span>
                            </a>
                            <?= Html::a('<span class="glyphicon glyphicon-trash">
                                    </span>', ['delatefilm', 'Id' => $model['Id']], [
                                'class' => '  btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>

                </div>
            </a>
        </div>
        <?php
    }
}