<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_messagequeue_message'] = array (
	'ctrl' => $TCA['tx_messagequeue_message']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,channel,message_text,priority'
	),
	'feInterface' => $TCA['tx_messagequeue_message']['feInterface'],
	'columns' => array (
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'channel' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:message_queue/locallang_db.xml:tx_messagequeue_message.channel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
			)
		),
		'message_text' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:message_queue/locallang_db.xml:tx_messagequeue_message.message_text',
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'priority' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:message_queue/locallang_db.xml:tx_messagequeue_message.priority',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'int'
			)
		),
		'error' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:message_queue/locallang_db.xml:tx_messagequeue_message.error',
			'config'  => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'int'
			)
		),
		'error_nextrun' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:message_queue/locallang_db.xml:tx_messagequeue_message.error_nextrun',
			'config'  => array (
				'type'    => 'input',
				'eval' => 'datetime'
			)
		),
		'error_message' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:message_queue/locallang_db.xml:tx_messagequeue_message.error_message',
			'config'  => array (
				'type'    => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1, channel, message_text, priority, error, error_nextrun, error_message')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>