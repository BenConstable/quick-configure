<?php namespace QuickConfigure\Util;

use QuickConfigure\Util\Paths;

class Manager {

    /**
     * Path helper utility.
     *
     * @var \QuickConfigure\Util\Paths
     */
    private $pathsHelper;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setPathsHelper(new Paths);
    }

    /**
     * Set the Path helper utility.
     *
     * @param \QuickConfigure\Util\Paths $pathsHelper
     * @return \QuickConfigure\Util\Manager
     */
    public function setPathsHelper($pathsHelper)
    {
        $this->pathsHelper = $pathsHelper;
        return $this;
    }

    /**
     * Get the Path helper utility.
     *
     * @return \QuickConfigure\Util\Paths
     */
    public function getPathsHelper()
    {
        return $this->pathsHelper;
    }
}
