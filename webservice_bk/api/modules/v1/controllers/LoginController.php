<?php

namespace api\modules\v1\controllers;

use Yii;
use common\models\User;
use common\models\LegacyPasswords;
use common\components\AesSecurity;
use common\components\FacebookLoginHandler;
use common\components\GoogleLoginHandler;
use common\components\TwitterLoginHandler;
use yii\web\HttpException;
use yii\web\BadRequestHttpException;
use api\components\Controller;
use api\modules\v1\models\PasswordResetRequestForm;
use api\modules\v1\models\ResetPasswordForm;

/**
 * Login Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class LoginController extends Controller
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\User';
    /**
     * @var
     */
    public $loginType;
    /**
     * @var
     */
    public $authToken;
    /**
     * @var
     */
    public $user;

    /**
     *
     */
    const PASSLOGIN = 0;
    /**
     *
     */
    const FBLOGIN = 1;
    /**
     *
     */
    const GOOGLELOGIN = 2;
    /**
     *
     */
    const TWITTERLOGIN = 3;
    /**
     *
     */
    const LINKEDINLOGIN = 4;

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @return $this|array
     * @throws HttpException
     */
    public function actionTwitter()
    {
        try {
            $loginClient = new TwitterLoginHandler;
            $data = $loginClient->login();
            return $data;
        } catch (\Exception $ex) {
            throw new HttpException(500, $ex);
            // throw new HttpException(500, "Unable to log you in via Twitter.") ;
        }
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     * @throws HttpException
     * @throws \Exception
     */
    public function actionCreate()
    {
        $this->loginType = intval(Yii::$app->request->get('type'));

        if ($this->loginType === self::FBLOGIN) {
            $this->authToken = Yii::$app->request->post('authtoken');
            if ($this->authToken == null) {
                throw new BadRequestHttpException("Missing request parameter: authtoken",
                    1);
            }
            $data = $this->process();
            return $this->sendApiData($data);
        } elseif ($this->loginType === self::GOOGLELOGIN) {
            $this->authToken = Yii::$app->request->post('authtoken');
            if ($this->authToken == null) {
                throw new BadRequestHttpException("Missing request parameter: authtoken",
                    1);
            }
            $data = $this->process();
            return $this->sendApiData($data);
        } elseif ($this->loginType === self::PASSLOGIN) {
            $user_email = Yii::$app->request->post('email', null);
            if ($user_email == null || $user_email == "") {
                throw new HttpException(401, "Email/Username can not be blank.");
            }

            $user_decrypt_pswd = Yii::$app->request->post('password', null);
            if ($user_decrypt_pswd == null || $user_decrypt_pswd == "") {
                throw new HttpException(401, "Password can not be blank.");
            }

            $user_pswd = AesSecurity::encrypt($user_decrypt_pswd, User::_AES_KEY);

            if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $user = User::findOne(['email' => $user_email]);
            } else {
                $user = User::findOne(['username' => $user_email]);
            }

            if (is_null($user)) {
                throw new HttpException(401, "User is not registered.");
            }

            if ($user->status != 2) {
                throw new HttpException(401, "Your account is inactive. Please confirm your email or contact team@indiefolio.com.");
            }

            if (is_null($user->password) || empty($user->password)) {
                $legacy_pass = LegacyPasswords::find()->where(['user_id' => $user->id])->one();
                if ($legacy_pass) {
                    $pass_check = ($legacy_pass->password == md5($user_decrypt_pswd));
                    if ($pass_check) {
                        $user->setPassword($user_pswd);
                        if ($user->save()) {
                            $user->refresh();
                            $legacy_pass->delete();
                        }
                    }
                } else {
                    throw new HttpException(401, "Password is not set for this account. Please try social login.");
                }
            } else {
                $pass_check = $user->validatePassword($user_pswd);
            }

            if ($pass_check) {
                $data = "";
                $data = $this->updateUser($data, $user);
                return $this->sendApiData($data);
            } else {
                throw new HttpException(401, "Username or password is incorrect");
            }
        } else {
            throw new BadRequestHttpException("Invalid login method", 1);
        }
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        $this->loginType = self::PASSLOGIN;
        $user_email = Yii::$app->request->post('email');
        if ($user_email == null || $user_email == "") {
            throw new HttpException(401, "Email can not be blank.");
        }

        $username = Yii::$app->request->post('username');
        if ($user_email == null || $user_email == "") {
            throw new HttpException(401, "Username can not be blank.");
        }

        $customer = User::findOne(['email' => $user_email]);
        if ($customer != null) {
            throw new HttpException(401,
                "Your email is already registered with us.");
        }

        $customer = User::findOne(['username' => $username]);
        if ($customer != null) {
            throw new HttpException(401, "Username already taken.");
        }

        $data['email'] = $user_email;
        $data['username'] = $username;
        $user_decrypt_pswd = Yii::$app->request->post('password');
        if ($user_decrypt_pswd == null || $user_decrypt_pswd == "") {
            throw new HttpException(401, "Password can not be blank.");
        }

        $user_pswd = AesSecurity::encrypt($user_decrypt_pswd, User::_AES_KEY);
        if (isset($user_pswd) && strlen($user_pswd) <= 50) {
            $data['password'] = Yii::$app->security->generatePasswordHash($user_pswd);
        }

        $data = $this->createUser($data);

        // if ((isset($data['at']) && isset($this->user)) && Yii::$app->request->post('verified') == null) {
        //     $activation_token = AesSecurity::encrypt($this->user->email, User::_AES_KEY) ;
        //     //send activation email
        // }

        return $this->sendApiData($data);
    }

    /**
     * @param $activation_token
     * @return array
     * @throws HttpException
     */
    public function actionActivate($activation_token)
    {
        $user = User::find()->where(['registration_key' => urldecode($activation_token)])->one();
        if ($user) {
            if ($user->status != 2) {
                $user->status = 2;
                if ($user->save()) {
                    return [
                        'activated' => 1
                    ];
                } else {
                    throw new HttpException(401, "Unable to activate your account. Please try again later.");
                }
            } else {
                throw new HttpException(401, "Your account is already activated.");
            }
        } else {
            throw new HttpException(401, "Invalid activation token. Please contact us for more assistance.");
        }
    }

    /**
     * @param $password_reset_token
     * @return array
     * @throws HttpException
     */
    public function actionResetPasswordTokenCheck($password_reset_token)
    {
        $user = User::find()->where(['password_reset_token' => urldecode($password_reset_token)])->one();
        if ($user) {
            if ($user->status == 2) {
                return [
                    'user_found' => 1
                ];
            } else {
                throw new HttpException(401, "Your account is inactive. Please confirm your email first or contact team@indiefolio.com.");
            }
        } else {
            throw new HttpException(401, "Invalid password reset token. Please contact us for more assistance.");
        }
    }

    /**
     * @param $password_reset_token
     * @return array
     * @throws HttpException
     * @throws \yii\base\Exception
     */
    public function actionResetPassword($password_reset_token)
    {
        $user = User::find()->where(['password_reset_token' => urldecode($password_reset_token)])->one();
        if ($user) {
            if ($user->status == 2) {
                $new_password = Yii::$app->request->post('password', null);
                if (strlen($new_password) > 0) {
                    $new_password = AesSecurity::encrypt($new_password, User::_AES_KEY);
                    $user->password = Yii::$app->security->generatePasswordHash($new_password);
                    $user->password_reset_token = NULL;
                    if ($user->save()) {
                        return [
                            'password_set' => 1,
                            'message' => 'Your password has been set. You can login now.'
                        ];
                    } else {
                        throw new HttpException(401, "Unable to reset password. Please contact us for more assistance.");
                    }
                } else {
                    throw new HttpException(401, "Please enter new password.");
                }
            } else {
                throw new HttpException(401, "Your account is inactive. Please confirm your email first or contact team@indiefolio.com.");
            }
        } else {
            throw new HttpException(401, "Invalid password reset token. Please contact us for more assistance.");
        }
    }

    /**
     * @return array
     * @throws HttpException
     */
    public function actionForgotPassword()
    {
        $user_email = Yii::$app->request->post('email', null);
        if ($user_email) {
            if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $user = User::find()->where(['email' => $user_email])->one();
                if ($user) {
                    if ($user->status == 2) {
                        if ($user->sendResetPasswordMail()) {
                            return [
                                'message' => "Confirmation email sent to: {$user_email}",
                                'password_mail_sent' => 1,
                            ];
                        } else {
                            throw new HttpException(400, "Unable to send password reset email.");
                        }
                    } else {
                        throw new HttpException(400, "Please confirm your account first.");
                    }
                } else {
                    throw new HttpException(400, "This email address is not registered with us.");
                }
            } else {
                throw new HttpException(400, "Please enter valid email address.");
            }
        } else {
            throw new HttpException(400, "Please enter your email address.");
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws HttpException
     */
    protected function sendApiData($data)
    {
        if (isset($data['at']) && isset($this->user)) {
            $profile = $this->user->getIndexAttributes();
            unset($profile['fb_access_token']);
            unset($profile['fbuid']);
            unset($profile['password']);
            $data['profile'] = $profile;
            return $data;
        } else {
            throw new HttpException(401, ($data['error']));
        }
    }

    /**
     * @return array|mixed
     */
    protected function getData()
    {
        if ($this->loginType === self::FBLOGIN) {
            $vendor = new FacebookLoginHandler($this->authToken);
        } elseif ($this->loginType === self::GOOGLELOGIN) {
            $vendor = new GoogleLoginHandler($this->authToken);
        }

        return $vendor->getData();
    }

    /**
     * @param null $data
     * @return array
     */
    protected function process($data = null)
    {
        if (is_null($data)) {
            try {
                $data = $this->getData();
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        $user = User::findOne(['email' => $data['email']]);

        if ($user == null) {
            return $this->createUser($data);
        } else {
            return $this->updateUser($data, $user);
        }
    }

    /**
     * @param $data
     * @return array
     */
    protected function createUser($data)
    {
        $connection = Yii::$app->db;
        try {
            $user = new User;
            $user->load($data, '');

            if (empty($user->username)) {
                $user->username = md5($user->email);
            }

            if ($this->loginType == self::PASSLOGIN) {
                $user->status = 1;
                $user->registration_key = Yii::$app->security->generateRandomString();
            } else {
                $user->status = 2;
            }

            $user->auth_key = Yii::$app->security->generateRandomString();
            $this->user = $user;
            if ($user->save()) {
                $user->refresh();
                $auth_key = $user->generateAuthKey();
                $response = [
                    'at' => $auth_key,
                    'name' => $user->first_name,
                ];
                $response['profile_image_url'] = $user->getProfileImage();
                $response['new_user'] = ($this->loginType == self::PASSLOGIN);
                return $response;
            } else {
                return ['error' => json_encode($user->getErrors())];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * @param $data
     * @param $user
     * @return array
     */
    protected function updateUser($data, &$user)
    {
        $connection = Yii::$app->db;
        try {
            $user->load($data, '');
            $this->user = $user;

            if (empty($user->username)) {
                $user->username = md5($this->email);
            }

            if ($user->save()) {
                $auth_key = $user->generateAuthKey();

                $response = [
                    'at' => $auth_key,
                    'name' => $user->first_name,
                ];

                $response['profile_image_url'] = $user->getProfileImage();
                $response['new_user'] = false;
                return $response;
            } else {
                return ['error' => json_encode($user->getErrors())];
            }
        } catch (Exception $exc) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $msg = 'Check your email for further instructions.';
                $success = true;
            } else {
                $msg = 'Sorry, we are unable to reset password for email provided.';
                $success = false;
            }
        } else {
            $msg = 'Sorry, we are unable to reset password for email provided.';
            $success = false;
        }
        return ['success' => $success,
            'message' => $msg];
    }

}
