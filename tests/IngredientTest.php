<?php

class IngredientTest extends \PHPUnit_Framework_TestCase
{
    public function testIngredient()
    {
        $jamon = new Ingredient();
        $jamon->setPrice(1);
        $this->assertInstanceOf('IngredientIface', $jamon);
        $this->assertEquals(1, $jamon->getPrice());
    }
}