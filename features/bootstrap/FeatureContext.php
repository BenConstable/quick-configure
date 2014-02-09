<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

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
}
