<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22/04/2017
 * Time: 15:46
 */

namespace vr\app\user\forms;

use vr\app\user\validators\PasswordValidator;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Class SignInModel
 * @package vr\app\user\forms
 */
class SignInModel extends Model
{
    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $password;

    /**
     * @var bool
     */
    public $skipOnDebug = true;

    /**
     * @var string
     */
    public $tokenAttribute = 'access_token';

    /**
     * @var BaseActiveRecord
     */
    protected $user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'checkPassword'],
        ];
    }

    /**
     * @return bool
     */
    public function checkPassword()
    {
        if ($this->skipOnDebug && YII_DEBUG) {
            return true;
        }

        /** @var ActiveRecord $user */
        $user = $this->findUser();

        if ($user && \Yii::$app->security->validatePassword($this->password, $user->getAttribute('password'))) {
            return true;
        }

        return $this->addError('password', 'Incorrect email or password') && false;
    }

    /**
     * @return array|null|ActiveRecord
     */
    private function findUser()
    {
        /** @var ActiveQuery $query */
        $query = call_user_func([\Yii::$app->user->identityClass, 'find']);

        return $query->andWhere(['email' => $this->email, 'is_active' => 1])->one();
    }

    /**
     * @return bool
     */
    public function run()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user = $this->findUser();

        $this->user->setAttribute($this->tokenAttribute, \Yii::$app->security->generateRandomString());

        if (!$this->user->save()) {
            $this->addErrors($this->user->errors);

            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return [
            'user' => $this->user,
        ];
    }
}