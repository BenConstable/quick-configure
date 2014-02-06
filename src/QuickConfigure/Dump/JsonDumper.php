<?php namespace QuickConfigure\Dump;

use QuickConfigure\Dump\Contracts\DumperInterface;

class JsonDumper implements DumperInterface {

    /**
     * Dump config to JSON.
     *
     * @see \QuickConfigure\Dump\Contracts\DumperInterface
     * @param stdClass $config
     * @return string
     */
    public function dump($config)
    {
        return json_encode($config);
    }

    /**
     * Get file extension ('json').
     *
     * @return string
     */
    public function getExtension()
    {
        return 'json';
    }
}
