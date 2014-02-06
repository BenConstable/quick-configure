<?php namespace QuickConfigure\Support;

use QuickConfigure\Support\Contracts\ManagerInterface;

/**
 * Abstract implementation of object Managers.
 */
abstract class Manager implements ManagerInterface {

    /**
     * Set of managed objects.
     *
     * @var array
     */
    protected $managed;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->managed = array();
    }

    /**
     * Get a registered object.
     *
     * @param string $key Identifier
     * @return mixed Registered object
     */
    public function get($key)
    {
        return $this->managed[$key];
    }

    /**
     * Register an objecy with the Manager.
     *
     * @param string $key  Identifier
     * @param mixed  $item Object to register
     * @return \QuickConfigure\Support\Manager
     */
    public function register($key, $item)
    {
        $this->managed[$key] = $item;
        return $this;
    }
}
