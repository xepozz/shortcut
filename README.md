# Shortcut

Sets of helper functions for rapid development of Yii 3 applications.

[![Latest Stable Version](https://poser.pugx.org/xepozz/shortcut/v/stable.svg)](https://packagist.org/packages/xepozz/shortcut)
[![Total Downloads](https://poser.pugx.org/xepozz/shortcut/downloads.svg)](https://packagist.org/packages/xepozz/shortcut)
[![phpunit](https://github.com/xepozz/shortcut/workflows/PHPUnit/badge.svg)](https://github.com/xepozz/shortcut/actions)
[![codecov](https://codecov.io/gh/xepozz/shortcut/branch/master/graph/badge.svg?token=UREXAOUHTJ)](https://codecov.io/gh/xepozz/shortcut)
[![type-coverage](https://shepherd.dev/github/xepozz/shortcut/coverage.svg)](https://shepherd.dev/github/xepozz/shortcut)

## About

The library provides a set of helper functions for rapid development of Yii 3 applications.


## Installation

```bash
composer req xepozz/shortcut
```

## Shortcuts

### Table of contents

- [container](#item-container)
  - Accessing the PSR-11 container 
- [route](#item-route)
  - Generating a route URL 
- [view](#item-view)
  - Rendering a view file to a response object
- [response](#item-response)
  - Creating a response object
- [redirect](#item-redirect)
  - Creating a redirect response object 
- [alias](#item-alias)
  - Getting an alias 
- [aliases](#item-aliases)
  - Getting multiple aliases at once 
- [translate](#item-translate)
  - Translating a message 
- [validate](#item-validate)
  - Validating a data 
- [log](#item-log)
  - Logging a message with PSR-3 logger 
- [cache](#item-cache)
  - Accessing the PSR-6 cache 

### Functions
<a id="item-container"></a>
#### `container(string $id, bool $optional = false): mixed`

- `$id` is a container id
- `$optional` is a flag to return `null` if the `$id` is not found in the container

```php
container(\App\MyService::class); // => \App\MyService instance
container('not-exist'); // => throws \Psr\Container\NotFoundExceptionInterface
container('not-exist', true); // => null
```

<a id="item-route"></a>
#### `route(string $name, array $params = [], array $query = []): string`

- `$name` is a route name
- `$params` is a route params
- `$query` is a query params

```php
route('site/index'); // => '/index'
route('user/view', ['id' => 1]); // => '/user/1'
route('site/index', [], ['page' => 2]); // => '/index?page=2'
```

<a id="item-view"></a>
#### `view(string $view, array $params = [], null|string|object $controller = null): \Yiisoft\DataResponse\DataResponse`

- `$view` is a view name
- `$params` is a view params
- `$controller` is a controller instance or a path to views directory. Used to bind views to the specific directory.

```php
view('site/index'); // => A response object with content of file '/views/site/index.php'
view('site/index', ['page' => 2]); // => A response object with content of file '/views/site/index.php' and params ['page' => 2]
view('index', ['page' => 2], new MyController()); // => A response object with content of file '/views/my/index.php' and params ['page' => 2]
view('index', ['user' => $user], 'module/user'); // => A response object with content of file '/views/module/user/index.php' and params ['user' => $user]

class SiteController 
{
    public function actionIndex()
    {
        return view('index', [], $this);  // => A response object with content of file '/views/site/index.php'
    }
}
```

<a id="item-response"></a>
#### `response(int|null|string|array|StreamInterface $body = null, int $code = 200, string $status = 'OK', array $headers = []): \Psr\Http\Message\ResponseInterface`

- `$body` is a response body
- `$code` is a response code
- `$status` is a response status
- `$headers` is a response headers

```php
response('Hello world'); // => A response object with body 'Hello world'
response('Hello world', 201); // => A response object with body 'Hello world' and code 201
response('Hello world', 201, 'Created'); // => A response object with body 'Hello world', code 201 and status 'Created'
response('Hello world', 201, 'Created', ['X-My-Header' => 'My value']); // => A response object with body 'Hello world', code 201, status 'Created' and header 'X-My-Header' with value 'My value'

response(['message' => 'Hello world']); // => A response object with body '{"message":"Hello world"}' and header 'Content-Type' with value 'application/json'
```

<a id="item-redirect"></a>
#### `redirect(string $name, array $parameters = [], array $query = [], int $code = Status::TEMPORARY_REDIRECT, bool $absolute = false): \Psr\Http\Message\ResponseInterface`

- `$name` is a route name or an absolute url if `$absolute` is `true`
- `$parameters` is a route parameters. Used only if `$absolute` is `false`
- `$query` is a query parameters
- `$code` is a response code
- `$absolute` is a flag to generate absolute url, default is `false`

```php
// Route name 'site/index' is bound to '/index'
redirect('site/index'); // => A response object with code 307 and header 'Location' with value '/index'
redirect('site/index', ['page' => 2]); // => A response object with code 307 and header 'Location' with value '/index/2'
redirect('site/index', [], ['page' => 2]); // => A response object with code 307 and header 'Location' with value '/index?page=2'
redirect('site/index', [], ['page' => 2], Status::PERMANENT_REDIRECT); // => A response object with code 308 and header 'Location' with value '/index?page=2'

// Generating absolute url
redirect('/path/to/redirect', [], ['page' => 2], Status::PERMANENT_REDIRECT, true); // => A response object with code 308 and header 'Location' with value 'http://localhost/path/to/redirect?page=2'
```

<a id="item-alias"></a>
#### `alias(string $path): string`

- `$path` is an alias name

```php
alias('@runtime'); // => '/path/to/runtime'
```

<a id="item-aliases"></a>
#### `aliases(string ...$paths): array`

- `$paths` is alias names

```php
aliases('@runtime', '@webroot'); // => ['/path/to/runtime', '/path/to/webroot']
```

<a id="item-translate"></a>
#### `translate(string $message, array $params = [], string $category = 'app', string $language = null): string`

- `$message` is a translation message
- `$params` is a translation params
- `$category` is a translation category
- `$language` is a translation language

```php
translate('main.hello'); // => 'Hello world'
translate('error.message', ['message' => 'Something went wrong']); // => 'Error: "Something went wrong".'
translate('error.message', ['message' => 'Something went wrong'], 'modules'); // => 'Error from a module: "Something went wrong".'
translate('error.message', ['message' => 'Something went wrong'], 'modules', 'ru'); // => 'Ошибка из модуля: "Something went wrong".'
```

<a id="item-validate"></a>
#### `validate(mixed $data, callable|iterable|object|string|null $rules = null, ?ValidationContext $context = null): Result`

- `$data` is a data to validate
- `$rules` is a validation rules
- `$context` is a validation context

```php
validate(
    ['name' => 'John'],
    ['name' => [new Required()]],
);
```

See more about validator rules in [yiisoft/validator](https://github.com/yiisoft/validator)

<a id="item-log"></a>
#### `log_message(string $level, string|stringable $message, array $context = []): void`

- `$level` is a log level. Available levels: `emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info`, `debug`.
  - You can use `\Psr\Log\LogLevel` constants: 
    - `\Psr\Log\LogLevel::EMERGENCY`
    - `\Psr\Log\LogLevel::ALERT`
    - `\Psr\Log\LogLevel::CRITICAL`
    - `\Psr\Log\LogLevel::ERROR`
    - `\Psr\Log\LogLevel::WARNING`
    - `\Psr\Log\LogLevel::NOTICE`
    - `\Psr\Log\LogLevel::INFO`
    - `\Psr\Log\LogLevel::DEBUG`.
- `$message` is a log message
- `$context` is a log context

Also, you can use already level-specific functions:
- `log_emergency(string|Stringable $message, array $context = []): void`
- `log_alert(string|Stringable $message, array $context = []): void`
- `log_critical(string|Stringable $message, array $context = []): void`
- `log_error(string|Stringable $message, array $context = []): void`
- `log_warning(string|Stringable $message, array $context = []): void`
- `log_notice(string|Stringable $message, array $context = []): void`
- `log_info(string|Stringable $message, array $context = []): void`
- `log_debug(string|Stringable $message, array $context = []): void`

```php
log_message('info', 'Some info message');
log_message('error', 'Could not authenticate user with ID {user_id}', ['user_id' => $userId]);

log_info('Info message');
log_error('Error message');
log_warning('Warning message');
log_notice('Notice message');
log_debug('Debug message');
log_critical('Critical message');
log_alert('Alert message');
log_emergency('Emergency message');
```

<a id="item-cache"></a>
#### `cache(string|int|Stringable|array $key, mixed $value = null, int|DateInterval|null $ttl = null): mixed`

- `$key` is a cache key
- `$value` is a cache value
- `$ttl` is a cache TTL

```php
cache('key', 'value'); // returns "value" and sets cache with key "key" if it does not exist

cache('key', 'value', 3600); // sets cache with key "key" and value "value" for 1 hour
cache('key', 'value', new DateInterval('PT1H')); // also TTL can be an instance of DateInterval

cache('key', fn () => 'value'); // $value can be a closure. It will be executed only if cache with key "key" does not exist

cache(new StringableClass('key'), fn () => 'value'); // $key can be an instance of Stringable
cache(12345, fn () => 'value'); // $key can be an integer
cache(['key' => 'value', '!@#$%^&*()_+`' => '_)(*&^%$#@!~`'], fn () => 'value'); // $key can be an array. It will be serialized to a JSON and the following characters will be replaced to "_": {}()/\@:
```

## Looking for more modules?

- [Unique ID](https://github.com/xepozz/unique-id) - Allows you to track the unique user in the application.
- [Request ID](https://github.com/xepozz/request-id) - A simple library to generate both unique request and response IDs for tracing purposes.
- [AB](https://github.com/xepozz/ab) - A simple library to enable A/B testing based on a set of rules.
- [Feature Flag](https://github.com/xepozz/feature-flag) - A simple library to enable/disable features based on a set of rules.
