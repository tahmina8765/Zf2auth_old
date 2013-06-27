<?php

namespace Zf2auth\Entity;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Fbprofiles implements InputFilterAwareInterface
{

    public $name;
    public $first_name;
    public $last_name;
    public $link;
    public $username;
    public $gender;
    public $timezone;
    public $locale;
    public $verified;
    public $updated_time;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id'])) ? $data['id'] : null;
        $this->name         = (isset($data['name'])) ? $data['name'] : null;
        $this->first_name   = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->last_name    = (isset($data['last_name'])) ? $data['last_name'] : null;
        $this->link         = (isset($data['link'])) ? $data['link'] : null;
        $this->username     = (isset($data['username'])) ? $data['username'] : null;
        $this->gender       = (isset($data['gender'])) ? $data['gender'] : null;
        $this->timezone     = (isset($data['timezone'])) ? $data['timezone'] : null;
        $this->locale       = (isset($data['locale'])) ? $data['locale'] : null;
        $this->verified     = (isset($data['verified'])) ? $data['verified'] : null;
        $this->updated_time = (isset($data['updated_time'])) ? $data['updated_time'] : null;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    public function getVerified()
    {
        return $this->verified;
    }

    public function setUpdated_time($updated_time)
    {
        $this->updated_time = $updated_time;
    }

    public function getUpdated_time()
    {
        return $this->updated_time;
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
                        'name'     => 'name',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'first_name',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'last_name',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'link',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'username',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'gender',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'timezone',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'locale',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'verified',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name'     => 'updated_time',
                        'required' => false,
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}