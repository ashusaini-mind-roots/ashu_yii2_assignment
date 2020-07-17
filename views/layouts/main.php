<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\nav\NavX;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
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

       ?>

    <?php
    if(Yii::$app->user->isGuest){
        echo NavX::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Login', 'url' => ['/site/login']],
                    ['label' => 'Signup', 'url' => ['/site/register']],
                ],
            'encodeLabels' => false
        ]);
    }else{

        $username = Yii::$app->user->identity->username;
        $roles=['1'=>'Admin','2'=>'Manager','3'=>'User'];
        $roleName=$roles[Yii::$app->user->identity->role];
        echo NavX::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Posts','class' => 'btn btn-link logout', 'url' => ['/post/index']],
                    ['label' => 'Logout','class' => 'btn btn-link logout', 'url' => ['/site/logout']],
                    ['label' => $username.' | '.$roleName,'class' => 'btn btn-link logout', 'url' => ['/site/index']],
                ],
            'encodeLabels' => false
        ]);
    }
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

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ashu saini <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
