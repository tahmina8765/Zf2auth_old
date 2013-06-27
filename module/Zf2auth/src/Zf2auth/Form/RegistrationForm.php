<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class RegistrationForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', '');
        $this->setAttribute('method', 'post');


        $id = new Element\Hidden('id');
        $id->setAttribute('class', 'primarykey');


        $username = new Element\Text('username');
        $username->setLabel('Username')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('placeholder', 'Username');


        $email = new Element\Text('email');
        $email->setLabel('Email')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('placeholder', 'Email');


        $password = new Element\Password('password');
        $password->setLabel('Password')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('id', 'inputPassword')
                ->setAttribute('placeholder', 'Password');

        $first_name = new Element\Text('first_name');
        $first_name->setLabel('First Name')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('placeholder', 'First Name');


        $last_name = new Element\Text('last_name');
        $last_name->setLabel('Last Name')
                ->setAttribute('class', 'required inputfullwidth')
                ->setAttribute('placeholder', 'Last Name');


//        $submit = new Element\Submit('submit');
//        $submit->setValue('Join Now')
//                ->setAttribute('class', 'btn btn-large btn-success  join-now-home fullwidth');


        $this->add($id);
        $this->add($first_name);
        $this->add($last_name);
        $this->add($username);
        $this->add($email);
        $this->add($password);
//        $this->add($submit);
    }

}

