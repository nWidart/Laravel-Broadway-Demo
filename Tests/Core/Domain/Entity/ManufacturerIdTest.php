<?php namespace Asgard\Tests\Core\Domain\Entity;

use Modules\Parts\Entities\ManufacturerId;
use Rhumsaa\Uuid\Uuid;

class ManufacturerIdTest extends \PHPUnit_Framework_TestCase
{
    public function should_require_instance_of_uuid()
    {
        $this->setExpectedException('Exception');

        $id = new ManufacturerId();
    }

    /** @test */
    public function should_create_new_id()
    {
        $id = new ManufacturerId(Uuid::uuid4());

        $this->assertInstanceOf('Modules\Parts\Entities\ManufacturerId', $id);
    }

    /** @test */
    public function should_generate_new_id()
    {
        $id = ManufacturerId::generate();

        $this->assertInstanceOf('Modules\Parts\Entities\ManufacturerId', $id);
    }

    /** @test */
    public function should_create_id_from_string()
    {
        $id = ManufacturerId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');

        $this->assertInstanceOf('Modules\Parts\Entities\ManufacturerId', $id);
    }

    /** @test */
    public function should_test_equality()
    {
        $one   = ManufacturerId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');
        $two   = ManufacturerId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');
        $three = ManufacturerId::generate();

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /** @test */
    public function should_return_id_as_string()
    {
        $id = ManufacturerId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');

        $this->assertEquals('d16f9fe7-e947-460e-99f6-2d64d65f46bc', $id->toString());
        $this->assertEquals('d16f9fe7-e947-460e-99f6-2d64d65f46bc', (string) $id);
    }
}
