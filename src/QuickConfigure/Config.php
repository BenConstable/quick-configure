<?php namespace QuickConfigure;

use QuickConfigure\Util\Manager as UtilManager;
use QuickConfigure\Util\Paths;

/**
 * Fetch generated config.
 *
 * Usage:
 *
 * <?php
 *
 * // Get a configured value from the default environment
 * new \QuickConfigure\Config;
 * $defaultValue = $config->get('configured_value');
 *
 * // and then from a different one
 * $devValue = $config->setEnv('development')->get('configured_value');
 *
 */
class Config {

    /**
     * Currently loaded config.
     *
     * @var stdClass
     */
    private $config = null;

    /**
     * Current environment.
     *
     * @var string
     */
    private $env;

    /**
     * Utility manager.
     *
     * @var \QuickConfigure\Util\Manager
     */
    private $utils;

    /**
     * Constructor.
     *
     * Set env and load config.
     *
     * @param string $env Optianlly set environment
     * @return void
     */
    public function __construct($env = null)
    {
        $this->env = $env;

        $pathsHelper = new Paths;
        $pathsHelper->setGeneratedConfigFilePrefix($env ?: '');

        $this->utils = new UtilManager;
        $this->utils->register('paths', $pathsHelper);

        $this->loadConfig();
    }

    /**
     * Fetch a config value from the store.
     *
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return $this->config->$key;
    }

    /**
     * Set the config environment.
     *
     * @param string $env
     * @return \QuickConfigure\Config
     */
    public function setEnv($env)
    {
        $this->env = $env;
        $this->utils->get('paths')->setGeneratedConfigFilePrefix($env);
        $this->loadConfig();
        return $this;
    }

    /**
     * Get the current config environment.
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Load config in the current environment.
     *
     * @return \QuickConfigure\Config
     */
    private function loadConfig()
    {
        $this->config = json_decode(file_get_contents(
            $this
                ->utils
                ->get('paths')
                ->getGeneratedConfigDir() . '/' .
            $this
                ->utils
                ->get('paths')
                ->getGeneratedConfigFileName()
        ));

        return $this;
    }
}
