<?php

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;

class YamlFileLoader extends FileLoader
{
    protected $locator;
    private $bokata;
    protected $yamlParser;

    public function __construct(Bokata $bokata, FileLocatorInterface $fileLocator)
    {
        $this->locator = $fileLocator;
        $this->bokata  = $bokata;
    }

    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        $content = $this->loadFile($path);

        if (null === $content) {
            return;
        }

        if (isset($content['basePrice'])) {
            $this->bokata->setBasePrice($content['basePrice']);
        }

        if (isset($content['ingredients'])) {
            foreach ($content['ingredients'] as $item) {
                $ingredient = new Ingredient();
                $ingredient->setName($item['name']);
                $ingredient->setPrice($item['price']);
                $this->bokata->appendIngredient($ingredient);
            }
        }
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    protected function loadFile($file)
    {
        if (!stream_is_local($file)) {
            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
        }

        if (!file_exists($file)) {
            throw new InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        return $this->validate($this->yamlParser->parse(file_get_contents($file)), $file);
    }

    private function validate($content, $file)
    {
        if (null === $content) {
            return $content;
        }

        if (!is_array($content)) {
            throw new InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
        }

        foreach (array_keys($content) as $namespace) {
            if (in_array($namespace, array('ingredients', 'basePrice'))) {
                continue;
            }
        }

        return $content;
    }
}