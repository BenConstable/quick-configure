<?php namespace QuickConfigure\Dump\Contracts;

/**
 * Interface for objects that should dump config in a particular format.
 *
 * Dumped config will be output to file.
 */
interface DumperInterface {

    /**
     * Dump the given config to a particular format.
     *
     * @param stdClass $config Config to dump
     * @return string Dump config
     */
    public function dump($config);

    /**
     * Return the extension of the dumped file.
     *
     * @return string Extension e.g 'php' or 'json'
     */
    public function getExtension();
}
