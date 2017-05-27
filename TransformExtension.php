<?php
namespace Gone\Twig;

use Camel\CaseTransformer;
use Camel\Format;

class TransformExtension extends \Twig_Extension
{
    private $transformers = [
        'Camel',
        'ScreamingSnake',
        'Snake',
        'Spinal',
        'Studly',
    ];

    public function getFilters()
    {
        $filters = [];
        foreach ($this->transformers as $fromTransformer) {
            foreach ($this->transformers as $toTransformer) {
                $name = 'transform_' . strtolower($fromTransformer) . "_to_" . strtolower($toTransformer);
                $filters[$name] =
                    new \Twig_SimpleFilter($name, [$this, 'transform', $fromTransformer, $toTransformer]);
            }
        }
        return $filters;
    }

    public function transform($string, $from, $to)
    {
        $fromTransformer = $this->getTransformer($from);
        $toTransformer = $this->getTransformer($to);

        $transformer = new CaseTransformer($fromTransformer, $toTransformer);
        return $transformer->transform($string);
    }

    protected function getTransformer($name)
    {
        switch (strtolower($name)) {
            case 'camel':
            case 'camelcase':
                return new Format\CamelCase();

            case 'screaming':
            case 'screamingsnakecase':
                return new Format\ScreamingSnakeCase();

            case 'snake':
            case 'snakecase':
                return new Format\SnakeCase();

            case 'spinal':
            case 'spinalcase':
                return new Format\SpinalCase();

            case 'studly':
            case 'studlycaps':
                return new Format\StudlyCaps();

            default:
                throw new TransformExtensionException("Unknown transformer: \"{$name}\".");
        }
    }


    public function getName()
    {
        return 'transform_extension';
    }
}
