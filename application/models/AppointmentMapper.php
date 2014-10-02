<?php

class Application_Model_AppointmentMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $collection = new $dbTable();
            $collection = $collection->_name;
        }
        $this->_dbTable = $collection;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Appointment');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Appointment $appointment)
    {
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");

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
            $db->$collection->insert($data);
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
            $db->$collection->update(array("_id" => $id),$data);
        }
    }

    /* Fetch all appointments */
    public function fetchAll()
    {
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");

        $this->session = new Zend_Session_Namespace('user_session');
        $cid = $this->session->id;

        $collection = $this->getDbTable();
        $resultSet = $db->$collection->find(array('cid'=>(int)$cid));
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

    /* Fetch a single calender by id */
    public function fetch($id)
    {
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");

        $collection = $this->getDbTable();
        $result = $db->$collection->findOne(array('_id'=>(int)$id));

        if (0 == count($result)) {
            return;
        }

        return $result;
    }

    public function remove($id)
    {
        //delete a document in the database
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");

        $collection = $this->getDbTable();

        $db->$collection->remove(array('_id' => (int)$id));
    }

    public function getNextSequence()
    {
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");
        $collection = $this->getDbTable();

        $cursor = $db->$collection->find();
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

