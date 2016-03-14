<?php

namespace IpBehavior\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Short description for class.
 *
 */
class IpsFixture extends TestFixture
{

    public $table = 'ips';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'content' => ['type' => 'string', 'length' => 16, 'null' => true],
        'ip' => ['type' => 'string', 'length' => 16, 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    public function __construct($connection = null)
    {
        $this->records = [
            ['id' => 1, 'content' => 'init content', 'ip' => '127.0.0.1']
        ];

        if ($connection) {
            $this->connection = $connection;
        }
        $this->init();
    }

}
