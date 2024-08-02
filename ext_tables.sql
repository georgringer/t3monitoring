
CREATE TABLE tx_t3monitoring_domain_model_client (
	title varchar(255) DEFAULT '' NOT NULL,
	domain varchar(255) DEFAULT '' NOT NULL,
	comment text NOT NULL,
	basic_auth_username varchar(255) DEFAULT '' NOT NULL,
	basic_auth_password varchar(255) DEFAULT '' NOT NULL,
	host_header varchar(255) DEFAULT '' NOT NULL,
	force_ip_resolve enum('', 'v4', 'v6') DEFAULT '' NOT NULL,
	ignore_cert_errors tinyint(4) unsigned DEFAULT '0' NOT NULL,
	exclude_from_import tinyint(4) unsigned DEFAULT '0' NOT NULL,
	secret varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	php_version varchar(255) DEFAULT '' NOT NULL,
	mysql_version varchar(255) DEFAULT '' NOT NULL,
	disk_free_space bigint(20) unsigned DEFAULT '0' NOT NULL,
	disk_total_space bigint(20) unsigned DEFAULT '0' NOT NULL,
	insecure_core tinyint(1) unsigned DEFAULT '0' NOT NULL,
	outdated_core tinyint(1) unsigned DEFAULT '0' NOT NULL,
	insecure_extensions int(11) DEFAULT '0' NOT NULL,
	outdated_extensions int(11) DEFAULT '0' NOT NULL,
	error_message text DEFAULT '' NOT NULL,
	error_count int(11) DEFAULT '0' NOT NULL,
	extra_info text NOT NULL,
	extra_warning mediumtext NOT NULL,
	extra_danger text NOT NULL,
	last_successful_import int(11) DEFAULT '0' NOT NULL,
	extensions int(11) unsigned DEFAULT '0' NOT NULL,
	core int(11) unsigned DEFAULT '0',
	sla int(11) unsigned DEFAULT '0',
	tag tinytext
);

CREATE TABLE tx_t3monitoring_domain_model_extension (
	name varchar(255) DEFAULT '' NOT NULL,
	version varchar(255) DEFAULT '' NOT NULL,
	insecure tinyint(1) unsigned DEFAULT '0' NOT NULL,
	next_secure_version varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,
	last_updated datetime DEFAULT NULL,
	author_name varchar(255) DEFAULT '' NOT NULL,
	update_comment text NOT NULL,
	state int(11) DEFAULT '0' NOT NULL,
	category int(11) DEFAULT '0' NOT NULL,
	version_integer int(11) DEFAULT '0' NOT NULL,
	is_used tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_official tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_modified tinyint(1) unsigned DEFAULT '0' NOT NULL,
	is_latest tinyint(1) unsigned DEFAULT '0' NOT NULL,
	major_version int(11) unsigned DEFAULT '0' NOT NULL,
	minor_version int(11) unsigned DEFAULT '0' NOT NULL,
	last_bugfix_release varchar(255) DEFAULT '' NOT NULL,
	last_minor_release varchar(255) DEFAULT '' NOT NULL,
	last_major_release varchar(255) DEFAULT '' NOT NULL,
	typo3_min_version int(11) unsigned DEFAULT '0' NOT NULL,
	typo3_max_version int(11) unsigned DEFAULT '0' NOT NULL,
	serialized_dependencies text,

	KEY major (name,major_version)
);

CREATE TABLE tx_t3monitoring_domain_model_core (
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
	is_official tinyint(1) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_t3monitoring_domain_model_sla (
	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL
);

CREATE TABLE tx_t3monitoring_domain_model_tag (
	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL
);

CREATE TABLE tx_t3monitoring_client_extension_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	is_loaded tinyint(4) unsigned DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	state int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
