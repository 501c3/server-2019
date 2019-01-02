<?php
/**
 * Copyright (c) 2018. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 12/21/18
 * Time: 4:30 PM
 */

namespace App\Command;


use Symfony\Component\Console\Output\OutputInterface;

class WorkingThread extends \Thread
{
   /** @var float */
   private $start;

   private $count = 0;

   private $rotator = ['\\','|','/'];

   /** @var OutputInterface */
   private $output;

   public function __construct(OutputInterface $output)
   {
       $this->start = microtime(true);
       $this->output = $output;
   }

   public function run(){
       $this->synchronized(function(\Thread $thread){
          while(!$thread->done){
              $time = microtime(true)-$this->start;
              $this->count++;
              $position = $this->count % 3;
              sleep(3);
              $this->output->write($this->rotator[$position]." Running for $time seconds.");
          }
          $thread->wait();
       },$this);
   }






}