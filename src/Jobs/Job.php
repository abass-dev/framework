<?php

namespace Bow\Jobs;

class Job
{
    /**
     * @var string
     */
    protected $queue = 'default';

    /**
     * The job handle
     */
    public function handle()
    {
        //
    }
    
    private function dispatch()
    {
        
    }
}
