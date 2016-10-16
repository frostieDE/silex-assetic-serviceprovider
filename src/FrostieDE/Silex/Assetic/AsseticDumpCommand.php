<?php

namespace FrostieDE\Silex\Assetic;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AsseticDumpCommand extends Command {
    public function configure() {
        $this
            ->setName('assetic:dump')
            ->setDescription('Dumps all assets');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $app = $this->getSilexApplication();

        /** @var Dumper $dumper */
        $dumper = $app['assetic.dumper'];

        $output->write('Dumping assets...');
        try {
            $dumper->dump();
            $output->writeln('OK');
        } catch(\Exception $e) {
            $output->writeln('ERROR');
            $this->getApplication()->renderException($e, $output);
        }

    }
}