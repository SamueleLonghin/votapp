<?php

$port = getenv("MYSQL_DBPORT") ?: '3306';
$host = getenv("MYSQL_DBHOST") ?: 'localhost';
$user = getenv("MYSQL_DBUSER") ?: 'root';
$password = getenv("MYSQL_DBPASS") ?: 'secret';
$db_name = getenv("MYSQL_DBNAME") ?: 'votapp';

//var_dump($host, $port, $user, $password, $db_name);

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=$host:$port;dbname=$db_name",
//    'dsn' => "mysql:host=$host:$port;dbname=$db_name",
    'username' => $user,
//    'username' => $user,
    'password' => $password,
//    'password' => 'secret',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
