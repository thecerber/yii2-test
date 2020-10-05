<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%leads}}".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $status
 * @property int $source_id
 * @property int $created_by
 * @property string $created_at
 *
 * @property Source $source
 * @property User $createdBy
 */
class Lead extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_PROCESS = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILURE = 3;

    /**
     * Получить все валидные статусы сущности.
     * @return int[]
     */
    public static function getValidStatuses(): array
    {
        return [static::STATUS_NEW, static::STATUS_PROCESS, static::STATUS_SUCCESS, static::STATUS_FAILURE];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%leads}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'phone', 'status', 'source_id', 'created_by'], 'required'],
            [['status', 'source_id', 'created_by'], 'default', 'value' => null],
            [['status', 'source_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['phone'], 'unique'],
            [['status'], 'in', 'range' => static::getValidStatuses()],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Source::class, 'targetAttribute' => ['source_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'source_id' => 'Source ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Source]].
     *
     * @return ActiveQuery|Source
     */
    public function getSource()
    {
        return $this->hasOne(Source::class, ['id' => 'source_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery|User
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}
