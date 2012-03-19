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
class tx_messagequeue_message {

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
	 * @var tx_messagequeue_queue
	 */
	protected $queue;


	/**
	 * 
	 * Enter description here ...
	 * @param tx_messagequeue_queue $queue
	 * @param string $message
	 * @param int $priority
	 */
	public function __construct(tx_messagequeue_queue $queue, $messageText = NULL, $priority = 9) {
		$this->queue = $queue;
		$this->messageText = $messageText;
		$this->priority = $priority;
		$this->channel = $queue->getChannel();

	}

	/**
	 * @return string $channel
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * @return string
	 */
	public function getMessageText() {
		return $this->messageText;
	}


	/**
	 * @return int $priority
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 *
	 */
	public function queue() {
		$this->queue->putMessage($this);
	}

}


?>