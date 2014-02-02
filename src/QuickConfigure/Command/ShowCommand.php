<?php namespace QuickConfigure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use QuickConfigure\Util\Manager as UtilManager;
use QuickConfigure\Validation\FormatValidator;

class ShowCommand extends Command {

    /**
     * Utility manager.
     *
     * @var \QuickConfigure\Util\Manager
     */
    private $utils;

    /**
     * Constructor.
     *
     * Configure utils and delegate to parent.
     *
     * @return void
     */
    public function __construct()
    {
        $this->utils = new UtilManager;

        parent::__construct();
    }

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure()
    {
        $file = $this->utils->getPathsHelper()->getConfigFileName();

        $this
            ->setName('show')
            ->setDescription("Show generated config")
            ->addOption(
                'env',
                'e',
                InputOption::VALUE_REQUIRED,
                "Show config for given environment. Global by default"
            )
        ;
    }

    /**
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        $this->utils->getPathsHelper()->setGeneratedConfigFilePrefix($input->getOption('env') ?: '');

        $file     = $this->utils->getPathsHelper()->getGeneratedConfigFileName();
        $filePath = $this->utils->getPathsHelper()->getGeneratedConfigDir();
        $env      = $input->getOption('env');

        if (file_exists("{$filePath}/{$file}")) {
            $config = json_decode(file_get_contents("{$filePath}/{$file}"));

            foreach ($config as $key => $value) {
                $output->writeln("    <comment>" . $this->format($config, $key) . " :</comment> $value");
            }

            $output->writeln('');
        } else {
            $formatter = $this->getHelperSet()->get('formatter');

            $errors = array(
                'Generated config not found, try running:',
                '',
                '    quick-configure configure' . ($env ? " --env $env" : ''),
                '',
                'to generate the config first'
            );

            $output->writeln($formatter->formatBlock($errors, 'error'));
            $output->writeln('');
        }
    }

    /**
     * Nicely format keys for console printing.
     *
     * @param stdClass $config Config array
     * @param string   $key    Key to format
     * @return string Formatted key
     */
    private function format($config, $key)
    {
        $max    = max(array_map('strlen', array_keys((array) $config)));
        $append = '';

        if (($diff = $max - strlen($key)) > 0) {
            $append = str_repeat(' ', $diff);
        }

        return "[$key]$append";
    }
}
