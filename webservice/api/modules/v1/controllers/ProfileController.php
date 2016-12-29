<?php

namespace api\modules\v1\controllers ;

use common\models\City;
use common\models\Status;
use common\models\UserDetails;
use common\models\UserSoftware;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Yii ;
use api\components\ActiveController ;
use common\models\User ;
use common\models\UserWork ;
use common\models\UserSocial ;
use common\models\UserEducation ;
use common\models\UserNotificationSetting ;
use common\models\ExpertiseMaster ;
use common\models\ExpertiseUserRelation ;
use common\models\CreativeField ;
use common\components\AesSecurity ;
use common\components\Base64Image ;
use ImageOptimizer\OptimizerFactory;
use common\components\ImageComponent;
use yii\web\NotFoundHttpException ;

/**
 * Profile Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class ProfileController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\User' ;

    /**
     * @return array
     */
    public function actions()
    {
        return [] ;
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $user = User::find()->where(['username' => $id])->one() ;
        if (!is_null($user)) {
            $response = $user->attributes ;
            if ($user->id !== \Yii::$app->user->id) {
                $user->view_count = $user->view_count + 1 ;
                $user->save() ;
            }
            $response['view_count'] = $user->view_count ;
            return $response ;
        } else {
            $user = User::find()->where(['user_guid' => $id])->one() ;
            if (!is_null($user)) {
                $response = $user->attributes ;
                if ($user->id !== \Yii::$app->user->id) {
                    $user->view_count = $user->view_count + 1 ;
                    $user->save() ;
                }
                $response['view_count'] = $user->view_count ;
                return $response ;
            } else {
                throw new NotFoundHttpException("No such user found", 1) ;
            }
        }
    }

    /**
     * @return array
     */
    public function actionGetCurrentUserSession()
    {
        $user = \Yii::$app->user->identity ;
        $response = [] ;
        $response['id'] = $user->id ;
        $response['name'] = $user->first_name . " " . $user->last_name ;
        $response['image'] = $user->getProfileImage() ;
        $response['gender'] = $user->gender ;
        $response['username'] = $user->username ;
        $response['location'] = $user->location ;
        $response['current_position'] = $user->current_position ;
        $response['is_tour_shown'] = $user->is_tour_shown ;
        $response['user_guid'] = $user->user_guid ;

        return $response ;
    }

    /**
     * @return array
     */
    public function actionGetSettings()
    {
        $user = \Yii::$app->user->identity ;
        $response = [] ;
        $response['first_name'] = $user->first_name ;
        $response['last_name'] = $user->last_name ;
        $response['username'] = $user->username ;
        $response['email'] = $user->email ;
        $response['emails'][] = [
            "email" => $user->email,
            "is_primary" => 1
        ] ;

        $response['is_password_set'] = 1 ;
        if (is_null($user->password) || strlen($user->password) == 0) {
            $response['is_password_set'] = 0 ;
        }

        foreach ($user->emails as $email) {
            $response['emails'][] = [
                "email" => $email->email,
                "is_primary" => 0
            ] ;
        }

        $notification_settings = $user->userNotificationSettings ;
        if (is_null($notification_settings)) {
            $notification_settings = new UserNotificationSetting ;
            $notification_settings->user_id = $user->id ;
            $notification_settings->save() ;
        }

        $response['notif_settings'] = $notification_settings->getBooleanAttributes(null,
                                                                                   ['id',
            'user_id',
            'create_time',
            'update_time']) ;

        return $response ;
    }

    /**
     * @param $username
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionViewLess($username)
    {
        $user = User::find()->where(['username' => $username])->one() ;
        if (!is_null($user)) {
            $user_name = $user->first_name . " " . $user->last_name ;
            if (strlen($user_name) < 2) {
                $user_name = $user->username ;
            }
            $response = [
                'id' => $user->id,
                'username' => $user_name
            ] ;
            return $response ;
        } else {
            throw new NotFoundHttpException("No such user found", 1) ;
        }
    }

    /**
     * @param $username
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewCard($username)
    {
        $user = User::find()->where(['username' => $username])->one() ;
        if (!is_null($user)) {
            return $user->hoverCardAttributes ;
        } else {
            throw new NotFoundHttpException("No such user found", 1) ;
        }
    }

    /**
     * @return mixed
     */
    public function actionPovDetails()
    {
        return \Yii::$app->user->identity->getAttributes(null, [], [1,
            2]) ;
    }

    /**
     * @return mixed
     * @throws \yii\base\ErrorException
     * @throws \yii\web\HttpException
     */
    public function actionUpdateCover()
    {
        $request = \Yii::$app->request ;
        $s3 = \Yii::$app->s3 ;

        $user_id = \Yii::$app->user->id ;

        // Getting data from post
        $cover_image_data = $request->post("cover_image_data", null) ;
        $offset_x = $request->post("offset_x", 0) ;
        $offset_y = $request->post("offset_y", 0) ;
        $width = $request->post("width", 1440) ;
        $height = $request->post("height", 320) ;

        if (!is_null($cover_image_data)) {
            $filename = md5("Cover" . $user_id . microtime() . uniqid()) ;
            list($filename, $filepath) = explode(':', Base64Image::save($cover_image_data, $filename)) ;
            unset($cover_image_data) ;

            $og = $s3->uploadFile($filepath, $filename, User::S3_COVER_PIC.'original/') ;
            $image = \Yii::$app->image->load($filepath) ;
            $image->resize($width, null, \yii\image\drivers\Image::ADAPT)
            ->crop($width, $height, $offset_x, $offset_y)
            ->save();

            $factory = new OptimizerFactory();
            $optimizer = $factory->get();
            $optimizer->optimize($filepath);

            $url = $s3->uploadFile($filepath, $filename, User::S3_COVER_PIC.'large/') ;
            $user = User::find()->where(['id' => $user_id])->one() ;

            if ($user) {
                $user->profile_cover = $filename ;
                if ($user->save()) {
                    return $url ;
                } else {
                    throw new \yii\web\HttpException(500, 'Unable to save new cover image.') ;
                }
            } else {
                throw new \yii\web\HttpException(400, 'Not Authorized') ;
            }
        } else {
            throw new \yii\web\HttpException(400, 'Image not uploaded') ;
        }
    }

    /**
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function actionUpdateProfilePhoto()
    {
        $request = \Yii::$app->request ;
        $s3 = \Yii::$app->s3 ;

        $user_id = \Yii::$app->user->id ;

        // Getting data from post
        $profile_image_data = $request->post("profile_image_data", null) ;
        $width = $request->post("width", 250) ;

        if (!is_null($profile_image_data)) {
            $filename = md5("ProfilePic" . $user_id . microtime() . uniqid()) ;
            list($filename, $filepath) = explode(':', Base64Image::save($profile_image_data, $filename)) ;

            $newPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.Yii::$app->security->generateRandomString();
            $ogPath = $newPath.DIRECTORY_SEPARATOR.'original'.DIRECTORY_SEPARATOR;
            $lgPath = $newPath.DIRECTORY_SEPARATOR.'large'.DIRECTORY_SEPARATOR;
            $smPath = $newPath.DIRECTORY_SEPARATOR.'small'.DIRECTORY_SEPARATOR;
            $xsmPath = $newPath.DIRECTORY_SEPARATOR.'xsmall'.DIRECTORY_SEPARATOR;

            mkdir($newPath);
            mkdir($ogPath);
            mkdir($lgPath);
            mkdir($smPath);
            mkdir($xsmPath);

            copy($filepath,$ogPath.$filename);
            copy($filepath,$lgPath.$filename);
            copy($filepath,$smPath.$filename);
            copy($filepath,$xsmPath.$filename);

            ImageComponent::resizeImage(250, $filename, $lgPath.$filename);
            ImageComponent::resizeImage(180, $filename, $smPath.$filename);
            ImageComponent::resizeImage(70, $filename, $xsmPath.$filename);

            $factory = new OptimizerFactory();
            $optimizer = $factory->get();
            $optimizer->optimize($lgPath.$filename);
            $optimizer->optimize($smPath.$filename);
            $optimizer->optimize($xsmPath.$filename);

            $og = $s3->uploadFile($ogPath.$filename, $filename, User::S3_PROFILE_PIC.'original/') ;
            $url = $s3->uploadFile($lgPath.$filename, $filename, User::S3_PROFILE_PIC.'large/') ;
            $sm = $s3->uploadFile($smPath.$filename, $filename, User::S3_PROFILE_PIC.'small/') ;
            $xsm = $s3->uploadFile($xsmPath.$filename, $filename, User::S3_PROFILE_PIC.'xsmall/') ;
            unset($profile_image_data) ;

            $user = User::find()->where(['id' => $user_id])->one() ;

            if ($user) {
                $user->profile_picture = $filename ;
                if ($user->save()) {

                    ImageComponent::delDir($newPath);
                    return $url ;
                } else {
                    throw new \yii\web\HttpException(500,
                                                     'Unable to save new profile image.') ;
                }
            } else {
                throw new \yii\web\HttpException(400, 'Not Authorized') ;
            }
        } else {
            throw new \yii\web\HttpException(400, 'Image not uploaded') ;
        }
    }

    /**
     * @param $type
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionUpdateProfileDetails($type)
    {
        $request = \Yii::$app->request ;
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one() ;

        if ($user) {
            switch ($type) {
                case 'name':
                    $first_name = $request->post("first_name", null) ;
                    $last_name = $request->post("last_name", null) ;
                    if (!is_null($first_name) && !is_null($last_name)) {
                        $user->first_name = $first_name ;
                        $user->last_name = $last_name ;
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: First name & Last name') ;
                    }
                    if ($user->save()) {
                        $user->refresh();
                        return [
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(500, 'Unable to save first and last name.') ;
                    }
                    break ;

                case 'gender':

                    $gender = $request->post("gender", null) ;

                    if (!is_null($gender)) {
                        $user->gender = $gender;
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: Sex') ;
                    }
                    if ($user->save()) {
                        $user->refresh();
                        return [
                            'gender'=>$user->gender
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(500, 'Unable to save sex.') ;
                    }
                    break ;

                case 'position_location':
                    $current_position = $request->post("current_position", null) ;
                    if (!is_null($current_position)) {
                        $user->current_position = $current_position ;

                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: Profile headline') ;
                    }
                    if ($user->save()) {
                        return [
                            'current_position' => $current_position
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(500, 'Unable to save profile headline.') ;
                    }
                    break ;

                case 'location':

                    $city = $request->post("city", null) ;
                    $state = $request->post("state", null) ;
                    $country = $request->post("country", null) ;

                    if (!is_null($city) && !is_null($state) && !is_null($country)) {
                        $model = City::find()->where(['name'=>$city])->one();
                        if(!empty($model)) {
                            if ($model->state->name === $state && $model->state->country->name = $country) {
                                $user->city_id = $model->id;
                            } else {
                                throw new \yii\web\HttpException(400, 'We seem to have lost you. Can you select the correct country, state and city.');

                            }
                        } else {
                            throw new \yii\web\HttpException(400, 'Please enter valid city name.');
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: Location') ;
                    }
                    if ($user->save()) {
                        return [
                            'city' => $city,
                            'state' => $state,
                            'country' => $country
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(500, 'Unable to save location.') ;
                    }
                    break ;

                case 'biography':
                    $biography = $request->post("biography", null) ;

                    if (!is_null($biography)) {
                        $user->biography = $biography ;
                        if ($user->save()) {
                            return [
                                'biography' => $biography
                            ] ;
                        } else {
                            throw new \yii\web\HttpException(500, 'Unable to update biography.') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: Biography') ;
                    }
                    break ;

                case 'expertise':
                    $expertises = $request->post("expertise", null) ;

                    if (!is_null($expertises) && is_array($expertises) && count($expertises) > 0) {
                        $current_expertise = [] ;
                        foreach ($user->expertiseUserRelations as $exp) {
                            $current_expertise[] = $exp->expertise_id ;
                        }

                        $to_be_created = [] ;
                        $new_expertise = [] ;
                        foreach ($expertises as $exp) {
                            if (isset($exp['id'])) {
                                $new_expertise[] = $exp['id'] ;
                            } else {
                                $to_be_created[] = $exp['name'] ;
                            }
                        }

                        $to_be_added = array_diff($new_expertise,
                                                  $current_expertise) ;
                        $to_be_deleted = array_diff($current_expertise,
                                                    $new_expertise) ;

                        foreach ($to_be_created as $exp_name) {
                            $model = new ExpertiseMaster ;
                            $model->name = $exp_name ;
                            $model->entity = 1 ;
                            $model->is_published = 2 ;
                            if (!$model->save()) {
                                throw new \yii\web\HttpException(500, 'Unable to save user expertise: ' . $exp_name) ;
                            } else {
                                $model->refresh() ;
                                $rModel = new ExpertiseUserRelation ;
                                $rModel->user_id = \Yii::$app->user->id ;
                                $rModel->expertise_id = $model->id ;
                                if (!$rModel->save()) {
                                    throw new \yii\web\HttpException(500, 'Unable to create new user expertise.') ;
                                }
                            }
                        }

                        foreach ($to_be_added as $key => $value) {
                            $model = new ExpertiseUserRelation ;
                            $model->user_id = \Yii::$app->user->id ;
                            $model->expertise_id = $value ;
                            if (!$model->save()) {
                                throw new \yii\web\HttpException(500, 'Unable to add new user expertise.') ;
                            }
                        }

                        foreach ($to_be_deleted as $key => $value) {
                            $model = $user->getExpertiseUserRelations()->where(['expertise_id' => $value])->one() ;
                            if ($model && !$model->delete()) {
                                throw new \yii\web\HttpException(500, 'Unable to delete old user expertise.') ;
                            }
                        }

                        return [
                            'expertises' => $user->getExpertiseUserRelations()->all()
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: Expertises') ;
                    }
                    break ;

                case 'work':
                    $work = $request->post("work", null) ;

                    if (!is_null($work)) {
                        if (isset($work['delete']) && $work['delete'] == 1) {
                            if (!empty($work['id'])) {
                                $model = $user->getUserWork()->where(['id' => $work['id']])->one() ;
                                if ($model && $model->delete()) {
                                    return [
                                        'work_history' => $user->getUserWork()->all()
                                    ] ;
                                } else {
                                    throw new \yii\web\HttpException(400, 'Can not delete job from history') ;
                                }
                            } else {
                                throw new \yii\web\HttpException(400, 'Can not delete job from history') ;
                            }
                        } else {
                            if (!empty($work['position'])) {
                                if (!empty($work['organization'])) {
                                    if (!empty($work['start_month']) && !empty($work['start_year'])) {
                                        if ((!empty($work['is_current']) && $work['is_current']) || (!empty($work['end_month']) && !empty($work['end_year']))) {
                                            if (!empty($work['id'])) {
                                                $model = $user->getUserWork()->where(['id' => $work['id']])->one() ;
                                            } else {
                                                $model = new UserWork ;
                                            }

                                            if ((!empty($work['is_current']) && $work['is_current'])) {
                                                $work['end_month'] = null ;
                                                $work['end_year'] = null ;
                                                $work['is_current'] = 1 ;
                                            } else {
                                                $end_date = new \DateTime('01-'.$work['end_month'].'-'.$work['end_year']);
                                                $work['is_current'] = 0 ;
                                            }
                                            $start_date = new \DateTime('01-'.$work['start_month'].'-'.$work['start_year']);
                                            if(new \DateTime('now') < $start_date){
                                                throw new \yii\web\HttpException(400, 'Time traveled to future, please select appropriate date for job start.') ;
                                            }
                                            if(!$work['is_current'] && $start_date > $end_date){
                                                throw new \yii\web\HttpException(400, 'Job start date is greater than end date.') ;
                                            }


                                            $work['user_id'] = $user->id ;
                                            if ($model->load(['UserWork' => $work]) && $model->save()) {
                                                return [
                                                    'work_history' => $user->getUserWork()->all()
                                                ] ;
                                            } else {
                                                throw new \yii\web\HttpException(400, json_encode($model->errors)) ;
                                            }
                                        } else {
                                            throw new \yii\web\HttpException(400, 'Required fields missing: Time Period') ;
                                        }
                                    } else {
                                        throw new \yii\web\HttpException(400, 'Required fields missing: Time Period') ;
                                    }
                                } else {
                                    throw new \yii\web\HttpException(400, 'Required fields missing: Organization Name') ;
                                }
                            } else {
                                throw new \yii\web\HttpException(400, 'Required fields missing: Job Designation') ;
                            }
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Job data missing.') ;
                    }
                    break ;

                case "education":
                    $education = $request->post("education", null) ;

                    if (!is_null($education)) {
                        if (isset($education['delete']) && $education['delete'] == 1) {
                            if (!empty($education['id'])) {
                                $model = $user->getUserEducation()->where(['id' => $education['id']])->one() ;
                                if ($model && $model->delete()) {
                                    return [
                                        'education_history' => $user->getUserEducation()->all()
                                    ] ;
                                } else {
                                    throw new \yii\web\HttpException(400,
                                                                     'Can not delete school from history') ;
                                }
                            } else {
                                throw new \yii\web\HttpException(400,
                                                                 'Can not delete school from history') ;
                            }
                        } else {
                            if (!empty($education['institute'])) {
                                if (!empty($education['course_name'])) {
                                    if (!empty($education['start_month']) && !empty($education['start_year'])) {
                                        if ((!empty($education['is_current']) && $education['is_current']) || (!empty($education['end_month']) && !empty($education['end_year']))) {
                                            if (!empty($education['id'])) {
                                                $model = $user->getUserEducation()->where(['id' => $education['id']])->one() ;
                                            } else {
                                                $model = new UserEducation ;
                                            }

                                            if ((!empty($education['is_current']) && $education['is_current'])) {
                                                $education['end_month'] = null ;
                                                $education['end_year'] = null ;
                                                $education['is_current'] = 1 ;
                                            } else {
                                                $end_date = new \DateTime('01-'.$education['end_month'].'-'.$education['end_year']);
                                                $education['is_current'] = 0 ;
                                            }
                                            $start_date = new \DateTime('01-'.$education['start_month'].'-'.$education['start_year']);
                                            if(new \DateTime() < $start_date){
                                                throw new \yii\web\HttpException(400, 'Time traveled to future, please select appropriate date for education start.') ;
                                            }
                                            if(!$education['is_current'] && $start_date > $end_date){
                                                throw new \yii\web\HttpException(400, 'Education start date is greater than end date.') ;
                                            }

                                            $education['user_id'] = $user->id ;

                                            if ($model->load(['UserEducation' => $education]) && $model->save()) {
                                                return [
                                                    'education_history' => $user->getUserEducation()->all()
                                                ] ;
                                            } else {
                                                throw new \yii\web\HttpException(400,
                                                                                 json_encode($model->errors)) ;
                                            }
                                        } else {
                                            throw new \yii\web\HttpException(400,
                                                                             'Required fields missing: Time Period') ;
                                        }
                                    } else {
                                        throw new \yii\web\HttpException(400,
                                                                         'Required fields missing: Time Period') ;
                                    }
                                } else {
                                    throw new \yii\web\HttpException(400,
                                                                     'Required fields missing: Degree name') ;
                                }
                            } else {
                                throw new \yii\web\HttpException(400,
                                                                 'Required fields missing: Institute Name') ;
                            }
                        }
                    } else {
                        throw new \yii\web\HttpException(400,
                                                         'School data missing.') ;
                    }
                    break ;

                case "notification_settings":
                    $type = $request->post("type", null) ;
                    $new_value = intval($request->post("new_value", 0)) ;

                    if (!is_null($type)) {
                        $notification_settings = $user->userNotificationSettings ;
                        if (is_null($notification_settings)) {
                            $notification_settings = new UserNotificationSetting ;
                            $notification_settings->user_id = $user->id ;
                        }
                        if ($notification_settings->hasAttribute($type)) {
                            $notification_settings->{$type} = $new_value ;
                            if ($notification_settings->save()) {
                                return [
                                    "{$type}" => $new_value
                                ] ;
                            } else {
                                throw new \yii\web\HttpException(500,
                                                                 'Unable to save changes') ;
                            }
                        } else {
                            throw new \yii\web\HttpException(400,
                                                             'Invalid notification type') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400,
                                                         'Required fields missing: Notification Type') ;
                    }

                case "username":
                    $username = $request->post("username", null) ;
                    if (!is_null($username)) {
                        if (ctype_alnum($username) !== false) {
                            if (!User::find()->where(['username' => $username])->andFilterWhere(['!=', 'id', $user->id])->exists()) {
                                $user->username = $username ;
                                if ($user->save()) {
                                    $user->refresh();
                                    return [
                                        "username" => $username,
                                    ] ;
                                } else {
                                    throw new \yii\web\HttpException(400, 'Unable to save username.') ;
                                }
                            } else {
                                throw new \yii\web\HttpException(400, 'Username is already taken') ;
                            }
                        } else {
                            throw new \yii\web\HttpException(400, 'Username can be alphanumeric only') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: username') ;
                    }
                    break;

                case 'phone':
                    $phone = $request->post("phone", null) ;

                    if(!is_null($phone)) {
                        $phoneUtil = PhoneNumberUtil::getInstance();
                        $numberPrototype = $phoneUtil->parse('+91' . $phone, "IN");
                        if ($phoneUtil->isValidNumber($numberPrototype)) {
                            $user->phone = $phoneUtil->format($numberPrototype, PhoneNumberFormat::E164);
                        } else {
                            throw new \yii\web\HttpException(400, 'Phone number incorrect') ;
                        }
                        if ($user->save()) {
                            $user->refresh();
                            return [
                                "phone" => $user->phone
                            ] ;
                        } else {
                            throw new \yii\web\HttpException(400, 'Unable to save phone.') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Please enter phone number.') ;

                    }
                    break;

                case "email":
                    $email = $request->post("email", null) ;

                    if (!is_null($email)) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            if (!User::find()->where(['email' => $email])->exists()) {
                                $user->email = $email ;
                                if ($user->save()) {
                                    return [
                                        "email" => $email
                                    ] ;
                                } else {
                                    throw new \yii\web\HttpException(500,
                                                                     'Unable to change email') ;
                                }
                            } else {
                                throw new \yii\web\HttpException(400,
                                                                 'Email already registered') ;
                            }
                        } else {
                            throw new \yii\web\HttpException(400,
                                                             'Email address is not valid') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400,
                                                         'Required fields missing: email') ;
                    }

                case "password":
                    $current_password = $request->post("current_password", null) ;
                    $new_password = $request->post("new_password", null) ;

                    if (!is_null($new_password)) {
                        $current_password = AesSecurity::encrypt($current_password,
                                                                 User::_AES_KEY) ;
                        $new_password = AesSecurity::encrypt($new_password,
                                                             User::_AES_KEY) ;
                        if (!is_null($user->password) && strlen($user->password) > 0) {
                            $pass_check = Yii::$app->security->validatePassword($current_password,
                                                                                $user->password) ;
                            if ($pass_check) {
                                $user->password = Yii::$app->security->generatePasswordHash($new_password) ;
                            } else {
                                throw new \yii\web\HttpException(400,
                                                                 'Current password is not valid') ;
                            }
                        } else {
                            $user->password = Yii::$app->security->generatePasswordHash($new_password) ;
                        }

                        if ($user->save()) {
                            return [
                                "is_password_set" => 1
                            ] ;
                        } else {
                            throw new \yii\web\HttpException(500,
                                                             'Unable to change password') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400,
                                                         'Required fields missing: New Password') ;
                    }

                case "social":
                    $links = $request->post('links') ;

                    $connection = \Yii::$app->db ;
                    $transaction = $connection->beginTransaction() ;

                    try {
                        UserSocial::deleteAll(['user_id' => $user->id]) ;
                        foreach ($links as $link) {
                            if (isset($link['social_link'])) {
                                $ln = new UserSocial ;
                                $ln->user_id = $user->id ;
                                $ln->social_type = $link['social_type'] ;
                                $ln->social_link = $link['social_link'] ;
                                if (!$ln->save()) {
                                    $transaction->rollBack() ;
                                    throw new \yii\web\HttpException(500,
                                                                     'Unable to change links') ;
                                }
                            }
                        }
                        $transaction->commit() ;
                        return [
                            "links_changed" => 1
                        ] ;
                    } catch (\Exception $ex) {
                        $transaction->rollBack() ;
                        throw new \yii\web\HttpException(500,
                                                         'Unable to change links') ;
                    }
                    break ;

                case "employment":
                    $employment_status = $request->post('employment_status',
                                                        null) ;

                    if (!is_null($employment_status)) {
                        $user->employment_status = $employment_status ;
                        if ($user->save()) {
                            return [
                                "employment_status_changed" => 1
                            ] ;
                        } else {
                            throw new \yii\web\HttpException(500,
                                                             "Unable to change employment status") ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400,
                                                         "Missing required fields: Employment Status") ;
                    }
                    break ;

                case 'interested_fields':
                    $creative_field_ids = $request->post('creative_field_ids',null) ;
                    if (!is_null($creative_field_ids) || !is_array($creative_field_ids) || empty($creative_field_ids)) {
                        $connection = \Yii::$app->db ;
                        $transaction = $connection->beginTransaction() ;

                        try {
                            CreativeField::deleteAll(['entity_type' => 'User',
                                'entity_id' => $user->id]) ;
                            foreach ($creative_field_ids as $cf_id) {
                                $cField = new CreativeField ;
                                $cField->entity_id = $user->id ;
                                $cField->entity_type = 'User' ;
                                $cField->creative_field_master_id = $cf_id ;
                                $cField->status = 1 ;
                                if (!$cField->save()) {
                                    $transaction->rollBack() ;
                                    throw new \yii\web\HttpException(500, 'Unable to save interested fields.') ;
                                }
                            }
                            $transaction->commit() ;
                            return [
                                "fields_saved" => 1
                            ] ;
                        } catch (\Exception $ex) {
                            $transaction->rollBack() ;
                            throw new \yii\web\HttpException(500, 'Please enter the creative fields you specialize in') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, "Missing required fields: Creative Fields") ;
                    }
                    break ;

                case 'tour_complete':
                    $is_tour_shown = $request->post('is_tour_shown', 0) ;
                    $user->is_tour_shown = $is_tour_shown ;
                    if ($user->save()) {
                        return [
                            'is_tour_shown' => $is_tour_shown
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(400,
                                                         "Unable to perform") ;
                    }

                case "portfolio-order":
                    $order_data = $request->post('order', []) ;
                    foreach ($order_data as $order) {
                        $project = $user->getProjects()->andWhere(['project_guid' => $order['guid']])->one() ;
                        if ($project) {
                            $project->order = $order['order'] ;
                            if (!$project->save()) {
                                throw new \yii\web\HttpException(500,
                                                                 'Unable to change order') ;
                            }
                        }
                    }
                    return [
                        'order_changed' => 1
                    ] ;
                    break ;

                case 'opportunity':

                    $opportunity = $request->post("opportunity", null) ;
//                    return $opportunity;
                    if (!is_null($opportunity)) {
                        if(is_null($opportunity['opportunity'])) {
                            throw new \yii\web\HttpException(400, 'Are you looking for an opportunity?') ;
                        }
                        if($opportunity['opportunity'] && !is_null($opportunity['type'])){
                            if(!isset($opportunity['type']['freelance'])) {
                                $opportunity['type']['freelance'] =0;
                            }
                            if(!isset($opportunity['type']['fulltime'])) {
                                $opportunity['type']['fulltime'] =0;
                            }
                            if(!isset($opportunity['type']['internship'])) {
                                $opportunity['type']['internship'] =0;
                            }
                            if(!$opportunity['type']['freelance'] && !$opportunity['type']['fulltime'] && !$opportunity['type']['internship'])
                                throw new \yii\web\HttpException(400, 'Please choose one of the work opportunity type.') ;
                        } else if ($opportunity['opportunity'] && is_null($opportunity['type'])) {
                            throw new \yii\web\HttpException(400, 'Please choose one of the work opportunity type.') ;
                        }
                        $model = $user->getUserDetails()->one() ;
                        $opportunity['user_id'] = $user->id;
                        if(empty($model)) {
                            $model = new UserDetails;
                        }
                        if($model->load(['UserDetails'=>$opportunity]) && $model->save()) {
                            unset($opportunity['user_id']);
                            $model->refresh();
                            return $model;
                        } else {
                            throw new \yii\web\HttpException(400, 'Could not save try-again later.') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Data missing.') ;
                    }
                    break ;

                case 'software':
                    $software = $request->post("software", null) ;

                    if (!is_null($software) && is_array($software) && count($software) > 0) {

                        $modelaAll = $user->getUserSoftwares()->where(['user_id' => Yii::$app->user->id])->all() ;
                        foreach ($modelaAll as $model) {
                            if ($model && !$model->delete()) {
                                throw new \yii\web\HttpException(500, 'Unable to delete old user expertise.');
                            }
                        }
                        foreach ($software as $key => $value) {
                            $model = new UserSoftware ;
                            $model->user_id = \Yii::$app->user->id ;
                            $model->software_master_id = $value['id'] ;
                            $model->rating = $value['rating'] ;
                            if (!$model->save()) {
                                throw new \yii\web\HttpException(500, 'Unable to add new user expertise.') ;
                            }
                        }

                        return [
                            'software' => $user->getUserSoftwares()->all()
                        ] ;
                    } else {
                        throw new \yii\web\HttpException(400, 'Required fields missing: Expertises') ;
                    }
                    break;

                default:
                    throw new \yii\web\HttpException(400, "Invalid Request") ;
                    break ;
            }
        } else {
            throw new \yii\web\HttpException(400, 'Not Authorized') ;
        }
    }

    public function actionDeactivate() {
        $data = Yii::$app->request->post();

        if(!isset($data['password'])){
            throw new \yii\web\HttpException(400, "Invalid Request") ;
        } else {
            $current_password = AesSecurity::encrypt($data['password'], User::_AES_KEY) ;
        }
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one() ;
        if(!empty($user)) {
            if(Yii::$app->security->validatePassword($current_password, $user->password)) {
                $user->status = 3;
                if ($user->save()) {
                    return true;
                } else {
                    throw new \yii\web\HttpException(400, "Unable to save.") ;
                }
            } else {
                throw new \yii\web\HttpException(400, "Password incorrect") ;
            }

        } else {
            throw new \yii\web\HttpException(400, "Invalid User") ;
        }

    }

}
