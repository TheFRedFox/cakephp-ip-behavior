<?php

namespace IpBehavior\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\ORM\Behavior;
use UnexpectedValueException;

class IpBehavior extends Behavior
{

    /** @var Request $_request */
    public static $_request;

    protected $_defaultConfig = [
        'implementedFinders' => [],
        'implementedMethods' => [],
        'fields' => ['ip' => 'new'],
    ];

    public function initialize(array $config)
    {
        parent::initialize($config);

        if (isset($config['fields'])) {
            $result = [];
            foreach ((array)$config['fields'] as $field => $when) {
                if (is_int($field)) {
                    $field = $when;
                    $when = 'new';
                }
                $result[$field] = $when;
            }
            $this->config('fields', $result, false);
        }
    }

    public function beforeSave(Event $event, EntityInterface $entity)
    {
        if ($entity === null) {
            return true;
        }

        $isNew = $entity->isNew();

        $fields = $this->config('fields');

        $ip = self::$_request->clientIp();

        foreach ($fields as $field => $when) {
            $when = strtolower($when);
            if (!in_array($when, ['always', 'new'])) {
                throw new UnexpectedValueException(
                    sprintf('"When" should be one of "always", "new". The passed value "%s" is invalid', $when)
                );
            }

            switch ($when) {
                case 'always': {
                    $entity->set($field, $ip);
                    continue;
                }
                    break;
                case 'new': {
                    if ($isNew) {
                        $entity->set($field, $ip);
                        continue;
                    }
                }
                    break;
            }

        }

        return true;
    }
}