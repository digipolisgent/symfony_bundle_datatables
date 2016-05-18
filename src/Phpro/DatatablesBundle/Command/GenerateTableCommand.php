<?php
namespace Phpro\DatatablesBundle\Command;

use Phpro\DatatablesBundle\Exception\InvalidArgumentException;
use Phpro\DatatablesBundle\Exception\RuntimeException;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class GenerateTableCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var array
     */
    private $columnConfig = [];

    const PATH_FACTORY     = '/Datatables/Factory';
    const PATH_EXTRACTOR   = '/Datatables/DataExtractor';
    const PATH_CONFIG      = '/Resources/config/Datatables';

    const SUFFIX_FACTORY   = 'DatatableFactory.php';
    const SUFFIX_EXTRACTOR = 'DataExtractor.php';
    const SUFFIX_CONFIG    = '.datatable.yml';

    const NAMESPACE_FACTORY = '%s\Datatables\Factory\%sDatatableFactory';
    const NAMESPACE_EXTRACTOR = '%s\Datatables\DataExtractor\%sDataExtractor';

    protected function configure()
    {
        $this->setName('datatables:table:generate');
        $this->setDescription('Generates a table by command');
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
        $this->input = $input;
        $this->output = $output;
        $renderer = $this->getContainer()->get('templating');

        /** @var Application $application */
        $application = $this->getApplication();

        $output->writeln('<info>Welcome to the Datatables generator</info>');
        $output->writeln('<info>Specify the name of the table you wish to create. Specify as AppBundle:Window</info>');

        $name = $this->ask('Datatable name', null);

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

        $factoryPath = $bundle->getPath() . self::PATH_FACTORY;
        $extractorPath = $bundle->getPath() . self::PATH_EXTRACTOR;
        $configPath = $bundle->getPath() . self::PATH_CONFIG;

        if (!@mkdir($factoryPath, 0777, true) && !is_dir($factoryPath)) {
            throw new RuntimeException('Could not create directory ' . $factoryPath);
        }

        if (!@mkdir($extractorPath, 0777, true) && !is_dir($extractorPath)) {
            throw new RuntimeException('Could not create directory ' . $extractorPath);
        }

        if (!@mkdir($configPath, 0777, true) && !is_dir($configPath)) {
            throw new RuntimeException('Could not create directory ' . $configPath);
        }

        $factoryFile = $factoryPath . '/' . ucfirst($tableName) . self::SUFFIX_FACTORY;
        $extractorFile = $extractorPath . '/' . ucfirst($tableName) . self::SUFFIX_EXTRACTOR;
        $configFile = $configPath . '/' . strtolower($tableName) . self::SUFFIX_CONFIG;

        if (file_exists($factoryFile) || file_exists($extractorFile) || file_exists($configFile)) {
            throw new RuntimeException('Factory and/or extractor for ' . $tableName . ' already exists');
        }

        $this->output->writeln('<info>Specify the columns you want to add to your table.</info>');
        $this->askForColumns();
        $cols = count($this->columnConfig);
        $this->output->writeln("<info>We will create a $tableName table with $cols columns</info>");

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
            'class_extractor' => sprintf(self::NAMESPACE_EXTRACTOR, ucfirst($bundleName), ucfirst($tableName)),
            'class_factory'   => sprintf(self::NAMESPACE_FACTORY, ucfirst($bundleName), ucfirst($tableName)),
        ]);

        file_put_contents($configFile, $configContent);
    }

    /**
     *  Keeps asking for columns as long as you want it.
     */
    private function askForColumns()
    {
        $name = $this->ask('Column name (to stop creating columns, press enter)', false);

        if (!$name) {
            return;
        }
        $label = $this->ask("Column label ($name)", $name);
        $property = $this->ask("Column property ($name)", $name);

        $this->columnConfig[$name] = [
            'label'    => $label,
            'property' => $property,
        ];

        $this->askForColumns();
    }

    /**
     * Asks a question to the user
     *
     * @param $question
     * @param $default
     * @return string
     */
    private function ask($question, $default)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        return trim($helper->ask($this->input, $this->output,
            new Question("<question>$question :</question> ", $default)));
    }
}
