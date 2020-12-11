<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired tickets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app('App\Http\Controllers\InquiryController')->checkTicketStatus();
    }
}
