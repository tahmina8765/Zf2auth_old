<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');

        $username = new Element\Text('username');
        $username->setLabel('User Name')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'User Name');


        $password = new Element\Password('password');
        $password->setLabel('Password')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Password');

        $rememberme = new Element\Checkbox('rememberme');
        $rememberme->setLabel('Remember me')
                ->setAttribute('class', '')
                ->setValue('1')
        ;




        $submit = new Element\Submit('submit');
        $submit->setValue('Log in')
                ->setAttribute('class', 'btn');

        $this->add($username);
        $this->add($password);
        $this->add($rememberme);
        $this->add($submit);
    }

}

