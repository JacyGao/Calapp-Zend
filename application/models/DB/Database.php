<?php

class Database
{

    // Define DB info

    public $_mongoServer = 'localhost';
    public $_mongoName = 'ecal';
    public $_mongoUser = 'jacy';
    public $_mongoPass = 'temp';
    public $_sqlServer = 'localhost';
    public $_sqlName = 'ecal_sql';
    public $_sqlUser = 'root';
    public $_sqlPass = '';

    // ------------------------------------------------------------------------

    /*
     * Construct
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function __construct()
    {

    }

    // ------------------------------------------------------------------------

    /*
     * Connect to Mongo DB
     *
     * @author		Jacy Gao
     * @return		Object
     * @param	    void
     */

    public function connectMongo()
    {
        $connection = new Mongo("mongodb://".$this->_mongoUser.":".$this->_mongoPass."@".$this->_mongoServer); // connect
        return $connection->selectDB($this->_mongoName);
    }

    // ------------------------------------------------------------------------

    /*
     * Connect to MySQL
     *
     * @author		Jacy Gao
     * @return		Object
     * @param	    void
     */

    public function connectSql()
    {
        $connection = mysqli_connect($this->_sqlServer,$this->_sqlUser,$this->_sqlPass,$this->_sqlName); // connect
        return $connection;
    }

    // ------------------------------------------------------------------------
}