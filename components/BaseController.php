<?php
namespace app\components;

use yii\base\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;

/**
 * Базовый контроллер API от которого наследуются все контроллеры приложения и который реализует все основные
 * маханизмы взаимодействия с клиентом.
 *
 * @package app\components
 */
class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'authenticator' => [
                'class' => HttpBasicAuth::class,
            ],
            'rateLimiter' => [
                'class' => RateLimiter::class,
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbActions(),
            ],
        ];
    }

    /**
     * Инструкции для фильтра: список действий с разрешёнными для них типами запросов.
     * @return array
     */
    protected function verbActions(): array
    {
        return [];
    }

    /**
     * Корректно уведомить клиент об ошибке.
     *
     * @param string $message
     * @param int $code
     * @return string[]
     */
    protected function sendError(string $message, int $code = 400): array
    {
        \Yii::$app->response->statusCode = $code;

        return [
            'status' => 'error',
            'message' => $message,
        ];
    }

    /**
     * Передать клиенту данные в случае успеха.
     *
     * @param array|null $data
     * @return array
     */
    public function sendOk(array $data = null): array
    {
        return [
            'status' => 'ok',
            'data' => $data,
        ];
    }
}
