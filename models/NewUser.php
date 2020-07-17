<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "new_user".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $email
 * @property string|null $password
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property string|null $role
 */
class NewUser extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email','password','role'], 'required'],
            [['username', 'email'], 'string', 'max' => 100],
            [['password','authKey', 'accessToken'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'role'=>'Role'
        ];
    }

    public function setPassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    public static function findIdentity($id){
       return static::find()->where(['id' => $id])->one();
    }

    public static function findByEmail($email){
       return static::find()->where(['email' => $email])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::find()->where(['accessToken' => $token])->one();
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return $this->authKey;
    }

    public function validateAuthKey($authKey){
        return $this->authKey == $authKey;
    }

    public function validatePassword($password)
    {
        return password_verify($password,$this->password);
    }
}
