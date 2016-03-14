<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\ORM\Table;

class IpsTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('ips');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('IpBehavior.Ip');
    }

}