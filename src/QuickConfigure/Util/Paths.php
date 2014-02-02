<?php namespace QuickConfigure\Util;

class Paths {

    /**
     * The name of the config that's supplied to the configure script.
     *
     * @var string
     */
    private $configFileName;

    /**
     * The path in which generated config files are stored.
     *
     * @var string
     */
    private $generatedConfigDir;

    /**
     * The base name of generated config files.
     *
     * @var string
     */
    private $generatedConfigFileName;

    /**
     * The prefix for generated config files.
     *
     * @var string
     */
    private $generatedConfigFilePrefix;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->configFileName = 'quick-configure.json';

        $this->generatedConfigDir = (dirname(__FILE__) . '/../../../gen');

        $this->generatedConfigFileName = 'configured.json';

        $this->generatedConfigFilePrefix = '';
    }

    /**
     * Get the name of the config that's supplied to the configure script.
     *
     * @return string
     */
    public function getConfigFileName()
    {
        return $this->configFileName;
    }

    /**
     * Set the name of the config that's supplied to the configure script.
     *
     * @param string $configFileName
     * @return \QuickConfigure\Util\Paths
     */
    public function setConfigFileName($configFileName)
    {
        $this->configFileName = $configFileName;
        return $this;
    }

    /**
     * Get the path in which generated config files are stored.
     *
     * @return string
     */
    public function getGeneratedConfigDir()
    {
        return $this->generatedConfigDir;
    }

    /**
     * Set the path in which generated config files are stored.
     *
     * @param string $generatedConfigDir Should be full path
     * @return \QuickConfigure\Util\Paths
     */
    public function setGeneratedConfigDir($generatedConfigDir)
    {
        $this->generatedConfigDir = $generatedConfigDir;
        return $this;
    }

    /**
     * Get the base name of generated config files.
     *
     * @param boolean $withPrefx Include the file prefix if set
     * @return string
     */
    public function getGeneratedConfigFileName($withPrefix = true)
    {
        return ($withPrefix ? $this->generatedConfigFilePrefix : '') . $this->generatedConfigFileName;
    }

    /**
     * Set the base name of generated config files.
     *
     * @param string $generatedConfigFileName
     * @return \QuickConfigure\Util\Paths
     */
    public function setGeneratedConfigFileName($generatedConfigFileName)
    {
        $this->generatedConfigFileName = $generatedConfigFileName;
        return $this;
    }

    /**
     * Get the prefix for generated config files.
     *
     * @return string
     */
    public function getGeneratedConfigFilePrefix()
    {
        return $this->generatedConfigFilePrefix;
    }

    /**
     * Set the prefix for generated config files.
     *
     * @param string $generatedConfigFileName
     * @return \QuickConfigure\Util\Paths
     */
    public function setGeneratedConfigFilePrefix($generatedConfigFilePrefix)
    {
        $this->generatedConfigFilePrefix = $generatedConfigFilePrefix;
        return $this;
    }
}
