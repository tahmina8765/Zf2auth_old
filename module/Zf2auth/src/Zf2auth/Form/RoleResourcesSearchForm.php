<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RoleResourcesSearchForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('role_resources');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');



        $role_id = new Element\Text('role_id');
        $role_id->setLabel('Role Id')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Role Id');


        $resource_id = new Element\Text('resource_id');
        $resource_id->setLabel('Resource Id')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Resource Id');




        $submit = new Element\Submit('submit');
        $submit->setValue('Search')
                ->setAttribute('class', 'btn btn-primary');


        $this->add($role_id);
        $this->add($resource_id);

        $this->add($submit);
    }

}

