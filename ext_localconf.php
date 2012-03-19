<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// adding scheduler task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_messagequeue_scheduler_WorkerTask'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:message_queue/locallang.xml:scheduler_workertask_title',
	'description'      => 'LLL:EXT:message_queue/locallang.xml:scheduler_workertask_description',
	'additionalFields' => 'tx_messagequeue_scheduler_WorkerTask_AdditionalFieldProvider',
);


?>