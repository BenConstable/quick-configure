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

use QuickConfigure\Command\DumpCommand;
use QuickConfigure\Util\Manager,
    QuickConfigure\Util\Paths;

/**
 * Dump feature context.
 */
class CommandDumpContext extends BehatContext
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
    private $format;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var boolean
     */
    private $stdOut;

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
        $this->format = 'json';
        $this->path = '.';
        $this->fileName = 'dumped_config';
        $this->stdOut = false;
    }

    /**
     * @Given /^I specify the path "([^"]*)"$/
     */
    public function iSpecifyThePath($arg1)
    {
        $this->path = $arg1;

        Assertion::directory(getcwd() . '/' . $this->path);
    }

    /**
     * @Given /^I specify the filename "([^"]*)"$/
     */
    public function iSpecifyTheFilename($arg1)
    {
        $this->fileName = $arg1;
    }

    /**
     * @Given /^I specify the format "([^"]*)"$/
     */
    public function iSpecifyTheFormat($arg1)
    {
        $this->format = $arg1;
    }

    /**
     * @Given /^I specify STDOUT$/
     */
    public function iSpecifyStdout()
    {
        $this->stdOut = true;
    }

    /**
     * @When /^I run the dump command$/
     */
    public function iRunTheDumpCommand()
    {
        $pathUtil = new Paths;
        $pathUtil->setGeneratedConfigDir((dirname(__FILE__) . '/../../data'));

        $manager = new Manager;
        $manager->register('paths', $pathUtil);

        $application = new Application();
        $application->add(new DumpCommand($manager));

        $command = $application->find('dump');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--env'    => $this->env,
            '--format' => $this->format,
            '--path'   => $this->path,
            '--name'   => $this->fileName
        ));

        $this->output = $commandTester->getDisplay();
    }

    /**
     * @Then /^I should get a file containing that config$/
     */
    public function iShouldGetAFileContainingThatConfig()
    {
        $file = $this->path . '/' . $this->fileName . '.' . $this->format;

        Assertion::file($file);

        $contents = file_get_contents($file);

        foreach ((array) $this->config as $key => $value) {
            Assertion::regex($contents, '/' . preg_quote("[$key]") . '/');
        }
    }

    /**
     * @Given /^it should be in JSON format$/
     */
    public function itShouldBeInJsonFormat()
    {
        $file = $this->path . '/' . $this->fileName . '.' . $this->format;

        Assertion::file($file);
        Assertion::notNull(json_decode(file_get_contents($file)));
    }

    /**
     * @Then /^I should see that config on my CLI$/
     */
    public function iShouldSeeThatConfigOnMyCli()
    {
        foreach ((array) $this->config as $key => $value) {
            Assertion::regex($this->output, '/' . preg_quote("[$key]") . '/');
        }
    }
}
