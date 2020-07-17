<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use yii\rbac\DbManager;
use app\models\NewUser;

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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
       return $this->render('index');
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
        $model->email = '';
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new NewUser();

        if ($model->load(Yii::$app->request->post())) {

            if($model->validate()){
                $roles=array('1'=>'Admin','2'=>'Manager','3'=>'User');
                $data = Yii::$app->request->post();
                $model->username = $data['NewUser']['username'];
                $model->email = $data['NewUser']['email'];
                $model->password = $model->setPassword($data['NewUser']['password']);
                $model->authKey = md5(random_bytes(5));
                $model->accessToken = md5(random_bytes(16));
                $model->role = $data['NewUser']['role'];
                if($model->save()){
                    $this->redirect(['/site/login']);
                }
                $role=new DbManager;
                $role->init();
                $newRole = $role->createRole($roles[$data['NewUser']['role']]);
                $role->add($newRole);
                $role->assign($newRole,$model->id);
            }
        }
        $model->username = '';
        $model->email = '';
        $model->password = '';
        $model->role = '';
        return $this->render('register', [
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
}
