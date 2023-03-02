[![Latest Version on Packagist](https://img.shields.io/packagist/v/mudandstars/historize-model-changes.svg?style=flat-square)](https://packagist.org/packages/mudandstars/historize-model-changes)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/mudandstars/historize-model-changes.svg?style=flat-square)](https://packagist.org/packages/mudandstars/historize-model-changes)

# historize-model-changes

When you want to historize changes to a column in your model, this package is for you.

## How it works

You have a model 'MyModel' and want to historize changes to the 'column_to_historize' column.

So you add the trait to the model and specify which columns to historize, giving each historization model a proper name.

Then, you run the command and migrate your database and voilá, whenever the specified column changes, a new instance of the historization model will be created.

## Installation & Setup

1. Install the package into your project via composer like so:

```
composer require mudandstars/historize-model-changes
```

2. Use the trait in the models you want to historize on change:

```php
class MyModel extends Model
{
    use HistorizeModelChange;

    ...
}
```

3. Specify the name of your Historization models and the column it should historize:

```php
class MyModel extends Model
{
    use HistorizeModelChange;

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
