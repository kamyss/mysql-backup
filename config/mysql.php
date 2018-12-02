<?php

return [
    'db_host'      => getenv('DB_HOST'),
    'db_username'  => getenv('DB_USERNAME'),
    'db_password'  => getenv('DB_PASSWORD'),
    'db_databases' => getenv('DB_DATABASES'),
    'backup_days'  => getenv('BACKUP_DAYS'),
    'mysql_path'   => getenv('MYSQL_PATH'),
];
