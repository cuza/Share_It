<?php
namespace EWZ\Bundle\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class OptimizeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('lucene:optimize')
            ->setDescription('Optimizes Lucene Index');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Optimizing lucene index...");

        $this->getContainer()
            ->get('ewz_search.lucene')
            ->getIndex()
            ->optimize();

        $output->writeln('<info>Lucene index optimized successfully!</info>');
    }
}
