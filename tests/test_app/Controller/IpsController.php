<?php

namespace TestApp\Controller;

use Cake\Log\Log;
use IpBehavior\Model\Behavior\IpBehavior;
use TestApp\Model\Table\IpsTable;

/**
 * Class IpsController
 * @package TestApp\Controller
 *
 * @property IpsTable Ips
 */
class IpsController extends AppController
{

    public $name = 'Ips';

    public function initialize()
    {
        parent::initialize();

        $this->viewBuilder()->autoLayout(false);
        $this->viewBuilder()->template('template');

        $this->set(['_serialize' => '']);
    }

    public function index()
    {
        $entities = $this->Ips->find()->all();

        $this->set('_serialize', $entities);
    }

    public function add()
    {
        if ($this->request->is(['post'])) {
            $entity = $this->Ips->newEntity($this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function edit($id)
    {
        $entity = $this->Ips->get($id);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function editWhenAlways($id)
    {
        $entity = $this->Ips->get($id);

        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        Log::debug($this->Ips->behaviors()->loaded());
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['ip' => 'always']]);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function editWhenBadValue($id)
    {
        $entity = $this->Ips->get($id);

        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['ip' => 'bad_value']]);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function addOtherField()
    {
        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['other_ip_field']]);

        if ($this->request->is(['post'])) {
            $entity = $this->Ips->newEntity($this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function editOtherField($id)
    {
        $entity = $this->Ips->get($id);

        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['other_ip_field']]);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function editOtherFieldWhenAlways($id)
    {
        $entity = $this->Ips->get($id);

        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['other_ip_field' => 'always']]);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function editOtherFieldWhenBadValue($id)
    {
        $entity = $this->Ips->get($id);

        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['other_ip_field' => 'bad_value']]);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function addTwoFields()
    {
        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['ip', 'other_ip_field' => 'always']]);

        if ($this->request->is(['post'])) {
            $entity = $this->Ips->newEntity($this->request->data());
            $this->Ips->save($entity);
        }
    }

    public function editTwoFields($id)
    {
        /** @var IpBehavior $behavior */
        $this->Ips->removeBehavior('Ip');
        $this->Ips->addBehavior('IpBehavior.Ip', ['fields' => ['ip', 'other_ip_field' => 'always']]);

        $entity = $this->Ips->get($id);

        if ($this->request->is(['post', 'put'])) {
            $entity = $this->Ips->patchEntity($entity, $this->request->data());
            $this->Ips->save($entity);
        }
    }

}