<?php

namespace App\Command;

use App\Entity\Poi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\PoiCategory;

class PoiImportCommand extends Command
{
    protected static $defaultName = 'poi:import';
    private $em;

    const RECORD_BLOCK_SIZE = 210;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
          ->setDescription('Import a list of POIs from a CSV file.')
          ->addArgument('file', InputArgument::REQUIRED, 'Path of the CSV file to import')
          ->addOption('force', 'f', InputOption::VALUE_NONE, 'Actually write data to DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('file');

        $file = fopen($filename, 'r');
        $io->note("File ok");

        $categories = $this->em->getRepository(PoiCategory::class)->findAll();

        /** @var PoiCategory $chosenCategory */
        $chosenCategory = null;
        if (!count($categories)) {
            $io->warning("No categories found, at least one is needed to proceed.");
        } else {

            $catChoices = ['(Create a new category)'];
            foreach ($categories as $cat) {
                $catChoices[] = $cat->getTitle();
            }
            $catTitle = $io->choice("Please choose a category", $catChoices);
            if ($catTitle !== '(Create a new category)') {
                $chosenCategory = $this->em
                  ->getRepository(PoiCategory::class)
                  ->findOneBy(['title' => $catTitle]);
            }
        }

        if (!$chosenCategory) {
            $catTitle = $io->ask("Please enter the new category name");
            $chosenCategory = new PoiCategory();
            $chosenCategory
              ->setTitle($catTitle)
              ->setActive(true);

            if ($input->getOption('force')) {
                if (!$io->confirm(sprintf("Ok to create category '%s'?", $catTitle), false)) {
                    $io->writeln('Bye bye');

                    return Command::FAILURE;
                }

                $this->em->persist($chosenCategory);
                $this->em->flush();
            }
        }

        $rows = 0;
        while (fgets($file) !== false) {
            $rows++;
        }

        $pbar = new ProgressBar($output, $rows);
        $pbar->start();

        fseek($file, 0, SEEK_SET);
        $row = 0;
        // Skip headers
        fgets($file);
        $retval = Command::SUCCESS;
        while (!feof($file)) {
            $row++;
            try {
                // !Importante! L'ordine delle colonne va verificato nel file di origine
                list($lon, $lat, $title) = fgetcsv($file);
                $poi = new Poi();
                // POINT({LON} {LAT})
                $poi
                  ->setCoords(sprintf('POINT(%f %f)', floatval($lon), floatval($lat)))
                  ->setTitle($title)
                  ->setCategory($chosenCategory);

                if ($input->getOption('force')) {
                    $this->em->persist($poi);
                    if (!($row % self::RECORD_BLOCK_SIZE)) {
                        $this->em->flush();
                    }
                }

                $pbar->advance();
            } catch (\Exception $e) {
                $pbar->clear();
                $io->warning("Error at row ".$row.": ".$e->getMessage());
                $retval = Command::FAILURE;
                break;
            }
        }

        // Flush dell'ultimo gruppo di record persist()iti
        if ($retval === Command::SUCCESS && $input->getOption('force')) {
            $this->em->flush();
        }

        fclose($file);
        $pbar->finish();
        $io->newLine(2);

        return $retval;
    }
}
