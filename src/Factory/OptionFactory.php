<?php

namespace App\Factory;

use App\Entity\Option;
use App\Repository\OptionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Option>
 *
 * @method        Option|Proxy                     create(array|callable $attributes = [])
 * @method static Option|Proxy                     createOne(array $attributes = [])
 * @method static Option|Proxy                     find(object|array|mixed $criteria)
 * @method static Option|Proxy                     findOrCreate(array $attributes)
 * @method static Option|Proxy                     first(string $sortedField = 'id')
 * @method static Option|Proxy                     last(string $sortedField = 'id')
 * @method static Option|Proxy                     random(array $attributes = [])
 * @method static Option|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OptionRepository|RepositoryProxy repository()
 * @method static Option[]|Proxy[]                 all()
 * @method static Option[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Option[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Option[]|Proxy[]                 findBy(array $attributes)
 * @method static Option[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Option[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OptionFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'isCorrect' => self::faker()->boolean(),
            'question' => QuestionFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Option $option): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Option::class;
    }
}
