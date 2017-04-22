<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22/04/2017
 * Time: 15:54
 */

namespace vr\app\user\forms;

use yii\base\Model;

/**
 * Class RestorePasswordModel
 * @package vr\app\user\forms
 */
class RestorePasswordModel extends Model
{
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
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

        return true;
    }
}