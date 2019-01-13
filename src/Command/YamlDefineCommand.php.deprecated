<?php

namespace App\Command;

use App\Common\AppParseException;
use App\Common\AppExceptionCodes;
use App\Common\YamlRelations;
use App\Signal\ProcessEvent;
use App\Signal\ProcessStatus;
use App\Signal\ProcessSubscriber;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class YamlDefineCommand extends Command
{
    const
        MASTER_KEYS = ['models', 'domains', 'values', 'persons', 'teams', 'event-values', 'model-events','relations'];

    protected static $defaultName = 'app:yaml:define';

    /** @var YamlRelations */
    private $yamlRelations;

    /** @var EventDispatcher */
    private $dispatcher;

    /** @var ProcessSubscriber */
    private $subscriber;

    /** @var /DateTime */
    private $startTime;

    /** @var  integer*/
    private $totalLines;

    /** @var integer */
    private $progress;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }


    protected function configure()
    {
        $this
            ->setDescription('A utility which transforms yaml specification files prior importing into prior to database import.')
            ->addArgument('master-file', InputArgument::OPTIONAL, 'Master file containing location of spec files.')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
        /** @var EventDispatcherInterface */
        $this->subscriber = new ProcessSubscriber();
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->subscriber);
        $this->yamlRelations = new YamlRelations($this->dispatcher);
    }

    /**
     * @param $file
     * @return int
     * @throws AppParseException
     */
    private function checkSubfilesAndLineCounts($file) : int
    {
        if(!file_exists($file)) {
            throw new AppParseException(AppExceptionCodes::FILE_NOT_FOUND, $file);
        }
        return intval(exec("wc -l '$file'"));
    }

    private function buildStatusObject(int $status, int $progress = 0)
    {
        switch($status){
            case ProcessStatus::COMMENCE:
                $this->startTime=new \DateTime('now');
                return new ProcessStatus(ProcessStatus::COMMENCE, $progress);
            case ProcessStatus::WORKING:
                return new ProcessStatus(ProcessStatus::WORKING, $progress);
            case ProcessStatus::COMPLETE:
                return new ProcessStatus(ProcessStatus::COMPLETE, 100);
        }
        return null;
    }

    private function computeProgress(int $status, int $lineCount)
    {
        switch($status){
            case ProcessStatus::COMMENCE:
                return 0;
            default:
                $this->progress+=$lineCount;
                return (int) $this->progress*100/$this->totalLines;
        }
    }


    private function sendStatus(int $status, int $lineCount)
    {
        $progress = $this->computeProgress($status,$lineCount);
        $obj = $this->buildStatusObject($status, $progress);
        $event = new ProcessEvent($obj);
        $this->dispatcher->dispatch('process.status.update',$event);
    }

    /**
     * @param $masterFile
     * @throws AppParseException
     */
    private function buildPhpStructures(string $masterFile, OutputInterface $output)
    {
        $this->subscriber->setOutputInterface($output);
        try {
           $contents = yaml_parse_file(__DIR__ . '/' . $masterFile);
        } catch(\Exception $exception){
            throw new AppParseException(AppExceptionCodes::FILE_NOT_FOUND,$masterFile);
        }
        $keys = array_keys($contents);
        $missing=array_diff(self::MASTER_KEYS, $keys);
        if(count($missing)) {
            $foundMissing = '['.join(',',$missing).']';
            throw new AppParseException(AppExceptionCodes::FILE_NOT_FOUND,$masterFile,$foundMissing);
        }
        $lineCounts=[];
        foreach(self::MASTER_KEYS as $key) {
            $lineCounts[$key]=$this->checkSubfilesAndLineCounts($contents[$key]) ;
        }
        $totalLines=array_reduce(array_values($lineCounts),function($carry, $value){$carry+=$value; return $carry;});
        $progress = 0;
        $output->write('Declaring model entities');
        $this->sendStatus(ProcessStatus::COMMENCE, $totalLines);
        $this->yamlRelations->declareModels($contents['models']);
        $progress+=$lineCounts['models'];
        $this->sendStatus(ProcessStatus::WORKING,$progress);
        $this->yamlRelations->declareDomains($contents['domains']);
        $progress+=$lineCounts['domains'];
        $this->sendStatus(ProcessStatus::WORKING,$lineCounts['domains']);
        $this->yamlRelations->declareValues($contents['values']);
        $progress+=$lineCounts['values'];
        $this->sendStatus(ProcessStatus::WORKING,$progress);
        $this->yamlRelations->declarePersons($contents['persons']);
        $progress+=$lineCounts['persons'];
        $this->sendStatus(ProcessStatus::WORKING,$progress);
        $this->yamlRelations->declareTeams($contents['teams']);
        $progress+=$lineCounts['teams'];
        $this->sendStatus(ProcessStatus::WORKING,$progress);
        $this->yamlRelations->declareEventValues($contents['event-values']);
        $progress+=$lineCounts['event-values'];
        $this->sendStatus(ProcessStatus::WORKING,$progress);
        $this->yamlRelations->declareEvents($contents['model-events']);
        $progress+=$lineCounts['model-events'];
        $this->sendStatus(ProcessStatus::WORKING,$progress);
        $this->sendStatus(ProcessStatus::COMPLETE,$this->totalLines);

        $this->yamlRelations->declareRelations($contents['relations']);
        $this->sendStatus(ProcessStatus::WORKING,$lineCounts['relations']);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $masterFile = $input->getArgument('master-file');
        if ($masterFile) {
            $io->note('Building error free yaml constructs.');
            $this->buildPhpStructures($masterFile,$output);
        }
    }
}
