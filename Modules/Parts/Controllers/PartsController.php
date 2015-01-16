<?php namespace Modules\Parts\Controllers;

use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\Repositories\PartRepository;

class PartsController extends \BaseController
{
    public function manufacture()
    {
        // Setup command handler
        //$commandHandler = new PartCommandHandler(new PartRepository());
    }
}
