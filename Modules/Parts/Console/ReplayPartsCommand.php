<?php namespace Modules\Parts\Console;

use Illuminate\Console\Command;

class ReplayPartsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:parts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the parts';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('rebuilding stuff...');
    }
}
