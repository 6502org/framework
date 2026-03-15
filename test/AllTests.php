<?php

if (!defined('MAD_ENV')) {
    $env = getenv('MAD_ENV');
    define('MAD_ENV', $env !== false ? $env : 'test');
}
if (!defined('MAD_ROOT')) {
    require_once dirname(dirname(__FILE__)).'/config/environment.php';
}

// Create SQLite test database from SQL file if needed
$config = Horde_Yaml::loadFile(MAD_ROOT.'/config/database.yml');
$spec = $config[MAD_ENV];

if ($spec['adapter'] == 'pdo_sqlite') {
    $dbFile = $spec['database'];
    if ($dbFile[0] != '/') { $dbFile = MAD_ROOT . '/' . $dbFile; }
    $sqlFile = MAD_ROOT . '/db/tests/madmodel_test_sqlite.sql';

    if (!file_exists($dbFile) && file_exists($sqlFile)) {
        $db = new PDO("sqlite:$dbFile");
        $db->exec(file_get_contents($sqlFile));
    }
}
