<?php

namespace Application;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    protected $view;
    protected $viewListener;

    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $locator      = $app->getLocator();
        $view         = $this->getView($app);
    }

    protected function getView($app)
    {
        if ($this->view) {
            return $this->view;
        }

        $di     = $app->getLocator();
        $view   = $di->get('view');
        $view->getEnvironment()->getLoader()->addPath(__DIR__ . '/views');

        $url    = $view->plugin('url');
        $url->setRouter($app->getRouter());

        $view->plugin('headTitle')->setSeparator(' - ')
                                  ->setAutoEscape(false)
                                  ->append('ZF2 Skeleton Application');

        $basePath = $app->getRequest()->detectBaseUrl();

        $view->plugin('headLink')->appendStylesheet($basePath . 'css/bootstrap.min.css');

        $html5js = '<script src="' . $basePath . 'js/html5.js"></script>';
        $view->plugin('placeHolder')->__invoke('html5js')->set($html5js);
        $favicon = '<link rel="shortcut icon" href="' . $basePath . 'images/favicon.ico">';
        $view->plugin('placeHolder')->__invoke('favicon')->set($favicon);

        $this->view = $view;
        return $view;
    }
}
