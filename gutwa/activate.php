<?php
/*
*
* Gutwa Table creator
*
*/

// create tables if not exist
$prefix = elgg_get_config('dbprefix');
$tables = get_db_tables();
if (! in_array("{$prefix}user_payment_details", $tables)) {
    run_sql_script(__DIR__ . '/sql/create_tables.sql');
    system_message("Table created: {$prefix}user_payment_details");
}