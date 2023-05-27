# Laravel Sortable Package

## Installation

```
composer require loafer/laravel-sortable
```

## Usage

Just add the trait to your model.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Loafer\Sortable;
}
```

### Configure sort column name

By default, the package will use `sort_order` column to store the sort value.

If you want to use another column, just add a `sortColumn` property to your model.

```php
class Post extends Model
{
    use \Loafer\Sortable;

    public $sortColumn = 'order';
}
```
