<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RolesForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('roles');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');


        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');


        $name = new Element\Text('name');
        $name->setLabel('Name')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Name');




        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-primary');

        $this->add($id);
        $this->add($name);

        $this->add($submit);
    }

}

