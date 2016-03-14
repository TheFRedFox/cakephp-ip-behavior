<?php
namespace IpBehavior\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use IpBehavior\Model\Behavior\IpBehavior;

class IpBehaviorFilter extends DispatcherFilter
{
    public function beforeDispatch(Event $event)
    {
        $request = $event->data['request'];
        IpBehavior::$_request = $request;
        parent::afterDispatch($event);
    }
}