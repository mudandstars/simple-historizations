[![Latest Version on Packagist](https://img.shields.io/packagist/v/mudandstars/simple-historizations.svg?style=flat-square)](https://packagist.org/packages/mudandstars/simple-historizations)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/mudandstars/simple-historizations.svg?style=flat-square)](https://packagist.org/packages/mudandstars/simple-historizations)
[![Tests](https://github.com/mudandstars/simple-historizations/actions/workflows/tests.yml/badge.svg)](https://github.com/mudandstars/simple-historizations/actions/workflows/tests.yml)

## Disclaimer
This package was meant to be a practice effort, growing out of the need of an own project.
If you require more complex functionality, I recommend you use the popular [Laravel Auditing](https://laravel-auditing.com/https://www.youtube.com/watch?v=R2d2spnXyLA).
# simple-historizations

When you want to historize changes to a column in your model, this package is for you.

## How it works

You have a model 'MyModel' and want to historize changes to the 'column_to_historize' column.

So you add the trait to the model and specify which columns to historize, giving each historization model a proper name.

Then, you run the command and migrate your database and voilá, whenever the specified column changes, a new instance of the historization model will be created.

## Installation & Setup

1. Install the package into your project via composer like so:

```
composer require mudandstars/simple-historizations
```

2. Use the trait in the models you want to historize on change:

```php
class MyModel extends Model
{
    use SimpleHistorizations;

    ...
}
```

3. Specify the name of your Historization models and the column it should historize:

```php
class MyModel extends Model
{
    use SimpleHistorizations;

    protected $historize = [
        'HistorizationModelName' => 'column_to_historize',
        'CostHistorization' => 'cost',
    ];

    ...
}
```

4. Run the artisan command to make the required models and migrations and migrate your database:

```
sail artisan make-historization-files
sail artisan migrate
```

---

You are all set up now. On subsequent updates to the model, an instance of the specified HistorizationModel will be created when the column_to_historize changes.
