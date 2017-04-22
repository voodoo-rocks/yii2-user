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
 * Class ChangePasswordModel
 * @package vr\app\user\forms
 */
class ChangePasswordModel extends Model
{
    /**
     * @var
     */
    public $oldPassword;

    /**
     * @var
     */
    public $newPassword;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword'], 'required'],
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