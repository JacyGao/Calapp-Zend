<?php

require_once('DB/Database.php');

class Application_Model_SessionMapper
{

    protected $_dbTable;
    public $_db;

    // ------------------------------------------------------------------------

    /*
     * Constructor
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function __construct()
    {
        // Load specific database
        $database = new Database();
        $this->_db = $database->connectSql();
    }

    // ------------------------------------------------------------------------

    /*
     * Set Database Table
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    Application_Model_DbTable_Session
     */

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $collection = new $dbTable();
            $collection = $collection->_name;
        }
        $this->_dbTable = $collection;
        return $this;
    }

    // ------------------------------------------------------------------------

    /*
     * Get Database Table
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    void
     */

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Session');
        }
        return $this->_dbTable;
    }

    // ------------------------------------------------------------------------

    /*
     * Insert appointment to the database
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    user.id, user.name
     */

    public function save($userId, $username)
    {
        $table = $this->getDbTable();
        $date = new Zend_Date();

        $created = $date->get('YYYY-MM-dd HH:mm:ss');

        mysqli_query($this->_db,"INSERT INTO ".$table." (userId, name, created)
        VALUES ('$userId', '$username', '$created')");
    }


}

