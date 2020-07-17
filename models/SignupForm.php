<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $role;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name','email','password','role'], 'required'],
            // Allow valid email
            [['email'], 'email']
        ];
    }

}
