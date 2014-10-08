<?php
require_once('DB/Database.php');

class Application_Model_AppointmentMapper
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
        $this->_db = $database->connectMongo();
    }

    // ------------------------------------------------------------------------

    /*
     * Set Database Table
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    Application_Model_DbTable_Appointment
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
            $this->setDbTable('Application_Model_DbTable_Appointment');
        }
        return $this->_dbTable;
    }

    // ------------------------------------------------------------------------

    /*
     * Insert session to the database
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    Application_Model_Appointment
     */

    public function save(Application_Model_Appointment $appointment)
    {
        $collection = $this->getDbTable();
        $this->session = new Zend_Session_Namespace('user_session');
        $cid = $this->session->id;

        if (null === ($id = $appointment->getId())) {

            $data = array(
                '_id'   => $this->getNextSequence(),
                'cid' => (int)$cid,
                'title'   => $appointment->getTitle(),
                'location' => $appointment->getLocation(),
                'startDate' => $appointment->getStartDate(),
                'startTime' => $appointment->getStartTime(),
                'endDate' => $appointment->getEndDate(),
                'endTime' => $appointment->getEndTime(),
                'notes' => $appointment->getNotes()
            );
            unset($data['id']);

            $this->_db->$collection->insert($data);
        } else {
            $data = array(
                '$set'=> array(
                    "title"   => $appointment->getTitle(),
                    'location' => $appointment->getLocation(),
                    'startDate' => $appointment->getStartDate(),
                    'startTime' => $appointment->getStartTime(),
                    'endDate' => $appointment->getEndDate(),
                    'endTime' => $appointment->getEndTime(),
                    'notes' => $appointment->getNotes()
                )
            );
            $this->_db->$collection->update(array("_id" => $id),$data);
        }
    }

    // ------------------------------------------------------------------------

    /*
     * Fetch all appointments for the calendar ID
     *
     * @author		Jacy Gao
     * @return		array
     * @param	    void
     */

    public function fetchAll()
    {
        $this->session = new Zend_Session_Namespace('user_session');
        $cid = $this->session->id;

        $collection = $this->getDbTable();
        $resultSet = $this->_db->$collection->find(array('cid'=>(int)$cid));
        $entries   = array();
        foreach ($resultSet as $row) {

            $entry['id'] = $row['_id'];
            $entry['title'] = $row['title'];
            $entry['location'] = $row['location'];
            $entry['startDate'] = $row['startDate'];
            $entry['startTime'] = $row['startTime'];
            $entry['endDate'] = $row['endDate'];
            $entry['endTime'] = $row['endTime'];
            $entry['notes'] = $row['notes'];
            $entries[] = $entry;

        }
        return $entries;
    }

    // ------------------------------------------------------------------------

    /*
     * Fetch all appointments for ICS export
     *
     * @author		Jacy Gao
     * @return		object
     * @param	    void
     */

    public function fetchExportData()
    {
        $collection = $this->getDbTable();
        $resultSet = $this->_db->$collection->find();
        $entries   = array();
        foreach ($resultSet as $row) {

            $entry['id'] = $row['_id'];
            $entry['cid'] = $row['cid'];
            $entry['title'] = $row['title'];
            $entry['location'] = $row['location'];
            $entry['startDate'] = $row['startDate'];
            $entry['startTime'] = $row['startTime'];
            $entry['endDate'] = $row['endDate'];
            $entry['endTime'] = $row['endTime'];
            $entry['notes'] = $row['notes'];
            $entries[] = $entry;

        }
        return $entries;
    }


    // ------------------------------------------------------------------------

    /*
     * Fetch a single appointment by ID
     *
     * @author		Jacy Gao
     * @return		array
     * @param	    appointment.id
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
     * Remove appointment by ID
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
        if(!$new)
        {
            $new = 1;
        }

        return (int)$new;
    }
}

