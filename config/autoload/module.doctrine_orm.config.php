 <?php
 /**
  * Doctrine ORM Configuration
  *
  * If you have a ./configs/autoload/ directory set up for your project, you can
  * drop this config file in it and change the values as you wish. This file is intended
  * to be used with a standard Doctrine ORM setup. If you have something more advanced
  * you may override the Zend\Di configuration manually (see module.config.php).
  */
$settings = array(
    // if disabled will not register annotations
    'use_annotations' => true,

    // if use_annotations (above) is set to true this file will be registered
    'annotation_file' => __DIR__ . '/../../vendor/DoctrineORMModule/vendor/doctrine-orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php',

    // enables production mode by disabling generation of proxies
   'production' => true,

    // sets the cache to use for metadata: one of 'array', 'apc', or 'memcache'
    'cache' => 'apc',

    // only used if cache is set to memcache
    'memcache' => array(
        'host' => '127.0.0.1',
        'port' => '11211'
    ),

    // connection parameters
    'connection' => array(
        'driver'  => 'pdo_sqlite',
        'path'    => __DIR__ . '/../../data/cities.sqlite',
        'charset' => 'UTF8',
    ),

    // driver settings
    'driver' => array(
        'class'     => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
        'namespace' => 'Wowo\Cities\Entity',
        'paths'     => array('module/KrymenCities/model/src/Wowo/Cities/Entity')
    ),

    // namespace aliases for annotations
    'namespace_aliases' => array(
    ),
);

/**
 * YOU DO NOT NEED TO EDIT BELOW THIS LINE.
 */
$cache = array('array', 'memcache', 'apc');
if (!in_array($settings['cache'], $cache)) {
    throw new InvalidArgumentException(sprintf(
        'cache must be one of: %s',
        implode(', ', $cache)
    ));
}
$settings['cache'] = 'doctrine_cache_' . $settings['cache'];

return array(
    'doctrine_orm_module' => array(
        'annotation_file' => $settings['annotation_file'],
        'use_annotations' => $settings['use_annotations'],
    ),
    'di' => array(
        'instance' => array(
            'doctrine_memcache' => array(
                'parameters' => $settings['memcache']
            ),
            'orm_config' => array(
                'parameters' => array(
                    'opts' => array(
                        'entity_namespaces' => $settings['namespace_aliases'],
                        'auto_generate_proxies' => !$settings['production']
                    ),
                    'metadataCache' => $settings['cache'],
                    'queryCache'    => $settings['cache'],
                    'resultCache'   => $settings['cache'],
                )
            ),
            'orm_connection' => array(
                'parameters' => array(
                    'params' => $settings['connection']
                ),
            ),
            'orm_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
                        'application_annotation_driver' => $settings['driver']
                    ),
                    'cache' => $settings['cache']
                )
            ),
        ),
    ),
);