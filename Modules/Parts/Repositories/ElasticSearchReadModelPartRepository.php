<?php namespace Modules\Parts\Repositories;

use Broadway\ReadModel\ElasticSearch\ElasticSearchRepository;
use Broadway\Serializer\SerializerInterface;
use Elasticsearch\Client;
use Modules\Parts\Entities\Part;

class ElasticSearchReadModelPartRepository extends ElasticSearchRepository implements ReadModelPartRepository
{
    public function __construct(
        Client $client,
        SerializerInterface $serializer,
        array $notAnalyzedFields = array()
    )
    {
        parent::__construct($client, $serializer, 'parts', Part::class, $notAnalyzedFields);
    }
}
