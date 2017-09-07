<?php
switch($_GET['clean']) {
    case 'log':
        clean_log_tables();
    break;
    case 'var':
        clean_var_directory();
    break;
}

function clean_log_tables() {
    $xml = simplexml_load_file('./app/etc/local.xml', NULL, LIBXML_NOCDATA);

    if(is_object($xml)) {
        $db['host'] = $xml->global->resources->default_setup->connection->host;
        $db['name'] = $xml->global->resources->default_setup->connection->dbname;
        $db['user'] = $xml->global->resources->default_setup->connection->username;
        $db['pass'] = $xml->global->resources->default_setup->connection->password;
        $db['pref'] = $xml->global->resources->db->table_prefix;

        $tables = array(
            'adminnotification_inbox',
            'dataflow_batch_export',
            'dataflow_batch_import',
            'log_customer',
            'log_quote',
            'log_summary',
            'log_summary_type',
            'log_url',
            'log_url_info',
            'log_visitor',
            'log_visitor_info',
            'log_visitor_online',
            'index_event',
            'report_event',
            'report_viewed_product_index',
            'report_compared_product_index'
        );

        mysql_connect($db['host'], $db['user'], $db['pass']) or die(mysql_error());
        mysql_select_db($db['name']) or die(mysql_error());

        foreach($tables as $table) {
            @mysql_query('TRUNCATE `'.$db['pref'].$table.'`');
        }
    } else {
        exit('Unable to load local.xml file');
    }
}

function clean_var_directory() {
    $dirs = array(
        'var/cache/',
        'var/locks/',
        'var/session/',
        'var/tmp/'
    );

    foreach($dirs as $dir) {
        exec('rm -rf '.$dir);
    }
} ?>