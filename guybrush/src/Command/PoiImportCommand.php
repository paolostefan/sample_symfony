<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
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

  public function __construct(\Doctrine\ORM\EntityManagerInterface $em) {
    parent::__construct();
    $this->em = $em;
  }

  protected function configure() {
    $this
      ->setDescription('Import a list of POIs from a CSV file.')
      ->addArgument('file', InputArgument::REQUIRED, 'Path of the CSV file to import')
      ->addOption('force', 'f', InputOption::VALUE_NONE, 'Actually write data to DB');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);
    $filename = $input->getArgument('file');

    $file = fopen($filename, 'r');
    $io->note("File ok");

    $categories = $this->em->getRepository(PoiCategory::class)->findAll();

    /** @var PoiCategory $chosenCategory */
    $chosenCategory = NULL;
    if (!count($categories)) {
      $io->warning("No categories found, I need at least one to proceed");
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
        ->setActive(TRUE);

      if ($input->getOption('force')) {
        if (!$io->confirm(sprintf("Ok to create category '%s'?", $catTitle), FALSE)) {
          $io->writeln('Bye bye');
          return Command::FAILURE;
        }

        $this->em->persist($chosenCategory);
        $this->em->flush();
      }
    }

    return Command::SUCCESS;
  }
}
