<?php
require_once('DB/Database.php');

class Application_Model_UserMapper
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
     * @param	    Application_Model_DbTable_User
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
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }

    // ------------------------------------------------------------------------

    /*
     * Validate user login by username and password
     *
     * @author		Jacy Gao
     * @return		boolean
     * @param	    user.name, user.password
     */

    public function find($username, $password)
    {
        $table = $this->getDbTable();

        $result = mysqli_query($this->_db,"SELECT * FROM ".$table." WHERE name = '".$username."' AND password = '".$password."'");

        return (mysqli_num_rows($result) > 0) ? mysqli_fetch_array($result) : false;
    }


    // ------------------------------------------------------------------------

    /*
     * Fetch All Users
     *
     * @author		Jacy Gao
     * @return		array
     * @param	    void
     */

    public function fetchAll()
    {
        $table = $this->getDbTable();

        $result = mysqli_query($this->_db,"SELECT * FROM ".$table);

        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        return $rows;
    }
}

