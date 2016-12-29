<?php

namespace api\modules\v1\controllers;

use Yii;
use api\components\ActiveController;
use common\models\Project;
use common\models\User;
use common\models\Job;

/**
 * Static Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class StaticController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'api\searchmodels\CategorySearch';

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @param $page
     * @return null|string
     */
    public function actionViewPage($page)
    {

        $page = explode('/', $page);
        switch ($page[1]) {
            case 'index.html':
            case '':
            case '/':
                $data = [
                    'title' => "IndieFolio | Explore India&apos;s Creative Community",
                    'description' => "IndieFolio is an online market-network start-up for Indian creative professionals. IndieFolio serves as a platform for Indian creatives to connect with their peers and showcase, promote and monetize their work. The aim is to create an end-to-end solution for all the creative needs in the country. At its core, IndieFolio is an aggregator of creative services. Using its technology, IndieFolio matches parties depending on time, budget and other criteria. Its services will make the recruitment procedure shorter, more efficient and cost-effective.",
                    'keywords' => "indiefolio, design, portfolio, designer jobs, job, interior, fashion, photography, product, indian, creative, community,online portfolio, online portfolio site, creative professional platform, creative network, creative community, creative talent,talent, institute, organization, classes, vendor, creative creatures, Branding, UI/UX, UI Design, animation, Creative Direction, Visualization, Visual Effects, Ideating, Communication Design, Motion Design",
                    'author' => "IndieFolio",
                ];
                return $this->getShareData($data);
                break;

            case 'project':
                if (count($page) == 3) {
                    $uid = $page[2];
                    $project = Project::find()->where(['project_guid' => $uid, 'status' => 2])->one();
                    if ($project) {
                        $data = [
                            'title' => $this->sanitize($project->title),
                            'description' => $this->sanitize($project->description),
                            'image_url' => $project->coverImage,
                            'author' => $this->sanitize($project->user->fullName),
                        ];
                        return $this->getShareData($data);
                    } else {
                        return null;
                    }
                    return null;
                }
                break;

            case 'profile':
                if (count($page) == 3) {
                    $username = $page[2];
                    $user = User::find()->where(['username' => $username])->one();
                    if ($user) {
                        $data = [
                            'title' => $this->sanitize($user->fullName),
                            'description' => $this->sanitize($user->biography),
                            'image_url' => $user->profileImage,
                        ];
                        return $this->getShareData($data);
                    } else {
                        return null;
                    }
                    return null;
                }
                break;

            case 'jobs':
                if (count($page) == 3) {
                    $uid = $page[2];
                    $job = Job::find()->where(['job_guid' => $uid])->one();
                    $title = $job->title . ' - ' . $job->job_location ;

                    if ($job) {
                        $data = [
                            'title' => $this->sanitize($title),
                            'description' => $this->sanitize($job->description),
                            'author' => $this->sanitize($job->company_name),
                            'image_url' => 'https://www.indiefolio.com/assets/img/jobs-banner4.png',
                        ];
                        return $this->getShareData($data);
                    } else {
                        return null;
                    }
                    return null;
                }
                break;

            default:
                $username = $page[1];
                $user = User::find()->where(['username' => $username])->one();
                if ($user) {
                    $data = [
                        'title' => $this->sanitize($user->fullName),
                        'description' => $this->sanitize($user->biography),
                        'image_url' => $user->profileImage,
                    ];
                    return $this->getShareData($data);
                } else {
                    return null;
                }
                break;
        }

        return null;
    }

    /**
     * @param \yii\base\Action $action
     * @param null $result
     */
    public function afterAction($action, $result = null)
    {
        if (!is_null($result)) {
            header("Content-Type: text/html");
            echo $result;
        } else {
            header("HTTP/1.0 404 Not Found");
            die();
        }
        return;
    }

    /**
     * @param $str
     * @return string
     */
    private function sanitize($str)
    {
        return htmlentities($str, ENT_QUOTES);
    }

    /**
     * @param $data
     * @return string
     */
    private function getShareData($data)
    {
        $x = "<!DOCTYPE html>";
        $x .= "<html prefix='og: http://ogp.me/ns#'>";
        $x .= "<head>";

        $x .= "<meta property='fb:app_id' content='729418570495639' />";
        $x .= "<link rel=\"schema.DC\" href=\"http://purl.org/dc/elements/1.1/\">";

        if (isset($data['title'])) {
            $x .= "<title>{$data['title']}'</title>";
            $x .= "<meta property='og:title' content='{$data['title']}' />";
            $x .= "<meta property='twitter:title' content='{$data['title']}' />";
            $x .= "<meta name='DC.Title' content='{$data['title']}'>";
        }

        $x .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $x .= "<meta property='twitter:card' content='summary_large_image' />";
        $x .= "<meta property='twitter:site' content='@banana_bandy' />";

        $x .= '<meta name="DC.Language" content="English">
                <script type="application / ld + json">
            {
              "@context" : "http://schema.org",
              "@type" : "Organization",
              "name" : "IndieFolio",
              "url" : "https://www.indiefolio.com",
              "logo": "https://www.indiefolio.com/assets/img/logo.png",
              "sameAs" : [
                "https://blog.indiefolio.com",
                "https://www.facebook.com/indiefolio",
                "https://twitter.com/banana_bandy",
                "https://plus.google.com/+IndiefolioNetwork",
                "https://www.youtube.com/c/indiefolio",
                "https://www.linkedin.com/company/indiefolio",
                "https://www.instagram.com/indiefolio/"
              ]
            }



                </script>
                <meta name="viewport"
                      content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>

                <meta name="fragment" content="!"/>

                <link rel="icon" type="image/png" href="https://cdn.indiefolio.com/assets/img/logo.png">';
        $x .= '
                <meta name="mobile-web-app-capable" content="yes"/>
                <meta name="theme-color" content="#12CC66">

                <meta name="apple-mobile-web-app-capable" content="yes"/>
                <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
                <meta name="apple-mobile-web-app-title" content="IndieFolio Lite"/>
                <link rel="apple-touch-startup-image" href="https://cdn.indiefolio.com/assets/img/logo.png">
                <link rel="apple-touch-icon" type="image/png" href="https://cdn.indiefolio.com/assets/img/logo.png">
                <link rel="apple-touch-icon-precomposed" href="https://cdn.indiefolio.com/assets/img/logo.png">

                <meta name="msapplication-TileImage" content="https://cdn.indiefolio.com/assets/img/logo.png">
                <meta name="msapplication-square310x310logo" content="https://cdn.indiefolio.com/assets/img/logo.png">
                <meta name="msapplication-TileColor" content="#333333">

                <link rel="manifest" href="./manifest.json">

                <!-- Chrome, Firefox OS and Opera -->
                <meta name="theme-color" content="#12CC66">
                <!-- Windows Phone -->
                <meta name="msapplication-navbutton-color" content="#12CC66">
                <!-- iOS Safari -->
                <meta name="apple-mobile-web-app-status-bar-style" content="#12CC66">';
        if (isset($data['author'])) {
            $x .= "<meta name='author' content='{$data['author']}' />";
            $x .= "<meta name='DC.Creator' content='{$data['author']}'>";
        }

        if (isset($data['description'])) {
            $x .= "<meta property='og:description' content='{$data['description']}' />";
            $x .= "<meta property='twitter:description' content='{$data['description']}' />";
            $x .= "<meta property='DC.Description' content='{$data['description']}' />";
        }

        if (isset($data['keywords'])) {
            $x .= "<meta property='keywords' content='{$data['keywords']}' />";
        }

        if (isset($data['image_url'])) {
            $x .= "<meta property='og:image' content='{$data['image_url']}' />";
            $x .= "<meta property='twitter:image' content='{$data['image_url']}' />";
        }
        $x .= "</head>";
        $x .= "<body>";
        if (isset($data['title'])) {
            $x .= "<h1>{$data['title']}</h1>";
        }
        if (isset($data['description'])) {
            $x .= "<p>{$data['description']}</p>";
        }

        if (isset($data['image_url'])) {
            $x .= "<img src='{$data['image_url']}' />";
        }

        $x .= "</body>";
        $x .= "</html>";

        return $x;
    }

    /**
     * @param $num
     * @return float|string
     */
    private function thousandsCurrencyFormat($num)
    {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int)$x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }
}
