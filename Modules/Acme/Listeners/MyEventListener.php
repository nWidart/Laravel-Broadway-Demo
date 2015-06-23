<?php namespace Modules\Acme\Listeners;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListenerInterface;

class MyEventListener implements EventListenerInterface
{
    /**
     * @param DomainMessage $domainMessage
     */
    public function handle(DomainMessage $domainMessage)
    {
        dd('get a domain message', $domainMessage);
    }
}
