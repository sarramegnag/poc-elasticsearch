<?php

namespace App\Factory;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Person>
 *
 * @method        Person|Proxy create(array|callable $attributes = [])
 * @method static Person|Proxy createOne(array $attributes = [])
 * @method static Person|Proxy find(object|array|mixed $criteria)
 * @method static Person|Proxy findOrCreate(array $attributes)
 * @method static Person|Proxy first(string $sortedField = 'id')
 * @method static Person|Proxy last(string $sortedField = 'id')
 * @method static Person|Proxy random(array $attributes = [])
 * @method static Person|Proxy randomOrCreate(array $attributes = [])
 * @method static PersonRepository|RepositoryProxy repository()
 * @method static Person[]|Proxy[] all()
 * @method static Person[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Person[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Person[]|Proxy[] findBy(array $attributes)
 * @method static Person[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Person[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class PersonFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->name(),
        ];
    }

    protected static function getClass(): string
    {
        return Person::class;
    }
}
