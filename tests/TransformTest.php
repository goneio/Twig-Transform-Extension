<?php
namespace MatthewBaggett\Twig\Tests;

use MatthewBaggett\Twig\TransformExtension;
use MatthewBaggett\Twig\TransformExtensionException;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase
{
    /** @var Environment */
    private $twig;
    /** @var LoaderInterface */
    private $loader;

    public function setUp() : void
    {
        parent::setUp();
        $this->loader = new ArrayLoader([]);
        $this->twig = new Environment($this->loader);
        $this->twig->addExtension(new TransformExtension);
    }

    public function testNoop()
    {
        $this->twig->setLoader(new ArrayLoader([
            'testNoop'          => "{{ test_phrase }}",
        ]));
        $this->assertEquals("Test Words", $this->twig->render('testNoop', ['test_phrase' => 'Test Words']));
    }

    public function transformationDataProvider()
    {
        return [
            ['transform_camel_to_camel', 'transformedPhrase', 'transformedPhrase'],
            ['transform_camel_to_screamingsnake', 'transformedPhrase', 'TRANSFORMED_PHRASE'],
            ['transform_camel_to_snake', 'transformedPhrase', 'transformed_phrase'],
            ['transform_camel_to_spinal', 'transformedPhrase','transformed-phrase'],
            ['transform_camel_to_studly', 'transformedPhrase', 'TransformedPhrase'],

            ['transform_screamingsnake_to_camel', 'TRANSFORMED_PHRASE', 'transformedPhrase'],
            ['transform_screamingsnake_to_screamingsnake', 'TRANSFORMED_PHRASE', 'TRANSFORMED_PHRASE'],
            ['transform_screamingsnake_to_snake', 'TRANSFORMED_PHRASE', 'transformed_phrase'],
            ['transform_screamingsnake_to_spinal', 'TRANSFORMED_PHRASE', 'transformed-phrase'],
            ['transform_screamingsnake_to_studly', 'TRANSFORMED_PHRASE', 'TransformedPhrase'],

            ['transform_snake_to_camel', 'transformed_phrase', 'transformedPhrase'],
            ['transform_snake_to_screamingsnake', 'transformed_phrase', 'TRANSFORMED_PHRASE'],
            ['transform_snake_to_snake', 'transformed_phrase', 'transformed_phrase'],
            ['transform_snake_to_spinal', 'transformed_phrase', 'transformed-phrase'],
            ['transform_snake_to_studly', 'transformed_phrase', 'TransformedPhrase'],

            ['transform_spinal_to_camel', 'transformed-phrase', 'transformedPhrase'],
            ['transform_spinal_to_screamingsnake', 'transformed-phrase', 'TRANSFORMED_PHRASE'],
            ['transform_spinal_to_snake', 'transformed-phrase', 'transformed_phrase'],
            ['transform_spinal_to_spinal', 'transformed-phrase', 'transformed-phrase'],
            ['transform_spinal_to_studly', 'transformed-phrase', 'TransformedPhrase'],

            ['transform_studly_to_camel', 'TransformedPhrase', 'transformedPhrase'],
            ['transform_studly_to_screamingsnake', 'TransformedPhrase', 'TRANSFORMED_PHRASE'],
            ['transform_studly_to_snake', 'TransformedPhrase', 'transformed_phrase'],
            ['transform_studly_to_spinal', 'TransformedPhrase', 'transformed-phrase'],
            ['transform_studly_to_studly', 'TransformedPhrase', 'TransformedPhrase'],
        ];
    }

    /**
     * @dataProvider transformationDataProvider
     */
    public function testCamelToStudly($transformer, $input, $output)
    {
        $this->twig->setLoader(new ArrayLoader([
            'test' => "{{ input|{$transformer} }}"
        ]));
        $this->assertEquals($output, $this->twig->render('test', ['input' => $input]));
    }

    public function testGetName()
    {
        $transformer = new TransformExtension();
        $this->assertEquals("transform_extension", $transformer->getName());
    }

    public function testInvalidTransformer()
    {
        $this->expectException(SyntaxError::class);
        $this->twig->setLoader(new ArrayLoader([
            'test' => "{{ input|transform_camel_to_notreal }}"
        ]));
        $this->twig->render('test', ['input' => 'test']);
    }

    public function testGetTransformerInvalid()
    {
        $this->expectException(TransformExtensionException::class);
        $this->expectExceptionMessage("Unknown transformer: \"not_a_transformer\".");

        $method = new \ReflectionMethod(TransformExtension::class, 'getTransformer');
        $method->setAccessible(true);
        $method->invoke(new TransformExtension(), 'not_a_transformer');
    }
}
