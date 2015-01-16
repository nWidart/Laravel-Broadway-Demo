<?php namespace Asgard\Tests\Parts\Domain\Entity;

use Modules\Parts\Entities\PartId;
use PHPUnit_Framework_TestCase;
use Rhumsaa\Uuid\Uuid;

class PartIdTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_require_instance_of_uuid()
    {
        $this->setExpectedException('Exception');

        $id = new PartId();
    }

    /** @test */
    public function should_create_new_id()
    {
        $id = new PartId(Uuid::uuid4());

        $this->assertInstanceOf('Modules\Parts\Entities\PartId', $id);
    }

    /** @test */
    public function should_generate_new_id()
    {
        $id = PartId::generate();

        $this->assertInstanceOf('Modules\Parts\Entities\PartId', $id);
    }

    /** @test */
    public function should_create_id_from_string()
    {
        $id = PartId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');

        $this->assertInstanceOf('Modules\Parts\Entities\PartId', $id);
    }

    /** @test */
    public function should_test_equality()
    {
        $one   = PartId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');
        $two   = PartId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');
        $three = PartId::generate();

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /** @test */
    public function should_return_id_as_string()
    {
        $id = PartId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');

        $this->assertEquals('d16f9fe7-e947-460e-99f6-2d64d65f46bc', $id->toString());
        $this->assertEquals('d16f9fe7-e947-460e-99f6-2d64d65f46bc', (string) $id);
    }
}
