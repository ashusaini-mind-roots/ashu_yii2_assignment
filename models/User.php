<?php

namespace app\models;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use Yii;


class User extends ActiveRecord implements IdentityInterface
{
    public $id;
    public $name;
    public $email;
    public $auth_key;
    public $role;
    public $accessToken;
    public $password;

    public static function tableName()
    {
        return '{{%users}}';
    }

    
    public function behaviors()
    {
        return [
            //TimestampBehavior::className(),
        ];
    }

    public function rules(){
        return [
            [['name','email','password'],'string'],
            [['role'],'integer'],
            [['auth_key','accessToken'],'string','max'=>255]
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password' => 'Password Hash',
            'email' => 'Email',
            'role' => 'Role'
        ];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function findIdentity($id){
       return static::find()->where(['id' => $id])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::find()->where(['accessToken' => $token])->one();
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
        return $this->auth_key;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }



    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->getIsNewRecord()) {
            return $this->insert($runValidation, $attributeNames);
        } else {
            return $this->update($runValidation, $attributeNames) !== false;
        }
    }

}
