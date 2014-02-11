<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Assert\Assertion;

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Tester\CommandTester;

use QuickConfigure\Command\ShowCommand;
use QuickConfigure\Util\Manager,
    QuickConfigure\Util\Paths;

/**
 * Show feature context.
 */
class CommandShowContext extends BehatContext
{
    /**
     * @var string
     */
    private $output;

    /**
     * @var string
     */
    private $env;

    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @var stdClass
     */
    private $config;

    /**
     * Initializes context.
     *
     * @param array $parameters context parameters
     */
    public function __construct(array $parameters)
    {
        $this->env = '';
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

    /**
     * @When /^I run the Show command$/
     */
    public function iRunTheShowCommand()
    {
        $pathUtil = new Paths;
        $pathUtil->setGeneratedConfigDir((dirname(__FILE__) . '/../../data'));

        $manager = new Manager;
        $manager->register('paths', $pathUtil);

        $application = new Application();
        $application->add(new ShowCommand($manager));

        $command = $application->find('show');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command' => $command->getName(),
            '--env'   => $this->env
        ));

        $this->output = $commandTester->getDisplay();
    }

    /**
     * @Then /^I should see that config$/
     */
    public function iShouldSeeThatConfig()
    {
        foreach ((array) $this->config as $key => $value) {
            Assertion::regex($this->output, '/' . preg_quote("[$key]") . '/');
        }
    }
}
