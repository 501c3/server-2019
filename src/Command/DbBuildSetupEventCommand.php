<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

namespace App\Command;

use App\Common\YamlDbSetupEvent;
use App\Common\YamlDbSetupEventTeam;
use App\Common\YamlDbSetupTeamClass;
use App\Signal\ProcessEvent;
use App\Signal\ProcessStatus;
use App\Signal\ProcessSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Symfony\Component\HttpKernel\KernelInterface;


class DbBuildSetupEventCommand extends Command
{
    const KEYS = ['models','domains','values','model-values','persons','teams','events','event-teams'];

    protected static $defaultName = 'db:build:setup-events';

    /** @var EventDispatcher */
    private $dispatcher;

    /** @var ProcessSubscriber */
    private $subscriber;

    private $setup = [];

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * DbBuildSetupCommand constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct('DbBuildSetup');
        /** @var EntityManagerInterface $em */
        $em = $kernel->getContainer()->get('doctrine.orm.setup_entity_manager');
        $this->entityManager = $em;
        $this->dispatcher = new EventDispatcher();
        $this->subscriber = new ProcessSubscriber();
        $this->dispatcher->addSubscriber($this->subscriber);
        //$this->setup['base-to-team'] = new YamlDbSetupTeamClass($em,$this->dispatcher);
        $this->setup['event'] = new YamlDbSetupEvent($em,$this->dispatcher);
        $this->setup['event-team'] = new YamlDbSetupEventTeam($em,$this->dispatcher);
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('masterFile', InputArgument::REQUIRED, 'Location of yaml master file.');

    }

    /**
     * @param string $masterFile
     * @param array $fileList
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     * @return int
     * @throws AppCommandException
     */
    private function initializeOutputFeedback(string $masterFile, array $fileList, SymfonyStyle $io,OutputInterface $output)
    {
        $this->subscriber->setOutputInterface($output);

        foreach(self::KEYS as $key) {
            if(!file_exists($fileList[$key])) {
                throw new AppCommandException(AppCommandException::MISSING_FILE,
                    [$fileList[$key],$masterFile]);
            }
        }

//        $personYaml = yaml_parse_file($fileList['persons']);
//        $personSectionCount = count($personYaml);
//
//        $teamYaml = yaml_parse_file($fileList['teams']);
//        $teamSectionCount = count($teamYaml);
//
        $eventYaml = yaml_parse_file($fileList['events']);
        $eventSectionCount = 0;
        foreach($eventYaml as $eventSections) {
            $eventSectionCount+=count($eventSections);
        }

        $eventTeams = yaml_parse_file($fileList['event-teams']);
        $eventTeamSectionCount = 0;
        foreach($eventTeams as $eventTeamsSections) {
            $eventTeamSectionCount+=count($eventTeamsSections);
        }

        $sectionCount =  $eventSectionCount + $eventTeamSectionCount;

        $event = new ProcessEvent(new ProcessStatus(ProcessStatus::COMMENCE, $sectionCount));

        $this->dispatcher->dispatch('process.update',$event);

        return $sectionCount;
    }

    /**
     * @param SymfonyStyle $io
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws AppCommandException
     * @throws \Doctrine\DBAL\DBALException
     */
    private function processFiles(SymfonyStyle $io, InputInterface $input,OutputInterface $output)
    {

        $masterFile = $input->getArgument('masterFile');
        $fileList = yaml_parse_file($masterFile);

        $filesToParse = [];
        foreach($fileList as $key=>$file) {
            if(!in_array($key,self::KEYS)) {
              throw new AppCommandException(AppCommandException::FOUND_BUT_EXPECTED,
                  [$key,self::KEYS,$masterFile]);
            }
            $filesToParse[$key]=$file;
        }

        $diff = array_diff(self::KEYS, array_keys($fileList));
        if(count($diff)>0) {
            throw new AppCommandException(AppCommandException::MISSING_KEYS,[$diff]);
        }
        $sections = $this->initializeOutputFeedback($masterFile,$fileList,$io,$output);

//        $this->setup['base-to-team']->parseModels($filesToParse['models']);
//
//        $this->setup['base-to-team']->parseDomains($filesToParse['domains']);
//
//        $this->setup['base-to-team']->parseValues($filesToParse['values']);
//
//        $this->setup['base-to-team']->parseModelValues($filesToParse['model-values']);
//
//        $this->setup['base-to-team']->parsePersons($filesToParse['persons']);
//
//        $this->setup['base-to-team']->parseTeams($filesToParse['teams']);
//
//        /** @var EntityManagerInterface $em */
//        $conn = $this->entityManager->getConnection();
//
//        $now = new \DateTime('now');
//
//        $io->note('CALL build_setup() @'.$now->format("Y-m-d H:i:s"));
//
//        $conn->query('CALL build_setup()');
//
        $next = new \DateTime('now');

        $io->note('Ended build_setup()@'.$next->format("Y-m-d H:i:s"));

//        $this->setup['event']->parseEvents($filesToParse['events']);

        $this->setup['event-team']->parseEventsTeams($filesToParse['event-teams']);

        $event = new ProcessEvent(new ProcessStatus(ProcessStatus::COMPLETE,$sections));

        $this->dispatcher->dispatch('process.update',$event);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,$output);
        //try{
            $this->processFiles($io, $input, $output);
        //} catch (AppCommandException $e) {
        //    $io->writeln($e->getMessage());
        //}
    }
}
