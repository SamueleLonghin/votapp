<?php

$port = getenv("MYSQL_DBPORT");
$host = getenv("MYSQL_DBHOST");
$user = getenv("MYSQL_DBUSER");
$password = getenv("MYSQL_DBPASS");
$db_name = getenv("MYSQL_DBNAME");

//var_dump($host, $port, $user, $password, $db_name);

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=mysql;dbname=$db_name",
//    'dsn' => "mysql:host=$host:$port;dbname=$db_name",
    'username' => 'root',
//    'username' => $user,
//    'password' => $password,
    'password' => 'secret',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
