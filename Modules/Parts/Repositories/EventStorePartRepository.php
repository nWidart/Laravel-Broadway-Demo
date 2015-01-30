<?php namespace Modules\Parts\Repositories;

use Broadway\Domain\DomainEventStreamInterface;
use Broadway\Repository\RepositoryInterface;

interface EventStorePartRepository extends RepositoryInterface
{
    /**
     * @param $id
     * @param DomainEventStreamInterface $eventStream
     * @return mixed
     */
    public function append($id, DomainEventStreamInterface $eventStream);

    /**
     * @return array
     */
    public function getStreamIds();
}
