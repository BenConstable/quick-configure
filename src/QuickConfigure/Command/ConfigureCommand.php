<?php namespace QuickConfigure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use QuickConfigure\Util\Manager as UtilManager;
use QuickConfigure\Validation\FormatValidator;

class ConfigureCommand extends Command {

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
            ->setName('configure')
            ->setDescription("Generate config from $file")
            ->addOption(
                'env',
                'e',
                InputOption::VALUE_REQUIRED,
                "Environment to configure. Global if excluded"
            )
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                "Path to $file file (current directory by default)",
                null
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

        $file       = $this->utils->getPathsHelper()->getConfigFileName();
        $filePath   = ($input->getOption('path') ?: getcwd()) . '/' . $file;
        $dialog     = $this->getHelperSet()->get('dialog');
        $configured = array();

        if (file_exists($filePath)) {
            $output->writeln(
                "<info>$file file found, configuring for " .
                ($input->getOption('env') ?: 'global') .
                " env...</info>"
            );
            $output->writeln('');

            $config    = json_decode(file_get_contents($filePath));
            $validator = new FormatValidator($config);

            if ($validator->validate()) {
                $this->utils->getPathsHelper()->setGeneratedConfigFilePrefix($input->getOption('env') ?: '');

                $configured = array();
                $genDir     = $this->utils->getPathsHelper()->getGeneratedConfigDir();
                $genFile    = $this->utils->getPathsHelper()->getGeneratedConfigFileName();

                foreach ($config as $key => $settings) {
                    $configured[$key] = $dialog->ask(
                        $output,
                        "    <comment>[$key] {$settings->description} :</comment> ",
                        isset($settings->default) ? $settings->default : null
                    );
                    $output->writeln('');
                }

                file_put_contents("{$genDir}/{$genFile}", json_encode($configured));

                $output->writeln('<info>...configured!</info>');
            } else {
                $output->writeln('<error>' . implode("\n", $validator->getErrors()) . '</error>');
            }

            $output->writeln('');
        } else {
            $output->writeln("<error>$file file not found</error>");
        }
    }
}
