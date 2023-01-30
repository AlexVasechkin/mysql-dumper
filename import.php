<?php

try {
    require_once './config.inc.php';
    require_once './import.connection.config.inc.php';
} catch (Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
}

$connection = new PDO(
    sprintf('mysql:host=%s;dbname=%s;charset=utf8', $config['db']['host'], $config['db']['name']),
    $config['db']['user'],
    $config['db']['password'],
    $config['db']['options']
);

$ddlFileList = scandir($ddlDir);
(!is_array($ddlFileList) && ($ddlFileList = []));
foreach ($ddlFileList as $fileName) {
    $query = file_get_contents($ddlDir . '/' . $fileName);
    if ($query) {
        try {
            $connection->exec($query);
        } catch (Throwable $e) {
            $e->getMessage();
        }
    }
}

$dataFileList = scandir($dataDir);
(!is_array($dataFileList) && ($dataFileList = []));
foreach ($dataFileList as $fileName) {
    try {
        system(sprintf($mysqlbinDir . 'mysql --host=%s --port=%s --user=%s -p%s %s < %s',
            $config['db']['host'],
            $config['db']['port'],
            $config['db']['user'],
            $config['db']['password'],
            $config['db']['name'],
            $dataDir . '/' . $fileName)
        );
    } catch (\Throwable $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}
