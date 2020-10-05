<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=pgsql;port=5432;dbname=yii2test',
    'username' => 'yii2test',
    'password' => 'yii2test',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
