<?php

use Modules\Acme\Commands\ExampleCommandHandler;

class HomeController extends BaseController
{
    /**
     * @var ExampleCommandHandler
     */
    private $exampleCommandHandler;

    public function __construct(ExampleCommandHandler $exampleCommandHandler)
    {
        parent::__construct();

        $this->exampleCommandHandler = $exampleCommandHandler;
    }

    public function showWelcome()
    {
        $this->exampleCommandHandler->testing();
        return View::make('hello');
    }
}
