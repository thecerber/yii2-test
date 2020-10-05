<?php
namespace app\services;

use app\models\db\Lead;
use app\models\db\User;
use app\models\request\LeadCreate;
use app\models\request\LeadRead;

/**
 * Сервис взаимодействия с сущностью Lead.
 *
 * @package app\services
 */
class LeadService
{
    /** @var int Количество элементов на страницу (пагинация) */
    const QUERY_LIMIT = 5;

    /**
     * Создание нового экземпляра сущности.
     *
     * @param LeadCreate $dto
     * @param User|null $user
     * @return Lead|null
     */
    public function create(LeadCreate $dto, User $user = null): ?Lead
    {
        $checkAttributes = ['name', 'email', 'phone'];

        foreach ($checkAttributes as $attr) {
            $exists = Lead::find()
                ->where([$attr => $dto->{$attr}])
                ->exists();

            if ($exists) {
                $dto->addError($attr, 'Lead with the same '. $dto->getAttributeLabel($attr) .' already exists');
                return null;
            }
        }

        $lead = new Lead();

        $lead->name = $dto->name;
        $lead->email = $dto->email;
        $lead->phone = $dto->phone;
        $lead->source_id = $dto->source_id;
        $lead->created_by = $user ? $user->id : \Yii::$app->user->id;
        $lead->status = Lead::STATUS_NEW;

        if ($lead->save(false)) {
            $lead->refresh();
            return $lead;
        }
        return null;
    }

    /**
     * Чтение (выборка) сущностей из хранилища (в нащем случае БД).
     *
     * @param LeadRead $dto
     * @return array
     */
    public function read(LeadRead $dto): array
    {
        $query = Lead::find();

        if ($dto->created_by) {
            $query->andWhere(['created_by' => $dto->created_by]);
        }
        if ($dto->status) {
            $query->andWhere(['status' => $dto->status]);
        }

        $totalCount = (clone $query)->count();

        if ($totalCount <= $dto->offset) {
            $resultRows = [];

        } else {
            $query->offset($dto->offset ?: 0)
                ->limit(static::QUERY_LIMIT);

            $resultRows = $query->all();

        }

        return [
            'limit' => static::QUERY_LIMIT,
            'offset' => $dto->offset ?: 0,
            'total' => $totalCount,
            'count' => count($resultRows),
            'rows' => $resultRows,
        ];
    }
}
