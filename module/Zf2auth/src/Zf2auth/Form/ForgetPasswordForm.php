<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class ForgetPasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('users');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');

        $username = new Element\Text('username');
        $username->setLabel('User Name')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Enter Your Username');


        $email = new Element\Text('email');
        $email->setLabel('Email')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Enter Your Email Address');


        $submit = new Element\Submit('submit');
        $submit->setValue('Log in')
                ->setAttribute('class', 'btn btn-success  header-btn');

        $this->add($username);
        $this->add($email);
        $this->add($submit);
    }

}

