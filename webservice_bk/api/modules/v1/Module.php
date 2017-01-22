<?php
namespace api\modules\v1;

/**
 * IndieFolio API V1 module
 *
 * @author Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @since 1.0
 * Class Module
 * @package api\modules\v1
 */

class Module extends \api\components\APIModule
{
    /**
     * @var string
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * Setup headers
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        header('Access-Control-Expose-Headers: X-Pagination-Current-Page, X-Pagination-Page-Count, X-Pagination-Per-Page, X-Pagination-Total-Count');
//        $headers = \Yii::$app->response->headers;
//        $headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60)));
//        $headers->set('Cache-Control', 'public, max-age=3600');
    }
}
