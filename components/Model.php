<?php
namespace app\components;

/**
 * Базовый класс для моделей описывающих запросы Клиента.
 *
 * @package app\components
 */
class Model extends \yii\base\Model
{
    /**
     * @inheritdoc
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * Получить самое первое сообщение об ошибке валидации атрибута.
     *
     * @return string
     */
    public function getFirstErrorMessage(): string
    {
        $firstErrorAttribute = array_key_first($this->firstErrors);
        return $this->getFirstError($firstErrorAttribute);
    }
}
