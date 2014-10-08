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

    protected function _initRoute()
    {
        $route = new Zend_Controller_Router_Route('/ical/:userid.ics', array(
            'controller' => 'user',
            'action' => 'export'
        ));
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $router->addRoute('test', $route);
    }

    protected function _initCache()
    {
        $frontend = array(
            'lifetime' => 7200,
            'automatic_seralization' => true
        );

        $backend = array(
            'cache_dir' => APPLICATION_PATH.'/cache',
            'compression' => true
        );

        $cache = Zend_Cache::factory('core','File',$frontend,$backend);
        Zend_Registry::set('cache',$cache);
    }
}

