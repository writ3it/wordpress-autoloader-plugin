<?php

/**
 * Plugin Name: Autoloader MU Module
 * Plugin URI:  https://github.com/writ3it/wordpress-autoloader-plugin
 * Description: Plugin provides PSR-4 autoloader for another plugins and themes.
 * License:     MIT
 * License URI: https://github.com/writ3it/wordpress-autoloader-plugin/blob/master/README.md
 * Version: 1.0.0
 * Author: writ3it
 * Text Domain: autoloader
 */

class ___Autoloader{

    const PHP_EXTENSION = '.php';
    const NS_DELIMITER = '\\';
    const DR_DELIMITER = '/';
    static $instance = null;
    static $namespacesPaths = array();
    static $namespaces = array();

    private function __construct()
    {
        // has to be singleton
    }

    /**
     * @param array $namespaces
     */
    static public function namespaces($namespaces){
        foreach($namespaces as $namespacePrefix=>$absoluteBasePath) {
            $tNamespacePrefix = trim($namespacePrefix, self::NS_DELIMITER);
            $tAbsoluteBasePath = rtrim($absoluteBasePath, self::DR_DELIMITER);
            static::$namespacesPaths[$tNamespacePrefix] = $tAbsoluteBasePath;
            static::$namespaces[$tNamespacePrefix] = strlen($tNamespacePrefix);
        }
    }

    /**
     * @throws Exception
     */
    static public function start(){
        /** @var ___Autoloader $instance */
        $instance = static::getInstance();
        $instance->registerAutoloader();
    }

    /**
     * @return ___Autoloader
     */
    static protected function getInstance(){
        if (static::$instance == false){
            static::$instance = new ___Autoloader();
        }
        return static::$instance;
    }

    private function registerAutoloader()
    {
        try {
            spl_autoload_register(array($this, 'psr4autoload'));
        } catch (Exception $e) {
            throw new Exception("Autoloader error",1,$e);
        }
    }

    private function psr4autoload($class){
        if (strpos($class,self::NS_DELIMITER)===false){
            return;
        }
        $namespace = $this->findNamespace($class);
        if ($namespace == null){
            return;
        }
        $this->load($namespace, $class);
    }

    /**
     * @param string $class
     * @return bool|string
     */
    private function findNamespace($class)
    {
        $classLength = strlen($class);
        foreach(static::$namespaces as $namespace=>$len){
            if ($classLength<$len){
                continue;
            }
            if (substr($class,0,$len)===$namespace && $class[$len] == self::NS_DELIMITER){
                return $namespace;
            }
        }
        return false;
    }

    private function load($namespace, $class)
    {
        $path = $this->getPath($namespace,$class);
        require_once $path;
    }

    private function getPath($namespace, $class)
    {
        $nsLen = static::$namespaces[$namespace];
        $classPart = substr($class,$nsLen+1);
        $filePart = str_replace(self::NS_DELIMITER,self::DR_DELIMITER,$classPart);
        $basePath = static::$namespacesPaths[$namespace];
        return $basePath.self::DR_DELIMITER.$filePart.self::PHP_EXTENSION;
    }


}

___Autoloader::start();