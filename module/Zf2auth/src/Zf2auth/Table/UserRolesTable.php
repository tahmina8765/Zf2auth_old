<?php

namespace Zf2auth\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zf2auth\Entity\UsersRoles;

class UserRolesTable extends AbstractTableGateway
{

    protected $table = 'user_roles';

    public function __construct(Adapter $adapter)
    {
        $this->adapter            = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new User_roles());

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

    public function getUser_roles($id)
    {
        $id     = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser_roles(User_roles $formdata)
    {
        $data = array(
            'user_id' => $formdata->user_id,
            'role_id' => $formdata->role_id,
        );

        $id = (int) $formdata->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getUser_roles($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteUser_roles($id)
    {
        $this->delete(array('id' => $id));
    }

}

