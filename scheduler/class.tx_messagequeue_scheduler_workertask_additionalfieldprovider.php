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
class tx_messagequeue_scheduler_WorkerTask_AdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {
	/**
	 * Add a multi select box with all available channels.
	 *
	 * @param array Reference to the array containing the info used in the add/edit form
	 * @param object When editing, reference to the current task object. Null when adding.
	 * @param tx_scheduler_Module Reference to the calling object (Scheduler's BE module)
	 * @return array Array containg all the information pertaining to the additional fields
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {


			// Initialize selected fields
		if (empty($taskInfo['messagequeue_scheduler_workertask_selectedChannels'])) {
			$taskInfo['messagequeue_scheduler_workertask_selectedChannels'] = array();
			if ($parentObject->CMD == 'add') {
				$taskInfo['messagequeue_scheduler_workertask_selectedChannels'][] = '';
			} elseif ($parentObject->CMD == 'edit') {
					// In case of editing the task, set to currently selected value
				$taskInfo['messagequeue_scheduler_workertask_selectedChannels'] = $task->selectedChannels;
			}
		}

		$fieldName = 'tx_scheduler[messagequeue_scheduler_workertask_selectedChannels][]';
		$fieldId = 'messagequeue_scheduler_workertask_selectedChannels';
		$fieldOptions = $this->getChannelOptions($taskInfo['messagequeue_scheduler_workertask_selectedChannels']);
		$fieldHtml =
			'<select name="' . $fieldName . '" id="' . $fieldId . '" class="wide" size="10" multiple="multiple">' .
				$fieldOptions .
			'</select>';

		$additionalFields[$fieldId] = array(
			'code' => $fieldHtml,
			'label' => 'Selected Channels',
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldId,
		);

		return $additionalFields;
	}

	/**
	 * Checks that all selected channels exist in available channels list
	 *
	 * @param array Reference to the array containing the data submitted by the user
	 * @param tx_scheduler_Module Reference to the calling object (Scheduler's BE module)
	 * @return boolean True if validation was ok (or selected class is not relevant), false otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$validData = TRUE;
		$availableChannels = $this->getRegisteredChannels();
		$invalidChannels = array_diff($submittedData['messagequeue_scheduler_workertask_selectedChannels'], $availableChannels);
		if (!empty($invalidChannels)) {
			$validData = FALSE;
		}

		return $validData;
	}

	/**
	 * Save selected channels in task object
	 *
	 * @param array Contains data submitted by the user
	 * @param tx_scheduler_Task Reference to the current task object
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->selectedChannels = $submittedData['messagequeue_scheduler_workertask_selectedChannels'];
	}

	/**
	 * Build select options of available channels and set currently selected channels
	 *
	 * @param array Selected channels
	 * @return string HTML of selectbox options
	 */
	protected function getChannelOptions(array $selectedChannels) {
		$options = array();

		$availableChannels = $this->getRegisteredChannels();
		foreach ($availableChannels as $channelName) {
			if (in_array($channelName, $selectedChannels)) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			$options[] =
				'<option value="' . $channelName .  '"' . $selected . '>' .
					$channelName .
				'</option>';
		}

		return implode($options);
	}

	/**
	 *
	 * @return array Registered channels
	 */
	protected function getRegisteredChannels() {
		$channels = array();
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'])) {
			$channels = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['message_queue']['channels'];
		}
		$channels = array_merge(array('' => ''), $channels);
		return array_keys($channels);
	}
} 


?>