<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Marc Bastian Heinrichs <mbh@mbh-software.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 *
 */
class tx_messagequeue_backend_DbBackend implements t3lib_Singleton {


	/**
	 * 
	 * @param tx_messagequeue_message $message
	 */
	public function saveMessage(tx_messagequeue_message $message) {
		$fields = array(
			'channel' => $message->getChannel(),
			'priority' => $message->getPriority(),
			'message_text' => serialize($message->getMessageText()),
			'crdate' => time(),
			'tstamp' => time()
		);
		$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_messagequeue_message', $fields);
	}

	/**
	 * 
	 * @param string $channel
	 * @param string $workerMessageClassName
	 * @param int $limit
	 * @param int $maxErrors
	 * @throws RuntimeException
	 */
	public function getMessages($channel, $limit = 10, $maxErrors = 10) {
		
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'][$channel]) || !is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'][$channel])) {
			throw new RuntimeException('Channel is not registered!');
		}

		$rows = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_messagequeue_message',
			'(error = 0 OR (error > 0 AND error < ' . (int)$maxErrors . ' AND error_nextrun > 0 AND error_nextrun < ' . time() . ')) AND channel = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($channel, 'tx_messagequeue_message') . t3lib_BEfunc::deleteClause('tx_messagequeue_message') . t3lib_BEfunc::BEenableFields('tx_messagequeue_message'),
			'',
			'crdate',
			$limit
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$rows[] = $row;
		}

		return $this->transformToMessageObjects($rows, $channel);
	}

	/**
	 *
	 * @param tx_messagequeue_worker_Message $message
	 */
	public function setProcessed(tx_messagequeue_worker_Message $message) {
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			'tx_messagequeue_message',
			'uid = ' . intval($message->getUid()),
			array(
				'hidden' => 1,
				'error' => 0,
				'error_nextrun' => 0,
				'error_message' => '',
				'tstamp' => time()
			)
		);
	}

	/**
	 *
	 * @param tx_messagequeue_worker_Message $message
	 * @param string $errorMessage
	 */
	public function setHasError(tx_messagequeue_worker_Message $message, $errorMessage = '') {
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			'tx_messagequeue_message',
			'uid = ' . intval($message->getUid()),
			array(
				'error' => ($message->getError() + 1),
				'error_message' => $errorMessage,
				//TODO make configurable
				'error_nextrun' => time() + (900 * ($message->getError() + 1)),
				'tstamp' => time()
			)
		);
	}

	/**
	 * TODO: move to abstract backend class
	 * @param array $rows
	 * @param string $workerMessageClassName
	 * @param string $channel
	 */
	protected function transformToMessageObjects($rows, $channel) {
		$queueConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'][$channel];
		$workerMessageClassName = isset($queueConfiguration['workerMessageClass']) ? $queueConfiguration['workerMessageClass'] : 'tx_messagequeue_worker_Message';

		$objects = array();
		foreach ($rows as $row) {
			$objects[] = $this->transformToMessageObject($row, $workerMessageClassName, $channel);
		}
		return $objects;
	}

	/**
	 * TODO: move to abstract backend class
	 *
	 * @param array $row
	 * @param string $workerMessageClassName
	 * @param string $channel
	 */
	protected function transformToMessageObject($row, $workerMessageClassName, $channel) {
		$object = t3lib_div::makeInstance($workerMessageClassName, $this, $row['uid'], $channel, unserialize($row['message_text']), $row['priority'], $row['error']);
		return $object;
	}

}

?>