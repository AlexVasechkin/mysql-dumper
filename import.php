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
$exceptions = array_map(function ($table) { return $table . '.sql'; }, $config['exclude']);
$dataFileList = array_diff($dataFileList, $exceptions);
sort($dataFileList, SORT_NATURAL | SORT_FLAG_CASE);

if (is_string($config['start_from'])) {
    $index = null;
    for ($i = 0; $i < count($dataFileList); $i++) {
        if ($dataFileList[$i] === ($config['start_from'] . '.sql')) {
            $index = $i;
            break;
        }
    }

    if ($index) {
        $dataFileList = array_slice($dataFileList, $index);
    }
}

foreach ($dataFileList as $fileName) {
    try {
        echo sprintf('started: %s', $fileName) . PHP_EOL;
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
