# Yii Short

Sets of helper functions for rapid development of Yii 3 applications.

[![Latest Stable Version](https://poser.pugx.org/xepozz/yii-short/v/stable.svg)](https://packagist.org/packages/xepozz/yii-short)
[![Total Downloads](https://poser.pugx.org/xepozz/yii-short/downloads.svg)](https://packagist.org/packages/xepozz/yii-short)
[![phpunit](https://github.com/xepozz/yii-short/workflows/PHPUnit/badge.svg)](https://github.com/xepozz/yii-short/actions)
[![codecov](https://codecov.io/gh/xepozz/yii-short/branch/master/graph/badge.svg?token=UREXAOUHTJ)](https://codecov.io/gh/xepozz/yii-short)
[![type-coverage](https://shepherd.dev/github/xepozz/yii-short/coverage.svg)](https://shepherd.dev/github/xepozz/yii-short)

## Installation

```bash
composer require xepozz/yii-short
```

## Shortcuts

### `container(string $id, bool $optional = false): mixed`

- `$id` is a container id
- `$optional` is a flag to return `null` if the `$id` is not found in the container

```php
container(\App\MyService::class); // => \App\MyService instance
container('not-exist'); // => throws \Psr\Container\NotFoundExceptionInterface
container('not-exist', true); // => null
```


### `route(string $name, array $params = [], array $query = []): string`

- `$name` is a route name
- `$params` is a route params
- `$query` is a query params

```php
route('site/index'); // => '/index'
route('user/view', ['id' => 1]); // => '/user/1'
route('site/index', [], ['page' => 2]); // => '/index?page=2'
```

### `view(string $view, array $params = [], null|string|object $controller = null): \Yiisoft\DataResponse\DataResponse`

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

### `response(int|null|string|array|StreamInterface $body, int $code = 200, string $status = 'OK', array $headers = []): \Psr\Http\Message\ResponseInterface`

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

### `redirect(string $name, array $parameters = [], array $query = [], int $code = Status::TEMPORARY_REDIRECT, bool $absolute = false): \Psr\Http\Message\ResponseInterface`

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

### `alias(string $path): string`

- `$path` is an alias name

```php
alias('@runtime'); // => '/path/to/runtime'
```

### `aliases(string ...$paths): array`

- `$paths` is alias names

```php
aliases('@runtime', '@webroot'); // => ['/path/to/runtime', '/path/to/webroot']
```

### `t(string $message, array $params = [], string $category = 'app', string $language = null): string`

- `$message` is a translation message
- `$params` is a translation params
- `$category` is a translation category
- `$language` is a translation language

```php
t('main.hello'); // => 'Hello world'
t('error.message', ['message' => 'Something went wrong']); // => 'Error: "Something went wrong".'
t('error.message', ['message' => 'Something went wrong'], 'modules'); // => 'Error from a module: "Something went wrong".'
t('error.message', ['message' => 'Something went wrong'], 'modules', 'ru'); // => 'Ошибка из модуля: "Something went wrong".'
```