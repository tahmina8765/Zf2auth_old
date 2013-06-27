<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class UsersForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');


        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');


        $username = new Element\Text('username');
        $username->setLabel('Username')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Username');


        $email = new Element\Text('email');
        $email->setLabel('Email')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Email');


        $password = new Element\Text('password');
        $password->setLabel('Password')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Password');


        $email_check_code = new Element\Text('email_check_code');
        $email_check_code->setLabel('Email Check Code')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Email Check Code');


        $is_disabled = new Element\Text('is_disabled');
        $is_disabled->setLabel('Is Disabled')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Is Disabled');




        $submit = new Element\Submit('submit');
        $submit->setValue('Submit')
                ->setAttribute('class', 'btn btn-primary');

        $this->add($id);
        $this->add($username);
        $this->add($email);
        $this->add($password);
        $this->add($email_check_code);
        $this->add($is_disabled);

        $this->add($submit);
    }

}

