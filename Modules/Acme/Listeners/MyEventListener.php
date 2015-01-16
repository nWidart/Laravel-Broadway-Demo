<?php namespace Modules\Acme\Listeners;

use Broadway\Domain\DomainMessageInterface;
use Broadway\EventHandling\EventListenerInterface;

class MyEventListener implements EventListenerInterface
{
    /**
     * @param DomainMessageInterface $domainMessage
     */
    public function handle(DomainMessageInterface $domainMessage)
    {
        dd('get a domain message', $domainMessage);
    }
}
