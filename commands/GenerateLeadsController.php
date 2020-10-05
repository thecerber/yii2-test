<?php
namespace app\commands;

use app\models\db\Lead;
use app\models\db\User;
use app\models\request\LeadCreate;
use app\services\LeadService;
use Faker\Factory;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Генератор случайных тестовых данных для сущности Leads.
 *
 * @package app\commands
 */
class GenerateLeadsController extends Controller
{
    /**
     * @var LeadService Сервис для взаимодействия с сущностью Lead
     */
    private LeadService $service;

    /**
     * GenerateLeadsController constructor.
     *
     * @param $id
     * @param $module
     * @param LeadService $service
     * @param array $config
     */
    public function __construct($id, $module, LeadService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Генерация тестовых данных и наполнение ими БД.
     *
     * @return int
     */
    public function actionIndex(): int
    {
        $faker = Factory::create();

        // для каждого пользователя
        foreach (User::findAll(['id' => [1, 2]]) as $user) {
            // каждого статуса
            foreach (Lead::getValidStatuses() as $status) {
                // по 10 лидов
                for ($i=0; $i<10; $i++) {
                    $model = new LeadCreate([
                        'name' => $faker->name,
                        'email' => $faker->email,
                        'phone' => $faker->e164PhoneNumber,
                        'source_id' => 1,
                    ]);

                    if ($model->validate()) {
                        $lead = $this->service->create($model, $user);

                        if ($lead) {
                            $lead->status = $status;
                            $lead->save();

                            echo $lead->id . PHP_EOL;
                        }
                    }
                }
            }
        }

        return ExitCode::OK;
    }
}
