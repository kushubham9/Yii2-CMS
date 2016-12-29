<?php
namespace api\modules\v1\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    /**
     * @var
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                $resetLink = \Yii::$app->urlManager->createAbsoluteUrl(['reset-password', 'token' => $user->password_reset_token],'https');
                return \Yii::$app->mailer->compose('Reset-Password', ['resetLink' => '<a class="mcnButton " title="Go here to change your password." href="'.$resetLink.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration-line: none;color: #FFFFFF;">Change Password</a>'])
                    ->enableAsync()
                    ->setFrom([\Yii::$app->params['teamEmail'] => \Yii::$app->name . ' Team'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->setTags(['reset-password'])
                    ->send();
            }
        }

        return false;
    }
}
