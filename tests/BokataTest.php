<?php
use Symfony\Component\Config\FileLocator;

class BokataTest extends \PHPUnit_Framework_TestCase
{
    public function testBokata()
    {
        $bokata = new Bokata(10);
        $this->assertInstanceOf('IngredientStackIface', $bokata);

        $jamon = $this->getMockBuilder('Ingredient')->disableOriginalConstructor()->getMock();
        $jamon->expects($this->any())->method('getPrice')->will($this->returnValue(1));

        $lomo = $this->getMockBuilder('Ingredient')->disableOriginalConstructor()->getMock();
        $lomo->expects($this->any())->method('getPrice')->will($this->returnValue(2));

        $bokata->appendIngredient($jamon);
        $bokata->appendIngredient($lomo);

        $this->assertInstanceOf('BokataIface', $bokata);
        $this->assertEquals(13, $bokata->getPrice());
    }

    public function testBokataFromYaml()
    {
        $bokata = new Bokata();

        $loader = new YamlFileLoader($bokata, new FileLocator(__DIR__ . '/fixtures'));
        $loader->load('config.yml');

        $this->assertEquals(23, $bokata->getPrice());
    }
}