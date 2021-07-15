<?php

namespace App\Jobs;

use App\Events\CompetitionManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompetitionBrain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $competitionId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($competitionId,$message)
    {
        $this->message = $message;
        $this->competitionId = $competitionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        broadcast(new CompetitionManager($this->competitionId,'json','Delayed job'.$this->message));
    }
}
