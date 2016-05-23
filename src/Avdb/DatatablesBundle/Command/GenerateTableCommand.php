<?php
namespace Avdb\DatatablesBundle\Command;

use Avdb\DatatablesBundle\DependencyInjection\Configuration;
use Avdb\DatatablesBundle\Exception\InvalidArgumentException;
use Avdb\DatatablesBundle\Exception\RuntimeException;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class GenerateTableCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var array
     */
    private $columnConfig = [];

    /**
     *  Set command name, description & alias
     */
    protected function configure()
    {
        $this->setName('datatables:table:generate');
        $this->setAliases(['generate:datatables:table']);

        $this->setDescription('Generates a table by command');
    }

    /**
     * Initializes the Console IO
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * Generates a DatatableFactory & a DataExtractor for you
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \Twig_Error
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $renderer = $this->getContainer()->get('templating');

        /** @var Application $application */
        $application = $this->getApplication();

        $this->io->text('Welcome to the Datatables generator');
        $this->io->text('Specify the name of the table you wish to create. Specify as AppBundle:Window');

        $name = $this->io->ask('Provide table name. For example \'AppBundle:Window\'');

        if (count(explode(':', $name)) < 2) {
            throw new InvalidArgumentException(
                "Table name $name is not a valid name, please include your bundle in the name, ie ; AppBundle:Name"
            );
        }

        $bundleName = explode(':', $name)[0];
        $tableName = explode(':', $name)[1];

        $bundle = $application->getKernel()->getBundle($bundleName);

        if (!$bundle instanceof BundleInterface) {
            throw new InvalidArgumentException("Bundle $bundleName was not found in this project.");
        }

        $factoryPath = $bundle->getPath() . Configuration::PATH_FACTORY;
        $extractorPath = $bundle->getPath() . Configuration::PATH_EXTRACTOR;
        $configPath = $bundle->getPath() . Configuration::PATH_CONFIG;

        if (!@mkdir($factoryPath, 0777, true) && !is_dir($factoryPath)) {
            throw new RuntimeException('Could not create directory ' . $factoryPath);
        }

        if (!@mkdir($extractorPath, 0777, true) && !is_dir($extractorPath)) {
            throw new RuntimeException('Could not create directory ' . $extractorPath);
        }

        if (!@mkdir($configPath, 0777, true) && !is_dir($configPath)) {
            throw new RuntimeException('Could not create directory ' . $configPath);
        }

        $factoryFile = $factoryPath . '/' . ucfirst($tableName) . Configuration::SUFFIX_FACTORY;
        $extractorFile = $extractorPath . '/' . ucfirst($tableName) . Configuration::SUFFIX_EXTRACTOR;
        $configFile = $configPath . '/' . strtolower($tableName) . Configuration::SUFFIX_CONFIG;

        if (file_exists($factoryFile) || file_exists($extractorFile) || file_exists($configFile)) {
            throw new RuntimeException('Factory and/or extractor for ' . $tableName . ' already exists');
        }

        $this->io->text('Specify the columns you want to add to your table.');
        $this->askForColumns();
        $cols = count($this->columnConfig);
        $this->io->text("We will create a $tableName table with $cols columns");

        $columns = '';
        foreach ($this->columnConfig as $name => $column) {
            $columns .= $renderer->render('DatatablesBundle:templates:create-column.php.twig', [
                'name'     => $name,
                'property' => $column['property'],
                'label'    => $column['label'],
            ]);
        }

        $factoryContent = $renderer->render('DatatablesBundle:templates:factory.php.twig', [
            'bundle'  => ucfirst($bundleName),
            'ucName'  => ucfirst($tableName),
            'lcName'  => strtolower($tableName),
            'columns' => $columns,
        ]);

        file_put_contents($factoryFile, $factoryContent);

        $extractorContent = $renderer->render('DatatablesBundle:templates:extractor.php.twig', [
            'bundle' => ucfirst($bundleName),
            'ucName' => ucfirst($tableName),
            'lcName' => strtolower($tableName),
        ]);

        file_put_contents($extractorFile, $extractorContent);

        $configContent = $renderer->render('DatatablesBundle:templates:table-config.yml.twig', [
            'bundle' =>  $bundleConfigName = str_replace('bundle', '', strtolower($bundleName)),
            'name'   => strtolower($tableName),
            'class_extractor' => sprintf(Configuration::NAMESPACE_EXTRACTOR, ucfirst($bundleName), ucfirst($tableName)),
            'class_factory'   => sprintf(Configuration::NAMESPACE_FACTORY, ucfirst($bundleName), ucfirst($tableName)),
        ]);

        file_put_contents($configFile, $configContent);
    }

    /**
     *  Keeps asking for columns as long as you want it.
     */
    private function askForColumns()
    {
        $name = $this->io->ask('Column name, to stop creating columns, press enter', false);

        if (!$name) {
            return;
        }
        $label = $this->io->ask('Column label', $name);
        $property = $this->io->ask('Column property', $name);

        $this->columnConfig[$name] = [
            'label'    => $label,
            'property' => $property,
        ];

        $this->askForColumns();
    }
}
