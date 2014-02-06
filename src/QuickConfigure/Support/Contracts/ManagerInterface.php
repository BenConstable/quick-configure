<?php namespace QuickConfigure\Support\Contracts;

/**
 * A simple Manager interface for fetching registered objects.
 */
interface ManagerInterface {

    /**
     * Fetch a registered object.
     *
     * @param string $key Identifier
     * @return mixed Registered object
     */
    public function get($key);

    /**
     * Register an object with the Manager.
     *
     * @param string $key  Identifier
     * @param mixed  $item Object to regsiter
     */
    public function register($key, $item);
}
