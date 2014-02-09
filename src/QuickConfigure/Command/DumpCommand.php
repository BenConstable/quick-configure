<?php namespace QuickConfigure\Command;

use Exception;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use QuickConfigure\Util\Manager as UtilManager;
use QuickConfigure\Dump\Manager as DumpManager;
use QuickConfigure\Validation\FormatValidator;

class DumpCommand extends Command {

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
        $file = $this->utils->get('paths')->getConfigFileName();

        $this
            ->setName('dump')
            ->setDescription('Dump config to file')
            ->addOption(
                'env',
                'e',
                InputOption::VALUE_REQUIRED,
                'Environment config to dump. Global if excluded',
                ''
            )
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to dumped file',
                getcwd()
            )
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'Dumped filename',
                'dumped_config'
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Output format. Either JSON [json] or PHP array [php]',
                'json'
            )
            ->addOption(
                'stdout',
                null,
                InputOption::VALUE_NONE,
                'Dump output to STDOUT, rather than file'
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
        $this
            ->utils
            ->get('paths')
            ->setGeneratedConfigFilePrefix($input->getOption('env') ?: '');

        $file     = $this->utils->get('paths')->getGeneratedConfigFileName();
        $filePath = $this->utils->get('paths')->getGeneratedConfigDir() . '/' . $file;
        $env      = $input->getOption('env');
        $manager  = new DumpManager;

        if (!file_exists($filePath)) {
            throw new Exception(implode("\n", array(
                'Generated config not found, try running:',
                '',
                '    quick-configure configure' . ($env ? " --env $env" : ''),
                '',
                'to generate the config first'
            )));
        }

        $config = json_decode(file_get_contents($filePath));

        $dumper = $manager->get($input->getOption('format'));
        $dumped = $dumper->dump($config);

        if ($input->getOption('stdout')) {
            $output->writeln($dumped);
        } else {
            $outputFile =
                $input->getOption('path') .'/' .
                $input->getOption('name') . '.' .
                $dumper->getExtension();

            file_put_contents($outputFile, $dumped);

            $output->writeln('');
            $output->writeln("    <info>Config dumped to $outputFile</info>");
            $output->writeln('');
        }
    }
}
