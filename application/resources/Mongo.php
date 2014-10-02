<?php

class My_Application_Resource_Mongo extends
    Zend_Application_Resource_ResourceAbstract {

    protected $_params = array();

    public function setParams(array $params) {
        $this->_params = $params;
        return $this;
    }

    public function init() {
        $dns = 'mongodb://';
        if (isset($this->_params['dbname'])) {
            if (isset($this->_params['username']) &&
                isset($this->_params['password'])) {
                $dns .= $this->_params['username'] .
                    ':' . $this->_params['password'] . '@';
            }
            if (isset($this->_params['hostname'])) {
                $dns .= $this->_params['hostname'];
            }

            if (isset($this->_params['port'])) {
                $dns .= ':' . $this->_params['port'];
            }
        } else {
            throw new Exception(__CLASS__ . ' is missing parameters.');
        }
        try {
            /*echo "connecting to Mongo DB...";
            $connection = new Mongo(
                'mongodb://localhost', array(
                'username' => 'jacy',
                'password' => 'temp',
                'db'       => 'mydb'
            ));
            echo "connecting to Mongo DB finished...";*/
            $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
            $db = $connection->selectDB("ecal");

            //return $connection->selectDB($this->_params['dbname']);
        } catch (MongoConnectionException $e) {
           error_log($e->getMessage());
        }
    }
}