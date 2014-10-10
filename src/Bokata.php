<?php

class Bokata implements IngredientStackIface, BokataIface
{
    private $ingredients;
    private $basePrice;


    public function __construct($basePrice = null)
    {
        $this->basePrice = $basePrice;
    }

    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;
    }

    public function appendIngredient(\IngredientIface $ingredient)
    {
        $this->ingredients[] = $ingredient;
    }

    public function getPrice()
    {
        $price = 0;
        foreach ($this->ingredients as $ingredient) {
            $price += $ingredient->getPrice();
        }

        return $this->basePrice + $price;
    }
} 