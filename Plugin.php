<?php

namespace Kanboard\Plugin\TaskMoveColumnNotMovedPeriodByPriority;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\TaskMoveColumnNotMovedPeriodByPriority\Action\TaskMoveColumnNotMovedPeriodByPriority;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskMoveColumnNotMovedPeriodByPriority($this->container));
    }

        public function getPluginName()
    {
        return 'TaskMoveColumnNotMovedPeriodByPriority';
    }

    public function getPluginDescription()
    {
        return t('Move the task to another column when not moved during a given period based on priority');
    }

    public function getPluginAuthor()
    {
        return 'Lasse Faber';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/DKFZ-NGSCF/kanboard-TaskMoveColumnNotMovedPeriodByPriority';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.43';
    }
}