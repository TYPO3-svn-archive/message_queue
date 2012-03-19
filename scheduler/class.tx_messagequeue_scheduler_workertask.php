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
class tx_messagequeue_scheduler_WorkerTask extends tx_scheduler_Task {

	public $selectedChannels = array();

	/**
	 * Executes the worker task and returns TRUE if the execution was
	 * succesfull
	 *
	 * @return	boolean	returns TRUE on success, FALSE on failure
	 */
	public function execute() {

		// fetch messages by channel from backend, create worker, run worker
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels']) && is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'] as $channel => $queueConfiguration) {
				if (!in_array($channel, $this->selectedChannels)) {
					continue;
				}
				if (!isset($queueConfiguration['workerClass']) || strlen($queueConfiguration['workerClass']) == 0) {
					throw new Exception('You need to define a worker class');
				}
				$worker = t3lib_div::makeInstance($queueConfiguration['workerClass']);
				if (!($worker instanceof tx_messagequeue_worker_Worker)) {
					throw new Exception('Worker have to implement tx_messagequeue_worker_Worker');
				}
				$backendClassName = isset($queueConfiguration['backendClass']) ? $queueConfiguration['backendClass'] : 'tx_messagequeue_backend_DbBackend';
				$backend = t3lib_div::makeInstance($backendClassName);
				$limit = isset($queueConfiguration['maxMessagesPerRun']) ? (int)$queueConfiguration['maxMessagesPerRun'] : 5;
		
				$messages = $backend->getMessages($channel, $limit);
				$worker->setMessages($messages);
				$worker->execute();
			}
		}

		return TRUE;
	}

	/**
	 *
	 * @return	string	Information to display
	 */
	public function getAdditionalInformation() {
		return implode(',', $this->selectedChannels);
	}

}

?>