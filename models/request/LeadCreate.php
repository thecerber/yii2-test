<?php
namespace app\models\request;

use app\components\Model;
use app\models\db\Source;

/**
 * Модель запроса к API на добавление лида.
 *
 * @package app\models\request
 */
class LeadCreate extends Model
{
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;

    public ?int $source_id = null;

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'email' => 'E-Mail',
            'phone' => 'Phone Number',
            'source_id' => 'Source ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'phone', 'source_id'], 'required'],

            [['name', 'email', 'phone'], 'trim'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],

            [['email'], 'email'],
            [['email'], 'filter', 'filter' => 'mb_strtolower'],

            [['source_id'], 'integer'],
            [['source_id'], 'in', 'range' => Source::find()->select('id')->asArray()->column()],
        ];
    }
}
