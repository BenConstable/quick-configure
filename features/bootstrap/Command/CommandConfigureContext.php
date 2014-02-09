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

use QuickConfigure\Command\ConfigureCommand;

/**
 * Configure feature context.
 */
class CommandConfigureContext extends BehatContext
{
    /**
     * @var string
     */
    private $output;

    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @var string
     */
    private $env;

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
     * @Given /^I have a config file at "([^"]*)"$/
     */
    public function iHaveAConfigFileAt($arg1)
    {
        $this->configFilePath = getcwd() . "/{$arg1}/";

        Assertion::file($this->configFilePath . 'quick-configure.json');
    }

    /**
     * @Given /^I set the environment as "([^"]*)"$/
     */
    public function iSetTheEnvironmentAs($arg1)
    {
        $this->env = $arg1;
    }

    /**
     * @When /^I run the Configure command$/
     */
    public function iRunTheConfigureCommand()
    {
        $application = new Application();
        $application->add(new ConfigureCommand());

        $command = $application->find('configure');
        $commandTester = new CommandTester($command);

        $dialog = $command->getHelper('dialog');
        $dialog->setInputStream($this->getMainContext()->mockInputStream(array(
            'Config response one',
            'Config response two'
        )));

        $commandTester->execute(array(
            'command' => $command->getName(),
            '--path'  => $this->configFilePath,
            '--env'   => $this->env
        ));

        $this->output = $commandTester->getDisplay();
    }

    /**
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee($arg1)
    {
        Assertion::regex($this->output, '/' . preg_quote($arg1) . '/');
    }
}
