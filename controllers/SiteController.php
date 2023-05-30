<?php

namespace app\controllers;

use app\models\Immagine;
use app\models\Location;
use app\models\Voto;
use Faker\Provider\DateTime;
use Faker\Provider\Image;
use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Film;
use app\models\Evento;
use yii\bootstrap\ActiveForm;
use app\models\UploadForm;
use yii\web\UploadedFile;

//use yii\imagine\Image;
//use vintage\tinify\UploadedFile;


class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($Giorno = false)
    {
        if (!$Giorno) {
            $Giorno = date("Y-m-d");
            return $this->redirect(['site/index', 'Giorno' => $Giorno]);
        }
        Yii::$app->view->params['eventi'] = Evento::getNomeInizio();
        return $this->render('index', ['films' => Film::getprossimiFilm('webbew', $Giorno), 'Giorno' => $Giorno,]);
//        return $this->render('index', ['films' => Film::getFilmGiornataN('webbew', $Giorno), 'Giorno' => $Giorno,]);


    }

    public function actionPie($Id = -1)
    {
        if ($Id == -1) {
            $Id = Evento::getEventoGiorno()['Id'];
        }
        return $this->render('pie', ['Id' => $Id]);
    }

    public function actionFilm($Id)
    {
        $model = Film::getById($Id);
        return $this->render('vediFilm', ['model' => $model]);
    }

    public function actionGestioneevento($Id = -1)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $model = new Evento();
        $model->Id = $Id;
        $model = ($Id == -1) ? $model : $model = Evento::getById($Id);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && !$model->isAccavallato() && $model->Salva()) {
            return $this->redirect(['site/gestioneevento', 'Id' => $model->Id]);
        }
        $pie = $this->renderPartial('pie', ['Id' => $model->Id]);
        return $this->render('gestioneEvento', ['model' => $model, 'locations' => Location::getAllforSelect2(), 'pie' => $pie]);
    }

    public function actionEventi()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $list = Evento::getAll();
//        $list = Yii::$app->db->createCommand("Select * From Eventi")->queryAll();
        return $this->render('eventi', ['list' => $list,]);
    }

    public function actionLocations()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $list = Yii::$app->db->createCommand("Select * From Location")->queryAll();
        return $this->render('locations', ['list' => $list]);
    }

    public function actionCambiastatovisibile($Id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $film = Film::getById($Id);
        $film->cambiaStatoVisibile();
        return $this->redirect(['site/gestioneevento', 'Id' => $film['IdEvento']]);
    }

    public function actionDelatefilm($Id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $film = Film::getById($Id);
        $film->delate();
        return $this->redirect(['site/gestioneevento', 'Id' => $film['IdEvento']]);
    }

    public function actionEstrazioneold()
    {
        return $this->render('estrazione', ['lista' => json_encode(Voto::getIMEIAll()), 'winner' => Voto::estrai()]);

    }

    public function actionEstrazione()
    {
        return $this->renderPartial('est', ['winner' => Voto::estrai()]);

    }

    public function actionInseriscifilm($Id = -1)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $model = $Id == -1 ? new Film() : Film::getById($Id);
        if ($Id == -1 || !isset($model->Id)) {
            $model->Image = '/img/VAdsQPEECU4s4nMgrzC9_Q5GzMdhmclD.jpg';
            $model->Id = -1;
        }
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'FileImmagine');
            if ($image) {
                $ext = pathinfo($image->name, PATHINFO_EXTENSION);
                $name = Yii::$app->security->generateRandomString() . ".{$ext}";;
                $path = 'img/' . $name;
                $image->saveAs($path);

                $imagine = new \Imagine\Imagick\Imagine();
                $imagick = $imagine->open($path);
                $imagick->resize(new \Imagine\Image\Box(640, 480))
                    ->save($path, ['jpeg_quality' => 50]);

                $model->Image = '/' . $path;
            }
            if ($model->validate() && $model->Salva()) {
                return $this->redirect(Url::toRoute('eventi'));
            }
        }
        return $this->render('inserisciFilm', [
            'model' => $model, 'eventi' => Evento::getAllforSelect2()
        ]);
    }

    public function actionCalendario()
    {
        return $this->render('calendario', []);
    }

    public function actionGestionelocation($Id = -1)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $model = new Location();
        $model->Id = $Id;
        $model = ($Id == -1) ? $model : $model = Location::getById($Id);;
        //$model->load(Yii::$app->request->post());
        //echo"<pre>";var_dump($model);die();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->Salva()) {
            return $this->render('GestioneLocation', ['model' => $model]);

            return $this->redirect(Url::toRoute('index'));
        }
        return $this->render('GestioneLocation', ['model' => $model]);
    }

    public function actionVedivoti()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        return $this->render('Vedivoti', ['voti' => Voto::getAll()]);
    }

    public function actionPietest($Id = -1)
    {
        return $this->render('pietest', []);
    }

    public function actionPietests()
    {
        return $this->render('pietestS', []);
    }

    public function actionTestf()
    {
        return $this->render('testf', []);
    }

    public function actionPiefullscreen()
    {
        return $this->renderPartial('piefullscreen', []);
    }


    /**
     * MOBILE
     */
    public function actionGetjson($IdMobile, $Giorno = false)
    {

//        $database = Yii::$app->db->createCommand("select Film.*,COALESCE(Voti.Voto,'0') as Voto from Film Left JOIN Voti on Film.Id=Voti.IdFilm and Voti.IdMobile = :IdMobile WHERE Proiezione >= :Today and Proiezione <= ADDDATE(:Today, INTERVAL 1 DAY) order by proiezione ")->bindValue('IdMobile', $IdMobile)->bindValue(':Today', $Giorno)->queryAll();
//        $database = Yii::$app->db->createCommand("select Id,Titolo,Descrizione,Durata,Autore,Proiezione,COALESCE(Image,' ') as Image,IsFilm,COALESCE(Voti.Voto,'0') as Voto from Film Left JOIN Voti on Film.Id=Voti.IdFilm and Voti.IdMobile = :IdMobile WHERE Proiezione >= :Today and Proiezione <= ADDDATE(:Today, INTERVAL 1 DAY) order by proiezione ")->bindValue('IdMobile', $IdMobile)->bindValue(':Today', $Giorno)->queryAll();
        $json = array_values(Film::getprossimiFilm($IdMobile, $Giorno));
        echo '{"elenco": ';
        echo json_encode($json);
        echo "}";
        die();
    }

    public function actionAddvoto($IdFilm, $Voto, $IdMobile, $Password, $Codice)
    {
        $model = new Voto();
        //var_dump(Yii::$app->request->get());die();
        if ($model->load(Yii::$app->request->get(), '') && $model->validate() && $model->Salva()) {
        } else {
            echo "C'è stato un errore nell'invio del voto. Prova a chiudere e riavviare l'app";
        }
    }

    public function actionControllacodice($Codice)
    {
        Evento::controllaCodice($Codice);
    }

    public function actionCodiceeventooggi()
    {
        echo json_encode(Evento::getEventoGiorno());
        die();
        var_dump($evento);
        die();

    }

    public function actionAjaxdatachart($Id = -1)
    {
        if ($Id == -1) {
            $Id = Evento::getEventoGiorno()['Id'];
        }
        // Yii::$app->response->format = Response::FORMAT_JSON;
        echo json_encode(Evento::getStatisticheFilmByIdEvento($Id));
        die();
    }

    public function actionAjaxdatachartmedia($Id = -1)
    {
        if ($Id == -1) {
            $Id = Evento::getEventoGiorno()['Id'];
        }
        // Yii::$app->response->format = Response::FORMAT_JSON;
        echo json_encode(Evento::getStatisticheMediaByIdEvento($Id));
        die();
    }

    public function actionGetlistaeventi()
    {
        $eventi = Evento::getNomeInizio();
        echo json_encode($eventi);
        die();
    }

    /**
     * AJAX WEBSITE
     */
    public function actionEsistecodiceevento($c)
    {
        echo Evento::esisteCodiceEvento($c);
        die();
    }

    public function actionAjaxfilmvalidation()
    {
        $model = new Film();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $model->valida() || true) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'data' => [
                    'success' => true,
                    'model' => $model,
                    'message' => 'Model has been saved.',
                ],
                'code' => 0,
            ];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'data' => [
                    'success' => true,
                    'model' => null,
                    'message' => 'An error occured.',
                ],
                'code' => 1, // Some semantic codes that you know them for yourself
            ];
        }
    }

    public function actionAjaxvalidaevento()
    {
        $model = new Evento();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && !$model->isAccavallato()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'data' => [
                    'success' => true,
                    'model' => $model,
                    'message' => 'Model has been saved.',
                ],
                'code' => 0,
            ];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'data' => [
                    'success' => true,
                    'model' => null,
                    'message' => 'An error occured.',
                ],
                'code' => 1, // Some semantic codes that you know them for yourself
            ];
        }
    }
}
