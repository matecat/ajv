<?php
namespace Matecat\AJV\Console;

use Matecat\AJV\Checker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
                ->setName('ajv:json:check')
                ->setDescription('Validate an Airbnb JSON file.')
                ->setHelp('This command allows you to validate an Airbnb JSON file.')
                ->addArgument('json-path', InputArgument::REQUIRED, 'JSON file path')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonPath =  $input->getArgument('json-path');
        $report = (new Checker($jsonPath))->report();

        $io = new SymfonyStyle($input, $output);
        $io->title("Validation report for file " . $jsonPath);

        $table = new Table($output);
        $table->addRow(['IS A VALID JSON', $report['status']]);
        $table->addRow(new TableSeparator());
        $table->addRow(['ERROR(S)', $this->formatErrors($report['errors'])]);
        $table->render();
        $io->newLine();

        if ($this->isValid($report)) {
            $io->success('Validation passed.');
        } else {
            $io->error('Validation NOT passed.');
        }
    }

    private function formatErrors(array $errors): string
    {
        $string = '';

        foreach ($errors as $key => $errs) {
            foreach ($errs as $error) {
                $string .= 'TYPE: ' . $key. PHP_EOL;
                $string .= 'NODE: ' . $error['node']. PHP_EOL;
                $string .= 'ID: ' . $error['id']. PHP_EOL;
                $string .= 'SEGMENT ID: ' . $error['segment_id']. PHP_EOL;
                $string .= 'KEY: ' . $error['key']. PHP_EOL;
                $string .= 'MESSAGE: ' . $error['message']. PHP_EOL;

                $endErrors = end($errors);
                if ($error !== end($endErrors)) {
                    $string .= PHP_EOL;
                }
            }
        }

        return $string;
    }

    private function isValid(array $report = []): bool
    {
        if (count($report['errors']) > 0) {
            return false;
        }

        if ($report['status'] !== 'OK') {
            return false;
        }

        return true;
    }
}
