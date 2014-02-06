<?php namespace QuickConfigure\Dump;

use QuickConfigure\Support\Manager as AbstractManager;

class Manager extends AbstractManager {

    /**
     * Constructor.
     *
     * Register default dumpers.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->register('php', new ArrayDumper);
        $this->register('json', new JsonDumper);
    }
}
