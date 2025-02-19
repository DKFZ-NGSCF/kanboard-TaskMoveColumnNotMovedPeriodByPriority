<?php

namespace Kanboard\Plugin\TaskMoveColumnNotMovedPeriodByPriority\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;

/**
 * Move a task to another column when not moved during a given period
 *
 * @package Kanboard\Action
 * @author  Lasse Faber
 */
class TaskMoveColumnNotMovedPeriodByPriority extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Move the task to another column when not moved during a given period based on priority');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(TaskModel::EVENT_DAILY_CRONJOB);
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'priority' => t('Priority'),
            'duration' => t('Duration in days'),
            'src_column_id' => t('Source column'),
            'dest_column_id' => t('Destination column'),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array('tasks');
    }

    /**
     * Execute the action (close the task)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $results = array();
        $max = (int)$this->getParam('duration') * 86400;
        $priority = (int)$this->getParam('priority');

        foreach ($data['tasks'] as $task) {
            if ($priority != $task['priority']) {
                continue;
            }
            $duration = time() - $task['date_moved'];

            if ($duration > $max && $task['column_id'] == $this->getParam('src_column_id')) {
                $results[] = $this->taskPositionModel->movePosition(
                    $task['project_id'],
                    $task['id'],
                    $this->getParam('dest_column_id'),
                    1,
                    $task['swimlane_id'],
                    false
                );
            }
        }

        return in_array(true, $results, true);
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return count($data['tasks']) > 0;
    }
}