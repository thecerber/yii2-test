<?php
namespace app\models\request;

use app\components\Model;
use app\models\db\Lead;
use app\models\db\User;

/**
 * Модель запроса к API на получение лидов (с пагинацией и фильтрами по владельцу лида и статусу).
 *
 * @package app\models\request
 */
class LeadRead extends Model
{
    public ?int $offset = null;
    public ?int $created_by = null;
    public ?int $status = null;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['offset', 'created_by'], 'integer', 'min' => 1],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['status'], 'in', 'range' => Lead::getValidStatuses()],
        ];
    }
}
