<?php

namespace vr\app\user\forms;

use vr\core\Inflector;
use vr\core\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class RegisterModel
 * @package vr\app\user\forms
 */
class RegisterModel extends Model
{
    /**
     * @var
     */
    public $firstName;

    /**
     * @var
     */
    public $lastName;

    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $password;

    /**
     * @var ActiveRecord
     */
    protected $user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'email', 'password'], 'required'],
            ['email', 'unique', 'targetClass' => \Yii::$app->user->identityClass],
            ['email', 'email'],
        ];
    }

    /**
     * @return bool
     */
    public function run()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user = \Yii::createObject(\Yii::$app->user->identityClass);

        $this->user->setAttributes(ArrayHelper::filter(
            Inflector::underscore($this->attributes), ['first_name', 'last_name', 'email']
        ));

        $this->user->setAttribute('password', \Yii::$app->security->generatePasswordHash($this->password));

        if (!($this->user->save() && $this->user->refresh())) {
            $this->addErrors($this->user);

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