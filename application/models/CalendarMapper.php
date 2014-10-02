<?php

class Application_Model_CalendarMapper
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
            $this->setDbTable('Application_Model_DbTable_Calendar');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Calendar $calendar)
    {
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");

        $collection = $this->getDbTable();

        if (null === ($id = $calendar->getId())) {
            $data = array(
                '_id'   => $this->getNextSequence(),
                'name'   => $calendar->getName()
            );
            unset($data['id']);
            $db->$collection->insert($data);
        } else {
            $data = array(
                '$set'=> array(
                "name"   => $calendar->getName())
            );
            $db->$collection->update(array("_id" => $id),$data);
        }
    }

    public function find($id, Application_Model_Calendar $calendar)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $calendar->setId($row->id)
            ->setName($row->name);
    }

    /* Fetch all calendars */
    public function fetchAll()
    {
        $connection = new Mongo("mongodb://jacy:temp@localhost"); // connect
        $db = $connection->selectDB("ecal");

        $collection = $this->getDbTable();
        $resultSet = $db->$collection->find();
        $entries   = array();
        foreach ($resultSet as $row) {

            $entry['id'] = $row['_id'];
            $entry['name'] = $row['name'];
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

        return $new;
    }


}

