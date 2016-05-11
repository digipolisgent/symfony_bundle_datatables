<?php

namespace Phpro\DatatablesBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateTableCommand extends Command
{
    protected function configure()
    {
        $this->setName('datatables:table:generate');
        $this->setDescription('Generates a table by command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
