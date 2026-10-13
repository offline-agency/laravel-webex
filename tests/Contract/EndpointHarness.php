<?php

namespace Offlineagency\LaravelWebex\Tests\Contract;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\LaravelWebex;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Finder\Finder;

/**
 * Calls every public endpoint method of every API class with placeholder arguments and records the requests,
 * normalised like the operations index (path parameters as {}).
 */
final class EndpointHarness
{
    /**
     * Methods of AbstractApi and helpers that are not endpoints.
     */
    private const NOT_ENDPOINTS = ['__construct', 'data', 'value', 'cursor'];

    /**
     * Arguments that must hold one of the values the API accepts (by parameter position).
     *
     * @var array<string, array<int, string>>
     */
    private const ARGUMENTS = [
        'Meetings\\Meeting::batchUpdateRegistrantsStatus' => [1 => 'approve'],
    ];

    /**
     * @return list<array{class: class-string<AbstractApi>, method: string}>
     */
    public static function endpoints(): array
    {
        $endpoints = [];
        foreach ((new Finder)->files()->in(__DIR__.'/../../src/Api')->name('*.php')->sortByName() as $file) {
            $class = 'Offlineagency\\LaravelWebex\\Api\\'.str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
            if (! is_subclass_of($class, AbstractApi::class) || (new ReflectionClass($class))->isAbstract()) {
                continue;
            }
            foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() === $class && ! in_array($method->getName(), self::NOT_ENDPOINTS, true)) {
                    $endpoints[] = ['class' => $class, 'method' => $method->getName()];
                }
            }
        }

        return $endpoints;
    }

    /**
     * The requests one endpoint method sends, as "METHOD /path" with the placeholder arguments replaced by {}.
     *
     * @param  class-string<AbstractApi>  $class
     * @return list<string>
     */
    public static function requestsOf(string $class, string $method): array
    {
        self::call($class, $method);

        return self::recordedRequests();
    }

    /**
     * Call one endpoint method with placeholder arguments and return its result.
     *
     * @param  class-string<AbstractApi>  $class
     */
    public static function call(string $class, string $method): mixed
    {
        self::$placeholders = [];
        $arguments = [];
        $fixed = self::ARGUMENTS[str_replace('Offlineagency\\LaravelWebex\\Api\\', '', $class).'::'.$method] ?? [];
        foreach ((new ReflectionMethod($class, $method))->getParameters() as $index => $parameter) {
            if (isset($fixed[$index])) {
                $arguments[] = $fixed[$index];

                continue;
            }
            $type = $parameter->getType();
            $name = $type instanceof ReflectionNamedType ? $type->getName() : 'string';
            $arguments[] = match ($name) {
                'array' => [],
                'int' => 7,
                'bool' => true,
                default => self::$placeholders[] = 'arg'.$index.'x',
            };
        }

        return (new $class(new LaravelWebex))->{$method}(...$arguments);
    }

    /**
     * @var list<string>
     */
    private static array $placeholders = [];

    /**
     * @return list<string>
     */
    private static function recordedRequests(): array
    {
        $placeholders = self::$placeholders;
        $requests = [];
        foreach (Http::recorded() as [$request]) {
            $path = (string) parse_url($request->url(), PHP_URL_PATH);
            $path = (string) preg_replace('#^/v1#', '', $path);
            $segments = array_map(fn (string $segment) => in_array(rawurldecode($segment), $placeholders, true) ? '{}' : $segment, explode('/', $path));
            $requests[] = $request->method().' '.rtrim(implode('/', $segments), '/');
        }

        return $requests;
    }

    /**
     * @return list<string>
     */
    public static function operations(): array
    {
        $index = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/webex-operations.json'), true);

        return is_array($index) && is_array($index['operations'] ?? null) ? array_values(array_filter($index['operations'], 'is_string')) : [];
    }
}
