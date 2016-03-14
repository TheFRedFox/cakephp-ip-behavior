<?php
namespace IpBehavior\Test\Fixture;

use Cake\Database\Type;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestCase;
use TestApp\Model\Table\IpsTable;

/**
 * This class helps in indirectly testing the functionalities of IntegrationTestCase
 *
 * @property IpsTable Ips
 */
class IpBehaviorTest extends IntegrationTestCase
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
            $routes->connect('/ips/add_other_field', ['controller' => 'Ips', 'action' => 'addOtherField']);
            $routes->connect('/ips/edit_other_field/:id', ['controller' => 'Ips', 'action' => 'editOtherField'], ['pass' => ['id']]);
            $routes->connect('/ips/edit_other_field_when_always/:id', ['controller' => 'Ips', 'action' => 'editOtherFieldWhenAlways'], ['pass' => ['id']]);
            $routes->connect('/ips/edit_other_field_when_bad_value/:id', ['controller' => 'Ips', 'action' => 'editOtherFieldWhenBadValue'], ['pass' => ['id']]);
            $routes->connect('/ips/add_two_fields', ['controller' => 'Ips', 'action' => 'addTwoFields']);
            $routes->connect('/ips/edit_two_fields/:id', ['controller' => 'Ips', 'action' => 'editTwoFields'], ['pass' => ['id']]);
        });
    }

    public function testAdd()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $content = 'content';
        $ip = $this->_clientIp;

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $ip]]);
        $this->post('/ips/add', ['content' => $content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count + 1, $entities);
        $this->assertEquals($entities[$count]->content, $content);
        $this->assertEquals($entities[$count]->ip, $ip);
        $this->assertEquals($entities[$count]->other_ip_field, null);
    }

    public function testEditWhenNew()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $other_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $other_ip]]);
        $this->put('/ips/edit/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $new_content);
        $this->assertEquals($entities[$count - 1]->ip, $entities[0]->ip);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $entities[0]->other_ip_field);
    }

    public function testEditWhenAlways()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $new_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $new_ip]]);
        $this->put('/ips/edit_when_always/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $new_content);
        $this->assertEquals($entities[$count - 1]->ip, $new_ip);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $entities[0]->other_ip_field);
    }

    public function testEditWhenBadValue()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $content_original = $entities[0]->content;
        $ip_original = $entities[0]->ip;
        $other_ip_field_original = $entities[0]->other_ip_field;

        $new_content = 'new content';
        $new_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $new_ip]]);
        $this->put('/ips/edit_when_bad_value/1', ['content' => $new_content]);
        $this->assertResponseFailure();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $content_original);
        $this->assertEquals($entities[$count - 1]->ip, $ip_original);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $other_ip_field_original);
    }

    public function testAddOtherField()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $content = 'content';
        $ip = $this->_clientIp;

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $ip]]);
        $this->post('/ips/add_other_field', ['content' => $content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count + 1, $entities);
        $this->assertEquals($entities[$count]->content, $content);
        $this->assertEquals($entities[$count]->ip, null);
        $this->assertEquals($entities[$count]->other_ip_field, $ip);
    }

    public function testEditOtherFieldWhenNew()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $new_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $new_ip]]);
        $this->put('/ips/edit_other_field/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $new_content);
        $this->assertEquals($entities[$count - 1]->ip, $entities[0]->ip);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $entities[0]->other_ip_field);
    }

    public function testEditOtherFieldWhenAlways()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $new_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $new_ip]]);
        $this->put('/ips/edit_other_field_when_always/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $new_content);
        $this->assertEquals($entities[$count - 1]->ip, $entities[0]->ip);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $new_ip);
    }

    public function testEditOtherFieldWhenBadValue()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $content_original = $entities[0]->content;
        $ip_original = $entities[0]->ip;
        $other_ip_field_original = $entities[0]->other_ip_field;

        $new_content = 'new content';
        $new_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $new_ip]]);
        $this->put('/ips/edit_other_field_when_bad_value/1', ['content' => $new_content]);
        $this->assertResponseFailure();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $content_original);
        $this->assertEquals($entities[$count - 1]->ip, $ip_original);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $other_ip_field_original);
    }

    public function testAddTwoFieldsWhenNewAndAlways()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $content = 'content';
        $ip = $this->_clientIp;

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $ip]]);
        $this->post('/ips/add_two_fields', ['content' => $content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count + 1, $entities);
        $this->assertEquals($entities[$count]->content, 'content');
        $this->assertEquals($entities[$count]->ip, $ip);
        $this->assertEquals($entities[$count]->other_ip_field, $ip);
    }

    public function testEditTwoFieldsWhenNewAndAlways()
    {
        $entities = $this->Ips->find()->all()->toArray();
        $count = count($entities);
        $this->assertEquals(1, $count);

        $new_content = 'new content';
        $new_ip = '::1';

        $this->configRequest(['headers' => ['Accept' => 'application/json', 'CLIENT_IP' => $new_ip]]);
        $this->put('/ips/edit_two_fields/1', ['content' => $new_content]);
        $this->assertResponseOk();
        $entities = $this->Ips->find()->all()->toArray();
        $this->assertCount($count, $entities);
        $this->assertEquals($entities[$count - 1]->content, $new_content);
        $this->assertEquals($entities[$count - 1]->ip, $entities[0]->ip);
        $this->assertEquals($entities[$count - 1]->other_ip_field, $new_ip);
    }
}
