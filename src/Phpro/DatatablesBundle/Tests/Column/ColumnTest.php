<?php
namespace Efashion\AppBundle\Datatables\Tests\Column;

use Phpro\DatatablesBundle\Column\Column;
use Phpro\DatatablesBundle\Column\ColumnInterface;

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testCanInstantiate()
    {
        $column = new Column('test', []);
        $this->assertInstanceOf(ColumnInterface::class, $column);
    }
}
