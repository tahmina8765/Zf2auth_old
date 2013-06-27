<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class User_rolesSearchForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('user_roles');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');



        $user_id = new Element\Text('user_id');
        $user_id->setLabel('User Id')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'User Id');


        $role_id = new Element\Text('role_id');
        $role_id->setLabel('Role Id')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Role Id');




        $submit = new Element\Submit('submit');
        $submit->setValue('Search')
                ->setAttribute('class', 'btn btn-primary');


        $this->add($user_id);
        $this->add($role_id);

        $this->add($submit);
    }

}

