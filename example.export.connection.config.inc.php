<?php

$config = [
    'db' => [
        'name' => '',
        'user' => '',
        'password' => '',
        'host' => '',
        'port' => '3306',
        'options' => []
    ],
];

$config = array_merge(
    $config,
    [
        //Общие параметры подключения
        'options' => [
            '--user=' . $config['db']['user'],
            '--password=' . $config['db']['password'],
            '--host=' . $config['db']['host'],
            '--port=' . $config['db']['port'],
            '--extended-insert=FALSE',
            '--dump-date=FALSE',
            '--skip-tz-utc',
        ],

        'structureOptions' => [
            '--skip-opt',
            '--skip-comments',
            '--add-drop-table', # for add DROP TABLE before CREATE TABLE (p.s. NOT USE —compact!]
            '--create-options', # add current AUTO_INCREMENT and other options
            '--routines', # add stored procedures
            '--triggers', # add triggers
            '--no-data', # skip data
        ],

        'dataOptions' => [
            '--skip-opt',
            '--skip-comments',
            '--disable-keys', # creating indexes after load data (for acceleration]
            '--replace', # for ignoring double rows and replace existing
            '--skip-triggers',
            '--default-character-set=utf8',
            '--set-charset',
            '--no-create-info', # skip structure
            '--no-tablespaces'
        ],
    ]
);
