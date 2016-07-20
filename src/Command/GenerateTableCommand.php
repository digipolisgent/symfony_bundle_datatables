<?php
namespace Avdb\DatatablesBundle\Command;

use Avdb\DatatablesBundle\DependencyInjection\Configuration;
use Avdb\DatatablesBundle\Exception\InvalidArgumentException;
use Avdb\DatatablesBundle\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

        $this->addArgument('name', InputArgument::OPTIONAL, 'table name. For example \'AppBundle:Window\'');

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

        if (($entityName = $input->getArgument('name')) === null) {
            $this->io->text('Welcome to the Datatables generator');
            $this->io->text('Specify the name of the table you wish to create. Specify as AppBundle:Window');

            $entityName = $this->io->ask('Provide table name. For example \'AppBundle:Window\'');
        }

        $isDoctrineEntity = $this->isEntityAlias($entityName);

        if (count(explode(':', $entityName)) < 2) {
            throw new InvalidArgumentException(
                "Table name $entityName is not a valid name, please include your bundle in the name, ie ; AppBundle:Name"
            );
        }

        list($bundleName, $tableName) = explode(':', $entityName);

        $bundle = $application->getKernel()->getBundle($bundleName);
        if (!$bundle instanceof BundleInterface) {
            throw new InvalidArgumentException("Bundle $bundleName was not found in this project.");
        }

        $bundleNamespace = $bundle->getNamespace();
        $bundleConfigName = $this->bundleNamespaceToPrefix($bundleNamespace);
        $factoryPath = $bundle->getPath() . Configuration::PATH_FACTORY;
        $extractorPath = $bundle->getPath() . Configuration::PATH_EXTRACTOR;
        $configPath = $bundle->getPath() . Configuration::PATH_CONFIG;

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
            'namespace'  => $bundleNamespace,
            'bundle'  => $bundleName,
            'table'  => $tableName,
            'columns' => $columns,
        ]);


        $extractorContent = $renderer->render('DatatablesBundle:templates:extractor.php.twig', [
            'entityName' => $entityName,
            'isDoctrineEntity' => $isDoctrineEntity,
            'namespace'  => $bundleNamespace,
            'bundle' => $bundleName,
            'table' => $tableName,
        ]);

        $configContent = $renderer->render('DatatablesBundle:templates:table-config.yml.twig', [
            'namespace'  => $bundleNamespace,
            'isDoctrineEntity' => $isDoctrineEntity,
            'bundle' =>  $bundleConfigName,
            'table'   => $tableName,
            'class_extractor' => sprintf(Configuration::NAMESPACE_EXTRACTOR, $bundleNamespace, ucfirst($tableName)),
            'class_factory'   => sprintf(Configuration::NAMESPACE_FACTORY, $bundleNamespace, ucfirst($tableName)),
        ]);

        $this->createDirectory($factoryPath);
        $this->createDirectory($extractorPath);
        $this->createDirectory($configPath);
        file_put_contents($factoryFile, $factoryContent);
        file_put_contents($extractorFile, $extractorContent);
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

    private function createDirectory($path)
    {
        if (!@mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException('Could not create directory ' . $path);
        }
    }

    private function isEntityAlias($name)
    {
        try {
            $this->getContainer()->get('doctrine.orm.default_entity_manager')->getClassMetadata($name);
            return true;
        } catch (\Exception $e) {}

        return false;
    }

    /**
     * @param string $namespace
     * @return string
     */
    private function bundleNamespaceToPrefix($namespace)
    {
        $prefix = '';

        foreach (explode('\\', $namespace) as $part) {
            $part = preg_replace('/Bundle$/', '', $part);
            if (strlen($part)) {
                $prefix .= $part;
            }
        }

        return strtolower(preg_replace('/(?!^)([A-Z])/', '_$1', $prefix));
    }
}
