<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RoleResourcesForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('role_resources');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');


        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');


        $role_id = new Element\Select('role_id');
        $role_id->setLabel('Role Id')
                ->setAttribute('class', 'required')
                ->setOptions(array())
                ->setDisableInArrayValidator(true)
                ->setAttribute('placeholder', 'Role Id');


        $resource_id = new Element\Select('resource_id');
        $resource_id->setLabel('Resource Id')
                ->setAttribute('class', 'required')
                ->setOptions(array())
                ->setDisableInArrayValidator(true)
                ->setAttribute('placeholder', 'Resource Id');




        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-primary');

        $this->add($id);
        $this->add($role_id);
        $this->add($resource_id);

        $this->add($submit);
    }

}

