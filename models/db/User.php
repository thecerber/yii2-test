<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $access_token
 * @property string $created_at
 *
 * @property Lead[] $leads
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'access_token'], 'required'],
            [['created_at'], 'safe'],
            [['username', 'access_token'], 'string', 'max' => 255],
            [['access_token'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'access_token' => 'Access Token',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Leads]].
     *
     * @return ActiveQuery|Lead[]
     */
    public function getLeads()
    {
        return $this->hasMany(Lead::class, ['created_by' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }
}
