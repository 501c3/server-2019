<?php
/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 11/10/18
 * Time: 8:44 PM
 */

namespace App\Signal;


use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProcessSubscriber implements EventSubscriberInterface
{
    /** @var OutputInterface */
    private $cli;

    /** @var \DateTime */
    private $start;

    /** @var ProgressBar */
    private $progressBar;


    private $lastProgress;

    /**
     * @param OutputInterface $output
     */
    public function setOutputInterface(OutputInterface $output)
    {
        $this->cli = $output;
    }

    /**
     * @param $totalLines
     */
    public function progressBarSetup($totalLines)
    {
        $this->progressBar = new ProgressBar($this->cli, $totalLines);
        $this->progressBar->setBarWidth(60);
        $this->progressBar->clear();
    }

    /**
     * @param ProcessEvent $event
     */
    public function onStatusUpdate(ProcessEvent $event)
    {
        $status = $event->getStatus();
        $event->stopPropagation();
        if(!$this->cli) return;
        switch($status->getStatus()){
            case ProcessStatus::COMMENCE:
                $this->start=$status->getTimestamp();
                $date = sprintf($this->start->format('Y-m-d  H:i:s'));
                $this->cli->writeln( "<fg=green>Commencing at $date</>" );
                $this->progressBarSetup( $status->getProgress() );
                $this->lastProgress = 0;
                $this->progressBar->display();
                break;
            case ProcessStatus::WORKING:
                $this->progressBar->setProgress($status->getProgress());
                break;
            case ProcessStatus::COMPLETE:
                $this->progressBar->finish();
                $this->cli->writeln("");
                $timestamp=$status->getTimestamp();
                $date = sprintf($timestamp->format('Y-m-d  H:i:s'));
                $completed=sprintf("<fg=green>Completed at %s</>",$date);
                $this->cli->writeln($completed);
                $duration=$this->start->diff($timestamp);
                $duration = sprintf("<fg=green>Duration : %s hours %s minutes %s seconds </>",
                    $duration->h, $duration->i, $duration->s);
                $this->cli->writeln($duration);
                break;
            case ProcessStatus::ERRORS:

        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [ProcessEvent::NAME => ['onStatusUpdate']];
    }

}