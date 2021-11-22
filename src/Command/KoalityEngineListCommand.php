<?php

namespace KoalityEngine\Cli\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class KoalityEngineListCommand
 *
 * Render a list in different formats.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-22
 */
abstract class KoalityEngineListCommand extends KoalityEngineCommand
{
    const OPTION_OUTPUT_FORMAT = 'outputFormat';

    const FORMAT_TABLE = 'table';
    const FORMAT_CSV = 'csv';

    const CSV_DELIMITER = ';';
    const CSV_ENCLOSURE = '"';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this->addOption(self::OPTION_OUTPUT_FORMAT, 'o', InputOption::VALUE_OPTIONAL, 'Output format for the result. Possible formats are table and csv.', self::FORMAT_TABLE);
    }

    /**
     * Render a list in different formats.
     *
     * Possible formats: table, csv
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $header
     * @param array $rows
     */
    protected function renderList(InputInterface $input, OutputInterface $output, array $header, array $rows)
    {
        switch (strtolower($input->getOption(self::OPTION_OUTPUT_FORMAT))) {
            case self::FORMAT_TABLE:
                $this->renderTable($output, $header, $rows);
                break;
            case self::FORMAT_CSV:
                $this->renderCsv($output, $header, $rows);
                break;
            default:
                throw new \RuntimeException('No output format "' . $input->getOption(self::OPTION_OUTPUT_FORMAT) . '" found. Use table or csv.');
        }
    }

    /**
     * Render an ASCII table.
     *
     * @param OutputInterface $output
     * @param array $header
     * @param array $rows
     */
    private function renderTable(OutputInterface $output, array $header, array $rows)
    {
        $table = new Table($output);
        $table
            ->setHeaders($header)
            ->setRows($rows);
        $table->render();
    }

    /**
     * Render CSV output.
     *
     * @param OutputInterface $output
     * @param array $header
     * @param array $rows
     */
    private function renderCsv(OutputInterface $output, array $header, array $rows)
    {
        $output->writeln($this->convertArrayToCsv($header));

        foreach ($rows as $row) {
            $cleanRow = [];
            foreach ($row as $element) {
                $cleanRow[] = str_replace("\n", " - ", $element);
            }
            $output->writeln($this->convertArrayToCsv($cleanRow));
        }
    }

    /**
     * Convert an array to a CSV string.
     *
     * @param array $input
     *
     * @return string
     */
    private function convertArrayToCsv(array $input)
    {
        $fp = fopen('php://temp', 'r+');
        fputcsv($fp, $input, self::CSV_DELIMITER, self::CSV_ENCLOSURE);
        rewind($fp);
        $data = fread($fp, 1048576);
        fclose($fp);
        return rtrim($data, "\n");
    }
}
