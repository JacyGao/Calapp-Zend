<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function initView()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->doctype('XHTML1 STRICT');
        $this->view->headMeta()->appendHttpEquiv('Content-Type',
            'text/html; charset=UTF-8');
    }
}

