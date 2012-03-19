#
# Table structure for table 'tx_messagequeue_message'
#
CREATE TABLE tx_messagequeue_message (
	uid bigint(14) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	channel varchar(30) DEFAULT '' NOT NULL,
	message_text text NOT NULL,
	priority tinyint(4) DEFAULT '0' NOT NULL,
	error tinyint(4) DEFAULT '0' NOT NULL,
	error_nextrun int(11) DEFAULT '0' NOT NULL,
	error_message text NOT NULL,
	
	
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY channel (channel,deleted,hidden)
);