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
class tx_messagequeue_queue {

	/**
	 * 
	 * @var string
	 */
	protected $channel;

	/**
	 * 
	 * @var tx_messagequeue_backend_DbBackend
	 */
	protected $backend;

	/**
	 * 
	 * @param string $channel
	 * @throws RuntimeException
	 */
	public function __construct($channel) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'][$channel]) || !is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'][$channel])) {
			throw new RuntimeException('Channel is not registered!');
		}
		
		$queueConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'][$channel];
		$backendClassName = isset($queueConfiguration['backendClass']) ? $queueConfiguration['backendClass'] : 'tx_messagequeue_backend_DbBackend';

		$this->channel = $channel;
		$this->backend = t3lib_div::makeInstance($backendClassName);
	}

	/**
	 * @return string $channel
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * 
	 * @param tx_messagequeue_message $message
	 */
	public function putMessage(tx_messagequeue_message $message) {
		$this->backend->saveMessage($message);
	}


	/**
	 * 
	 * @param string $message
	 * @param int $priority
	 */
	public function createMessage($messageText, $priority = 9) {
		return t3lib_div::makeInstance('tx_messagequeue_message',
			$this,
			$messageText,
			$priority
		);
	}


}

?>