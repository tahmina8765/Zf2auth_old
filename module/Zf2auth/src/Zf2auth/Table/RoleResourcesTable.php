<?php

namespace Zf2auth\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zf2auth\Entity\RoleResources;

class RoleResourcesTable extends AbstractTableGateway
{

    protected $table         = 'role_resources';
    protected $resourcetable = 'resources';

    public function __construct(Adapter $adapter)
    {
        $this->adapter            = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new RoleResources());

        $this->initialize();
    }

    public function fetchAll(Select $select = null)
    {
        if (null === $select)
            $select    = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    public function getRole_resources($id)
    {
        $id     = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRole_resources(RoleResources $formdata)
    {
        $data = array(
            'role_id'     => $formdata->role_id,
            'resource_id' => $formdata->resource_id,
        );

        $id = (int) $formdata->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getRole_resources($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function saveAll_role_resources(RoleResources $formdata)
    {

        $role_id = $formdata->role_id;
        $adapter = $this->adapter;
        $select  = new Select();
        $select->from($this->resourcetable);

        $where = new \Zend\Db\Sql\Where();

        $where->addPredicate(
                new \Zend\Db\Sql\Predicate\Expression("resources.id NOT IN (
                                    SELECT
                                        resource_id
                                        FROM role_resources WHERE
                                        role_id = {$role_id}

                                    )")
        );

        if (!empty($where)) {
            $select->where($where);
        }

        $sql       = new Sql($adapter);
        $statement = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $resultSet->buffer();

        $allResources = $resultSet;

        foreach ($allResources as $row) {
            $data = array(
                'role_id'     => $formdata->role_id,
                'resource_id' => $row->id,
            );

            $id = (int) $formdata->id;
            if ($id == 0) {
                $this->insert($data);
            }
        }
    }

    public function deleteRole_resources($id)
    {
        $this->delete(array('id' => $id));
    }

    public function getResourcesBasedRole($role_id)
    {
        $adapter = $this->adapter;
        $select  = new Select();
        $select->from($this->table);

        $select->join('resources', 'resources.id = role_resources.resource_id', array('resource_name' => 'name'), 'left');
        $select->where('role_id =' . $role_id);
        // $resultSet = $this->selectWith($select);
//        echo $role_id;
//        echo $select->getSqlString();
//        die();
        $sql       = new Sql($adapter);
        $statement = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $resultSet->buffer();


        return $resultSet;
    }

}
