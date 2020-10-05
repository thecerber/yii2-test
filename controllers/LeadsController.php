<?php
namespace app\controllers;

use app\components\BaseController;
use app\models\request\LeadCreate;
use app\models\request\LeadRead;
use app\services\LeadService;
use yii\base\Module;

/**
 * Контроллер управления лидами.
 *
 * @package app\controllers
 */
class LeadsController extends BaseController
{
    /**
     * @var LeadService Сервис для взаимодействия с сущностью Lead
     */
    protected LeadService $service;

    /**
     * LeadsController constructor.
     *
     * @param $id
     * @param $module
     * @param LeadService $service
     * @param array $config
     */
    public function __construct(string $id, Module $module, LeadService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function verbActions(): array
    {
        return [
            'add' => ['post'],
            'get' => ['get'],
        ];
    }

    /**
     * Метод добавления лида (POST only).
     *
     * @return array
     */
    public function actionAdd(): array
    {
        $model = new LeadCreate();
        $model->load(\Yii::$app->request->bodyParams);

        if (!$model->validate()) {
            return $this->sendError($model->getFirstErrorMessage());
        }

        $lead = $this->service->create($model);

        if (!$lead) {
            return $this->sendError(
                $model->hasErrors() ? $model->getFirstErrorMessage() : 'An error occurred while trying to create a Lead'
            );
        }

        return $this->sendOk($lead->toArray());
    }

    /**
     * Метод получения лидов (GET only): пагинация + фильтр по created_by & status.
     *
     * @return array
     */
    public function actionGet(): array
    {
        $model = new LeadRead();
        $model->load(\Yii::$app->request->bodyParams);

        if (!$model->validate()) {
            return $this->sendError($model->getFirstErrorMessage());
        }

        return $this->sendOk($this->service->read($model));
    }
}
