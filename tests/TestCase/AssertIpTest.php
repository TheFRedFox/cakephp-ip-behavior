<?php
namespace IpBehavior\Test\Fixture;

use Cake\Database\Type;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestCase;
use Cake\TestSuite\TestCase;
use IpBehavior\Model\Behavior\IpBehavior;
use IpType\Database\Type\IpType;
use TestApp\Lib\TestClass;
use TestApp\Model\Table\IpsTable;

/**
 * This class helps in indirectly testing the functionalities of IntegrationTestCase
 *
 * @property IpsTable Ips
 */
class IpsTestCase extends IntegrationTestCase
{

    public $fixtures = ['plugin.ip_behavior.ips'];

    private $_clientIp = '127.0.0.1';

    /**
     * Setup the test case, backup the static object values so they can be restored.
     * Specifically backs up the contents of Configure and paths in App if they have
     * not already been backed up.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Ips = TableRegistry::get('TestApp.Ips');
        $this->Ips->schema()->columnType('ip', 'ip');

        Router::scope('/', function ($routes) {
            $routes->connect('/ips', ['controller' => 'Ips']);
            $routes->connect('/ips/add', ['controller' => 'Ips', 'action' => 'add']);
            $routes->connect('/ips/edit/:id', ['controller' => 'Ips', 'action' => 'edit'], ['pass' => ['id']]);
            $routes->connect('/ips/edit_when_always/:id', ['controller' => 'Ips', 'action' => 'editWhenAlways'], ['pass' => ['id']]);
            $routes->connect('/ips/edit_when_bad_value/:id', ['controller' => 'Ips', 'action' => 'editWhenBadValue'], ['pass' => ['id']]);
        });
    }

    public function testAdd()
    {
        $count = $this->Ips->find()->count();
        $this->assertEquals(1, $count);

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $this->_clientIp]]);
        $this->post('/ips/add', ['content' => 'content']);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count + 1, $entities);
        $this->assertEquals($entities[1]->ip, $this->_clientIp);
    }

    public function testEditWhenNew()
    {
        $count = $this->Ips->find()->count();
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $this->_clientIp]]);
        $this->put('/ips/edit/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[0]->content, $new_content);
        $this->assertEquals($entities[0]->ip, $this->_clientIp);
    }

    public function testEditWhenAlways()
    {
        $count = $this->Ips->find()->count();
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $other_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $other_ip]]);
        $this->put('/ips/edit_when_always/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[0]->content, $new_content);
        $this->assertEquals($entities[0]->ip, $other_ip);
    }

    public function testEditWhenBadValue()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $content_original = $entities[0]->content;
        $ip_original = $entities[0]->ip;

        $new_content = 'new content';
        $other_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $other_ip]]);
        $this->put('/ips/edit_when_bad_value/1', ['content' => $new_content]);
        $this->assertResponseFailure();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[0]->content, $content_original);
        $this->assertEquals($entities[0]->ip, $ip_original);
    }
}
