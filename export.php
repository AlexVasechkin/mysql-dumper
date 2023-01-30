<?php

try {
    require_once './config.inc.php';
    require_once './export.connection.config.inc.php';
} catch (Throwable $e) {
    echo $e->getMessage();
}

$connection = new PDO(
    sprintf('mysql:host=%s;dbname=%s;charset=utf8', $config['db']['host'], $config['db']['name']),
    $config['db']['user'],
    $config['db']['password'],
    $config['db']['options']
);

$tableList = $connection->query('show tables')->fetchAll(PDO::FETCH_COLUMN);

if (!file_exists($ddlDir)) {
    mkdir($ddlDir);
}

$structureOptions = implode(' ', $config['options']) . ' ' . implode(' ', $config['structureOptions']);

foreach ($tableList as $tableName) {
    $filename = $ddlDir . '/' . $tableName . '.sql';

    try {
        file_put_contents(
            $filename,
            $connection->query(sprintf('SHOW CREATE TABLE %s', $tableName))->fetchColumn(1) ?? ''
        );
    } catch (Throwable $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}

if (!file_exists($dataDir)) {
    mkdir($dataDir);
}

$dataOptions = trim(implode(' ', $config['options']) . ' ' . implode(' ', $config['dataOptions']));
foreach ($tableList as $tableName) {
    $filename = $dataDir . '/' . $tableName . '.sql';

    try {
        system(
            $mysqlbinDir . 'mysqldump ' . $dataOptions . ' --result-file="' . $filename . '" ' . $config['db']['name'] . ' ' . $tableName
        );
    } catch (Throwable $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}
