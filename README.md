objectives:

## Requirements:
1. use the trait in the model
2. define the desired historizations in a public $historize = [] array

## Features
1. Upon calling a command, creates all the necessary migrations for the historization of a model
2. Upon updating a model, when the columns to be historized are updated, a corresponding historization instance is created
