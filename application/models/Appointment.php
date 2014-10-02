<?php

class Application_Model_Appointment
{

    public $_id;
    public $_title;
    public $_location;
    public $_startDate;
    public $_startTime;
    public $_endDate;
    public $_endTime;
    public $_notes;

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

    public function setTitle($text)
    {
        $this->_title = (string) $text;
        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setLocation($text)
    {
        $this->_location = (string) $text;
        return $this;
    }

    public function getLocation()
    {
        return $this->_location;
    }

    public function setStartDate($text)
    {
        $this->_startDate = (string) $text;
        return $this;
    }

    public function getStartDate()
    {
        return $this->_startDate;
    }

    public function setStartTime($text)
    {
        $this->_startTime = (string) $text;
        return $this;
    }

    public function getStartTime()
    {
        return $this->_startTime;
    }

    public function setEndDate($text)
    {
        $this->_endDate = (string) $text;
        return $this;
    }

    public function getEndDate()
    {
        return $this->_endDate;
    }

    public function setEndTime($text)
    {
        $this->_endTime = (string) $text;
        return $this;
    }

    public function getEndTime()
    {
        return $this->_endTime;
    }

    public function setNotes($text)
    {
        $this->_notes = (string) $text;
        return $this;
    }

    public function getNotes()
    {
        return $this->_notes;
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