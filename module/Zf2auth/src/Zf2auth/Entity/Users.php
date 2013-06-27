<?php

namespace Zf2auth\Entity;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\EmailAddress;

class Users
{

    public $username;
    public $email;
    public $password;
    public $email_check_code;
    public $is_disabled;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id               = (isset($data['id'])) ? $data['id'] : null;
        $this->username         = (isset($data['username'])) ? $data['username'] : null;
        $this->email            = (isset($data['email'])) ? $data['email'] : null;
        $this->password         = (isset($data['password'])) ? $data['password'] : null;
        $this->email_check_code = (isset($data['email_check_code'])) ? $data['email_check_code'] : null;
        $this->is_disabled      = (isset($data['is_disabled'])) ? $data['is_disabled'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setEmail_check_code($email_check_code)
    {
        $this->email_check_code = $email_check_code;
    }

    public function getEmail_check_code()
    {
        return $this->email_check_code;
    }

    public function setIs_disabled($is_disabled)
    {
        $this->is_disabled = $is_disabled;
    }

    public function getIs_disabled()
    {
        return $this->is_disabled;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'id',
                        'required' => true,
                        'filters'  => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'first_name',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'last_name',
                        'required' => true,
            )));


            $inputFilter->add($factory->createInput(array(
                        'name'     => 'username',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'       => 'email',
                        'required'   => true,
                        'filters'    => array(
                            array('name' => 'Zend\Filter\StringTrim'),
                        ),
                        'validators' => array(
                            new EmailAddress(),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'password',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'email_check_code',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'is_disabled',
                        'required' => false,
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}