<?php

class Application_Form_Appointment extends Zend_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an title element
        $this->addElement('text', 'title', array(
            'label'      => 'Title:',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        // Add a location element
        $this->addElement('text', 'location', array(
            'label'      => 'Location:',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        // Add a Start Date element
        $this->addElement('text', 'startDate', array(
            'label'      => 'Start Date:',
            'required'   => true,
            'validators'  => array (
                new Zend_Validate_Date(array('format' => 'dd/MM/yyyy'))
            ),
            'placeholder' => "dd/MM/yyyy",
            'id' => "startDate",
            'filters'    => array('StringTrim')
        ));

        // Add a Start Time element
        $this->addElement('text', 'startTime', array(
            'label'      => 'Start Time:',
            'required'   => true,
            'validators'  => array (
                new Zend_Validate_Date(array('format' => 'H:i'))
            ),
            'placeholder' => "Hour:Minute",
            'id' => "startTime",
            'filters'    => array('StringTrim')
        ));

        // Add a End Date element
        $this->addElement('text', 'endDate', array(
            'label'      => 'End Date:',
            'required'   => true,
            'validators'  => array (
                new Zend_Validate_Date(array('format' => 'dd/MM/yyyy'))
            ),
            'placeholder' => "dd/MM/yyyy",
            'id' => "endDate",
            'filters'    => array('StringTrim')
        ));

        // Add a End Time element
        $this->addElement('text', 'endTime', array(
            'label'      => 'End Time:',
            'required'   => true,
            'validators'  => array (
                new Zend_Validate_Date(array('format' => 'H:i'))
            ),
            'placeholder' => "Hour:Minute",
            'id' => "endTime",
            'filters'    => array('StringTrim')
        ));


        $this->addElement('text', 'notes', array(
            'label'      => 'Notes:',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Add Appointment',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}
