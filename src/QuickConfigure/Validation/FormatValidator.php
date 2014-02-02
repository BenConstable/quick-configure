<?php namespace QuickConfigure\Validation;

/**
 * Validate a QuickConfigure configuration file.
 *
 * Not yet implemented, validation always returns true.
 */
class FormatValidator {

    /**
     * Config to validate.
     *
     * @var stdClass
     */
    private $config;

    /**
     * Current validation errors.
     *
     * @var array
     */
    private $errors;

    /**
     * Constructor.
     *
     * @param stdClass $config Config to validate
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->errors = array();
    }

    /**
     * Run validation on the current config
     *
     * @todo Implement
     * @return boolean
     */
    public function validate()
    {
        return true;
    }

    /**
     * Get current validation errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
