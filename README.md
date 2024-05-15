# This is simple laravel backend generator with repository and service pattern ( Support Api )


With laravel backend generator, you can easly create backend for laravel


## Installation

You can install the package via composer:

```bash
composer require akmalriyadi/laravel-backend-generator
```

## Usage Custom Command

```php
php artisan make:repositoryakm User
//for create repository

php artisan make:serviceakm User
//for create service

php artisan make:serviceakm User --api
//for create service api
```

## Sample for Controller
You should define your service or repository on __construct, **Only on service api** you must end return with ->toJson()
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\TestingServiceApi;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    protected $testingServiceApi;

    public function __construct(TestingServiceApi $testingServiceApi)
    {
        $this->testingServiceApi = $testingServiceApi;
    }
    public function index(Request $request)
    {
        return $this->testingServiceApi->all($request->all())->toJson();
    }
}

```

## Work with Filter, Pagination, Relation, and Relation Count
### This work on function `all()`, `find()`, `findOrFail()`
#### params function :
| Params       | Type       | Default | Description                                                                                      |
|--------------|------------|---------|--------------------------------------------------------------------------------------------------|
| `request`    | array      | -       | The `$request` parameter should contain `$request->all()`.                                       |
| `itemOptions`| ItemOptions| DEFAULT       | For show option default, with trashed and only trashed data, Use `AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions` with options: `["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]` |
| `withOptions`| Boolean    | false       | Setting this to `true` will return relation references from your repository class `$this->option['with']`. |
|`withCountOption`| Boolean| false| Setting this to `true` will return relation count reference from your repository class `$this->option['withCount']`|
|`filterOption`| Boolean| false| Setting this to `true` for use ScopeFilter on your Model, or you can use my trait `AkmalRiyadi\LaravelBackendGenerator\Traits\BaseScope`|
|`paginationOption`| Boolean| false| set this to `true` for paginate your data, `this only work` when your array `$request` containt `limit` object|

### Sample Usage Params on **Controller**

```php
use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
//this for itemOption ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]

return $this->testingServiceApi->all(
    $request->all(), // !IMPORTANT, this params must like this
    ItemOptions::WITH_TRASHED, // you can change to default if you want show without trashed data
    true, // true with relations
    true, // true with relations count
    true, // true with scopeFilter
    true, // true with paginationOption
    )->toJson();

// or you can define params only you want like this
return $this->testingServiceApi->all($request->all(), paginationOption: true)
```

### Sample Relation 
#### **On Model**
```php
public function user()
{
    return $this->belongsTo(User::class,'user_id','id');
}
```
#### **On Repository Class**
```php
public function __construct(Testing $model)
{
    $this->model = $this->model;
    $this->with = [
        'user'
    ]
}
```

# Support My Work

Thank you for visiting my GitHub repository! Your interest in my work means a lot to me. If you find this project helpful or valuable, please consider supporting its development. 

Creating and maintaining this project requires significant time and effort. Your support will enable me to continue improving and adding new features to this project.

## How You Can Help

1. **Star the Repository**: Show your appreciation by giving this repository a star. It helps increase visibility and encourages more contributors to join.

2. **Share with Others**: If you know someone who might benefit from this project, please share it with them.

3. **Make a Donation**: If you're in a position to contribute financially, any amount would be greatly appreciated. Your donations will directly support the ongoing development and maintenance of this project.

   **Donate via PayPal**: [zainnoeryadie@gmail.com](https://www.paypal.com/paypalme/zainnoeryadie)

4. **Have a Special Request or Need Assistance?**: If you have any specific requests, ideas for improvement, or if you require assistance related to this project, feel free to reach out. You can email me directly at [zainnoeryadie@gmail.com](mailto:zainnoeryadie@gmail.com).

Thank you for your support!


## Credits

- [Zainnoeryadie akmal sobandiar](https://github.com/akmalriyadi)
- [Putu-yaza](https://github.com/yaza-putu)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
