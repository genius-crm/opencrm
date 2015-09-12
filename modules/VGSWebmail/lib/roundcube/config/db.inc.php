<?php

/*
 +-----------------------------------------------------------------------+
 | Configuration file for database access                                |
 |                                                                       |
 | This file is part of the Roundcube Webmail client                     |
 | Copyright (C) 2005-2012, The Roundcube Dev Team                       |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 |                                                                       |
 +-----------------------------------------------------------------------+

*/

include_once '../../../../config.inc.php';



$rcmail_config = array();

// PEAR database DSN for read/write operations
// format is db_provider://user:password@host/database 
// For examples see http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php
// currently supported db_providers: mysql, mysqli, pgsql, sqlite, mssql or sqlsrv

$rcmail_config['db_dsnw'] = 'mysql://' . $dbconfig['db_username'] . ':' . $dbconfig['db_password'] . '@' . $dbconfig['db_server'] . '/' . $dbconfig['db_name'];
// postgres example: 'pgsql://roundcube:pass@localhost/roundcubemail';
// Warning: for SQLite use absolute path in DSN:
// sqlite example: 'sqlite:////full/path/to/sqlite.db?mode=0646';

// PEAR database DSN for read only operations (if empty write database will be used)
// useful for database replication
$rcmail_config['db_dsnr'] = '';

// use persistent db-connections
// beware this will not "always" work as expected
// see: http://www.php.net/manual/en/features.persistent-connections.php
$rcmail_config['db_persistent'] = FALSE;

// you can define specific table names used to store webmail data
$rcmail_config['db_table_users'] = 'vtiger_vgswebmail_users';
$rcmail_config['db_table_identities'] = 'vtiger_vgswebmail_identities';
$rcmail_config['db_table_contacts'] = 'vtiger_vgswebmail_contacts';
$rcmail_config['db_table_contactgroups'] = 'vtiger_vgswebmail_contactgroups';
$rcmail_config['db_table_contactgroupmembers'] = 'vtiger_vgswebmail_contactgroupmembers';
$rcmail_config['db_table_session'] = 'vtiger_vgswebmail_session';
$rcmail_config['db_table_cache'] = 'vtiger_vgswebmail_cache';
$rcmail_config['db_table_cache_index'] = 'vtiger_vgswebmail_cache_index';
$rcmail_config['db_table_cache_thread'] = 'vtiger_vgswebmail_cache_thread';
$rcmail_config['db_table_cache_messages'] = 'vtiger_vgswebmail_cache_messages';
$rcmail_config['db_table_dictionary'] = 'vtiger_vgswebmail_dictionary';
$rcmail_config['db_table_searches'] = 'vtiger_vgswebmail_searches';
$rcmail_config['db_table_system'] = 'vtiger_vgswebmail_system';

// you can define specific sequence names used in PostgreSQL
$rcmail_config['db_sequence_users'] = 'user_ids';
$rcmail_config['db_sequence_identities'] = 'identity_ids';
$rcmail_config['db_sequence_contacts'] = 'contact_ids';
$rcmail_config['db_sequence_contactgroups'] = 'contactgroups_ids';
$rcmail_config['db_sequence_searches'] = 'search_ids';


// end db config file