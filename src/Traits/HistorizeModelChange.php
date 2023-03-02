<?php

namespace Mudandstars\SimpleHistorizations\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Mudandstars\SimpleHistorizations\Actions\GetCorrespondingMigrationPathAction;
use Mudandstars\SimpleHistorizations\Services\MigrationColumnsService;

trait HistorizeModelChange
{
    public function update(array $attributes = [], array $options = [])
    {
        foreach (array_keys($this->historize) as $modelName) {
            if (! $this->modelExists($modelName)) {
                continue;
            }

            $this->historizeUpdates($modelName, $attributes);
        }

        return parent::update($attributes, $options);
    }

    private function modelExists(string $modelName): bool
    {
        return file_exists(app_path('Models/'.$modelName.'.php'));
    }

    private function historizeUpdates(string $modelName, array $attributes): void
    {
        $model = App::make('App\Models\\'.$modelName);

        $column = $this->historize[$modelName];

        if ($this->shouldCreateHistorizationInstance($attributes, $column, $modelName)) {
            $foreignIdColumn = rtrim($this->getTable(), 's').'_id';

            $model->create([
                $foreignIdColumn => $this->id,
                'previous_'.$column => $this->__get($column),
                'new_'.$column => $attributes[$column],
            ]);
        }
    }

    private function shouldCreateHistorizationInstance(array $attributes, string $column, string $modelName): bool
    {
        return array_key_exists($column, $attributes)
        && $this->valueChanged($attributes, $column, $modelName);
    }

    private function valueChanged(array $attributes, string $column, string $modelName): bool
    {
        $migrationColumnService = new MigrationColumnsService();

        $migrationPath = GetCorrespondingMigrationPathAction::execute(class_basename($this));

        $migrationColumns = $migrationColumnService->getArray($migrationPath);

        $columnName = $this->historize[$modelName];
        $type = $migrationColumns[$columnName];

        if ($type === 'date') {
            return $this->__get($column)->notEqualTo(Carbon::createFromDate($attributes[$column]));
        } else {
            return $this->__get($column) != $attributes[$column];
        }
    }
}
