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
class tx_messagequeue_worker_Message {

	/**
	 * @var int
	 */
	protected $uid;

	/**
	 * @var string
	 */
	protected $channel;

	/**
	 * @var string
	 */
	protected $messageText;

	/**
	 * @var int
	 */
	protected $priority;

	/**
	 * @var int
	 */
	protected $error;

	/**
	 *
	 * @var tx_messagequeue_backend_DbBackend
	 */
	protected $backend;

	/**
	 * 
	 * @param tx_messagequeue_backend_DbBackend $backend
	 * @param int $uid
	 * @param string $channel
	 * @param string $messageText
	 * @param int $priority
	 * @param int $error
	 */
	public function __construct(tx_messagequeue_backend_DbBackend $backend, $uid, $channel, $messageText = '', $priority = 9, $error = 0) {
		$this->backend = $backend;
		$this->messageText = $messageText;
		$this->priority = $priority;
		$this->error = $error;
		$this->channel = $channel;
		$this->uid = $uid;
	}

	/**
	 * 
	 * @return int
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * @return string $channel
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * @return string $message
	 */
	public function getMessageText() {
		return $this->messageText;
	}


	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @return int
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 *
	 */
	public function setProcessed() {
		$this->backend->setProcessed($this);
	}

	/**
	 *
	 * @param string $errorMessage
	 */
	public function setHasError($errorMessage = '') {
		$this->backend->setHasError($this, $errorMessage);
	}

}


?>