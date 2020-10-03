<?php

namespace App\Command;

use App\Entity\Poi;
use App\Service\Geocode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PoiEnrichCommand extends Command
{
    const DEFAULT_LIMIT = 10;
    protected static $defaultName = 'poi:enrich';

    private EntityManagerInterface $em;
    private Geocode $geocode;


    public function __construct(EntityManagerInterface $em, Geocode $geocode, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->geocode = $geocode;
    }

    protected function configure()
    {
        $this
          ->setDescription('Reverse-geocode the POIs missing their address.')
          ->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Maximum no. of POIs to enrich')
          ->addOption('info', 'i', InputOption::VALUE_NONE, 'Just print the number of POI to enrich, and exit.')
          ->addOption('stoponerror', 's', InputOption::VALUE_NONE, 'Stop at first error')
          ->addOption('force', 'f', InputOption::VALUE_NONE, 'Actually write data to DB');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = $input->getOption('limit');
        $stoponerror = $input->getOption('stoponerror');
        $force = $input->getOption('force');

        if ($input->getOption('info')) {
            $limit = null;
        } else {
            if ($limit <= 0) {
                $io->writeln('<info>Automatically limiting the number of records to '.self::DEFAULT_LIMIT.'</info>');
                $limit = self::DEFAULT_LIMIT;
            }
        }

        $poi = $this->em->getRepository(Poi::class)->findBy(['address' => null], ['updatedAt' => 'ASC'], $limit);

        if ($input->getOption('info')) {
            $io->writeln('Total number of POIs to enrich: <info>'.count($poi).'</info>');

            return Command::SUCCESS;
        }

        // Create progressbar
        $pbar = new ProgressBar($output, count($poi));
        $pbar->start();
        $processed = 0;

        /** @var Poi $p */
        foreach ($poi as $p) {

            $processed++;

            $output = $this->geocode->callWebservice($p);

//            $io->writeln(print_r($output, true), OutputInterface::VERBOSITY_VERY_VERBOSE);

            if (empty($output['results'][0]['locations'][0])) {
                $pbar->clear();
                $io->writeln('<error>No results</error> for POI #'.$p->getId()." (".$p->getTitle().")");
                $io->writeln(
                  'API returned '.($output ? print_r($output, true) : 'null'),
                  OutputInterface::VERBOSITY_VERBOSE
                );
                if ($stoponerror) {
                    return Command::FAILURE;
                }

                $pbar->display();
                $pbar->advance();
                continue;
            }

            $pbar->advance();

            /**
             * https://developer.mapquest.com/documentation/geocoding-api/reverse/get/
             */
            if ($force) {
                $result = $output['results'][0]['locations'][0];
                $p
                  ->setAddress($result['street'])
                  ->setCity($result['adminArea5'])
                  ->setCountry($result['adminArea1'])
                  ->setProvince($result['adminArea3'] ?? '-')
                  ->setRegion($result['adminArea3'] ?? '-')
                  ->setZip($result['postalCode']);
                $this->em->persist($p);
                if (!$processed % 50) {
                    $this->em->flush();
                }
            }
        }

        $this->em->flush();

        $pbar->finish();
        $io->newLine(2);

        return Command::SUCCESS;
    }
}