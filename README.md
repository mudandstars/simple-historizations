# historize-model-changes

# THIS IS STILL IN BETA.
# DO NOT USE THIS PACKAGE YET.

## Description:
This package can be used to make simple historizations of specific columns of a model.
Using its main command, the historization models and tables are set up and connected to the primary model.
Then, on subsequent updates to the primary model, instances of the historization models specified will be created automatically.

# Installation & Setup
1. Install the package into your project via composer like so:
```
composer require mudandstars/historize-model-changes
```
2. Use the trait in the models you want to historize on change:
CODE: use HistorizeModelChange;
3. Specify the name of your Historization models and the column it should historize:
CODE: protected $historize = [
    'HistorizationModelName' => 'column_to_historize',
    'CostHistorization' => 'cost',
];
4. Run the artisan command to make the required models and migrations:
CODE: sail artisan make-historization-files
---
You are all set up now. On subsequent updates to the model, an instance of the specified HistorizationModel will be created when the column_to_historize changes.
