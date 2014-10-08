<?php
require_once('DB/Database.php');

class Application_Model_CalendarMapper
{

    protected $_dbTable;
    public $_db;
    public $_cache;

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
        $this->_db = $database->connectMongo();

    }

    // ------------------------------------------------------------------------

    /*
     * Set Database Table
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    Application_Model_DbTable_Calendar
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
            $this->setDbTable('Application_Model_DbTable_Calendar');
        }
        return $this->_dbTable;
    }

    // ------------------------------------------------------------------------

    /*
     * Insert Calendar to the database
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    Application_Model_Calendar
     */

    public function save(Application_Model_Calendar $calendar)
    {
        $collection = $this->getDbTable();
        $this->session = new Zend_Session_Namespace('user_session');
        $uid = $this->session->userId;

        if (null === ($id = $calendar->getId())) {
            $data = array(
                '_id'   => $this->getNextSequence(),
                'userId' => (int)$uid,
                'name'   => $calendar->getName()
            );
            unset($data['id']);
            $this->_db->$collection->insert($data);
        } else {
            $data = array(
                '$set'=> array(
                "name"   => $calendar->getName())
            );
            $this->_db->$collection->update(array("_id" => $id),$data);
        }

    }

    // ------------------------------------------------------------------------

    /*
     * Fetch all Calendars for the User ID
     *
     * @author		Jacy Gao
     * @return		array
     * @param	    void
     */

    public function fetchAll()
    {
        $this->session = new Zend_Session_Namespace('user_session');
        $uid = $this->session->userId;

        $collection = $this->getDbTable();
        $resultSet = $this->_db->$collection->find(array('userId'=>(int)$uid));
        $entries   = array();
        foreach ($resultSet as $row) {

            $entry['id'] = $row['_id'];
            $entry['name'] = $row['name'];
            $entries[] = $entry;

        }
        return $entries;
    }

    // ------------------------------------------------------------------------

    /*
     * Fetch all Calendars By User ID
     *
     * @author		Jacy Gao
     * @return		array
     * @param	    user.id
     */

    public function fetchByUserId($userid)
    {
        $uid = $userid;
        $collection = $this->getDbTable();
        $resultSet = $this->_db->$collection->find(array('userId'=>(int)$uid));
        $entries   = array();
        foreach ($resultSet as $row) {

            $entry['id'] = $row['_id'];
            $entry['name'] = $row['name'];
            $entries[] = $entry;

        }
        return $entries;
    }


    // ------------------------------------------------------------------------

    /*
     * Fetch a single calendar by User ID
     *
     * @author		Jacy Gao
     * @return		array
     * @param	    user.id
     */

    public function fetch($id)
    {
        $collection = $this->getDbTable();
        $result = $this->_db->$collection->findOne(array('_id'=>(int)$id));

        if (0 == count($result)) {
            return;
        }

        return $result;
    }

    // ------------------------------------------------------------------------

    /*
     * Remove Calendar by ID
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    appointment.id
     */

    public function remove($id)
    {
        //delete a document in the database
        $collection = $this->getDbTable();

        $this->_db->$collection->remove(array('_id' => (int)$id));
    }

    // ------------------------------------------------------------------------

    /*
     * Generate auto increasement ID
     *
     * @author		Jacy Gao
     * @return		int
     * @param	    void
     */

    public function getNextSequence()
    {

        $collection = $this->getDbTable();

        $cursor = $this->_db->$collection->find();
        $cursor->sort(array( '_id' => -1 ));
        $cursor->limit(1);
        foreach ($cursor as $row) {

            $entry['id'] = $row['_id'];
            $entries[] = $entry;

        }

        $last = $entries[0]['id'];
        $new = $last + 1;

        return (int)$new;
    }

}

