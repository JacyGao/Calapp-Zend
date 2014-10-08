<?php

class Application_Model_User
{

    public $_id;
    public $_name;
    public $_password;
    public $_contact;
    public $_email;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid calendar property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid calendar property');
        }
        return $this->$method();
    }

    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($text)
    {
        $this->_name = (string) $text;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setContact($text)
    {
        $this->_contact = (string) $text;
        return $this;
    }

    public function getContact()
    {
        return $this->_contact;
    }

    public function setEmail($text)
    {
        $this->_email = (string) $text;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setPassword($text)
    {
        $this->_password = (string) $text;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}

