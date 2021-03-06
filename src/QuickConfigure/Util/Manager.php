<?php namespace QuickConfigure\Util;

use QuickConfigure\Support\Manager as AbstractManager;

class Manager extends AbstractManager {

    /**
     * Constructor.
     *
     * Register default utilities.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->register('paths', new Paths);
    }
}
