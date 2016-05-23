#
# Table structure for table 'tx_t3monitoring_domain_model_client'
#
CREATE TABLE tx_t3monitoring_domain_model_client (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	domain varchar(255) DEFAULT '' NOT NULL,
	secret varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	php_version varchar(255) DEFAULT '' NOT NULL,
	mysql_version varchar(255) DEFAULT '' NOT NULL,
	insecure_core tinyint(1) unsigned DEFAULT '0' NOT NULL,
	outdated_core tinyint(1) unsigned DEFAULT '0' NOT NULL,
	insecure_extensions int(11) DEFAULT '0' NOT NULL,
	outdated_extensions int(11) DEFAULT '0' NOT NULL,
	error_message varchar(255) DEFAULT '' NOT NULL,
	extra_info text NOT NULL,
	extra_warning text NOT NULL,
	extra_danger text NOT NULL,
	last_successful_import int(11) DEFAULT '0' NOT NULL,
	extensions int(11) unsigned DEFAULT '0' NOT NULL,
	core int(11) unsigned DEFAULT '0',
	sla int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_t3monitoring_domain_model_extension'
#
CREATE TABLE tx_t3monitoring_domain_model_extension (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	version varchar(255) DEFAULT '' NOT NULL,
	insecure tinyint(1) unsigned DEFAULT '0' NOT NULL,
	next_secure_version varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,
	last_updated datetime DEFAULT NULL,
	author_name varchar(255) DEFAULT '' NOT NULL,
	update_comment varchar(255) DEFAULT '' NOT NULL,
	state int(11) DEFAULT '0' NOT NULL,
	category int(11) DEFAULT '0' NOT NULL,
	version_integer int(11) DEFAULT '0' NOT NULL,
	is_used tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_official tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_modified tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_latest tinyint(1) unsigned DEFAULT '0' NOT NULL,
	last_bugfix_release varchar(255) DEFAULT '' NOT NULL,
	last_minor_release varchar(255) DEFAULT '' NOT NULL,
	last_major_release varchar(255) DEFAULT '' NOT NULL,
	serialized_dependencies text,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY major (name,major_version),
);

#
# Table structure for table 'tx_t3monitoring_domain_model_core'
#
CREATE TABLE tx_t3monitoring_domain_model_core (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	version varchar(255) DEFAULT '' NOT NULL,
	insecure tinyint(1) unsigned DEFAULT '0' NOT NULL,
	next_secure_version varchar(255) DEFAULT '' NOT NULL,
	type int(11) DEFAULT '0' NOT NULL,
	release_date datetime DEFAULT NULL,
	latest varchar(255) DEFAULT '' NOT NULL,
	stable varchar(255) DEFAULT '' NOT NULL,
	is_stable tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_active tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_latest tinyint(1) unsigned DEFAULT '0' NOT NULL,
	version_integer int(11) DEFAULT '0' NOT NULL,
	is_used tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_official tinyint(1) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_t3monitoring_domain_model_sla'
#
CREATE TABLE tx_t3monitoring_domain_model_sla (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_t3monitoring_client_extension_mm'
#
CREATE TABLE tx_t3monitoring_client_extension_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_t3monitoring_client_extension_mm (
  is_loaded tinyint(4) unsigned DEFAULT '0' NOT NULL,
  title varchar(255) DEFAULT '' NOT NULL,
  state int(11) DEFAULT '0' NOT NULL,
);

CREATE TABLE tx_t3monitoring_domain_model_extension (
  major_version int(11) unsigned DEFAULT '0' NOT NULL,
  minor_version int(11) unsigned DEFAULT '0' NOT NULL,
);