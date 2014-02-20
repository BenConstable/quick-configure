<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Assert\Assertion;

/**
 * Root features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     *
     * @param array $parameters context parameters
     */
    public function __construct(array $parameters)
    {
        $this->useContext(
            'commandconfigurecontext',
            new CommandConfigureContext($parameters)
        );

        $this->useContext(
            'commandshowcontext',
            new CommandShowContext($parameters)
        );

        $this->useContext(
            'commanddumpcontext',
            new CommandDumpContext($parameters)
        );
    }

    /**
     * Mock the input stream for console commands.
     *
     * @param string|array $input   If array, will join with newlines
     * @param boolean      $newLine Append a newline to the input (true by default)
     * @return \resource
     */
    public function mockInputStream($input, $newLine = true)
    {
        if (is_array($input)) {
            $input = implode("\n", $input);
        }

        $input .= ($newLine ? "\n" : '');

        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    /**
     * @Given /^I set the environment as "([^"]*)"$/
     */
    public function iSetTheEnvironmentAs($arg1)
    {
        $this->env = $arg1;
    }

    /**
     * @Given /^I have some generated config at "([^"]*)"$/
     */
    public function iHaveSomeGeneratedConfigAt($arg1)
    {
        $this->configFilePath = getcwd() . "/{$arg1}/";

        Assertion::file($this->configFilePath . 'configured.json');

        $this->config = json_decode(file_get_contents($this->configFilePath . 'configured.json'));

        Assertion::isInstanceOf($this->config, 'stdClass');
    }
}
