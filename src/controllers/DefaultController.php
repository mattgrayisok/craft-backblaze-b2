<?php

namespace mattgrayisok\backblazeb2\controllers;

use Craft;
use craft\web\Controller as BaseController;
use yii\web\Response;

/**
 * This controller provides functionality to load data from Backblaze.
 *
 * @author Matt Gray <matt@mattgrayisok.com>
 * @since 1.0
 */
class DefaultController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //$this->defaultAction = 'load-bucket-data';
    }

    /**
     * Load bucket data for specified credentials.
     *
     * @return Response
     */
    // public function actionLoadBucketData()
    // {
    //     $this->requirePostRequest();
    //     $this->requireAcceptsJson();
    //
    //     $request = Craft::$app->getRequest();
    //     $accountId = Craft::parseEnv($request->getRequiredBodyParam('keyId'));
    //     $applicationKey = Craft::parseEnv($request->getRequiredBodyParam('secret'));
    //
    //     try {
    //         return $this->asJson(Volume::loadBucketList($accountId, $applicationKey));
    //     } catch (\Throwable $e) {
    //         return $this->asErrorJson($e->getMessage());
    //     }
    // }
}
