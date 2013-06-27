<?php

namespace Zf2auth\Form;

use Zend\Form\Form;
use \Zend\Form\Element;

class FbprofilesSearchForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('fbprofiles');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');



        $name = new Element\Text('name');
        $name->setLabel('Name')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Name');


        $first_name = new Element\Text('first_name');
        $first_name->setLabel('First Name')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'First Name');


        $last_name = new Element\Text('last_name');
        $last_name->setLabel('Last Name')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Last Name');


        $link = new Element\Text('link');
        $link->setLabel('Link')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Link');


        $username = new Element\Text('username');
        $username->setLabel('Username')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Username');


        $gender = new Element\Text('gender');
        $gender->setLabel('Gender')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Gender');


        $timezone = new Element\Text('timezone');
        $timezone->setLabel('Timezone')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Timezone');


        $locale = new Element\Text('locale');
        $locale->setLabel('Locale')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Locale');


        $verified = new Element\Text('verified');
        $verified->setLabel('Verified')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Verified');


        $updated_time = new Element\Text('updated_time');
        $updated_time->setLabel('Updated Time')
                ->setAttribute('class', 'required')
                ->setAttribute('placeholder', 'Updated Time');




        $submit = new Element\Submit('submit');
        $submit->setValue('Search')
                ->setAttribute('class', 'btn btn-primary');


        $this->add($name);
        $this->add($first_name);
        $this->add($last_name);
        $this->add($link);
        $this->add($username);
        $this->add($gender);
        $this->add($timezone);
        $this->add($locale);
        $this->add($verified);
        $this->add($updated_time);

        $this->add($submit);
    }

}

