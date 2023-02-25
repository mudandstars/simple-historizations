<?php

namespace Mudandstars\HistorizeModelChanges\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Mudandstars\HistorizeModelChanges\Actions\GetCorrespondingMigrationPathAction;
use Mudandstars\HistorizeModelChanges\Services\MigrationColumnsService;

trait HistorizeModelChange
{
    public function update(array $attributes = [], array $options = [])
    {
        foreach (array_keys($this->historize) as $modelName) {
            if (! file_exists(app_path('Models/'.$modelName.'.php'))) {
                continue;
            }

            $this->historizeUpdates($modelName, $attributes);
        }

        return parent::update($attributes, $options);
    }

    private function historizeUpdates(string $modelName, array $attributes): void
    {
        $column = $this->historize[$modelName];

        $model = App::make('App\Models\\'.$modelName);

        if ($this->shouldCreateHistorizationInstance($attributes, $column, $modelName)) {
            $model->create([
                rtrim($this->getTable(), 's').'_id' => $this->id,
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
        $migrationColumns = $migrationColumnService->getArray(GetCorrespondingMigrationPathAction::execute(class_basename($this)));

        $name = $this->historize[$modelName];
        $type = $migrationColumns[$name];

        if ($type === 'date') {
            return $this->__get($column)->notEqualTo(Carbon::createFromDate($attributes[$column]));
        } else {
            return $this->__get($column) != $attributes[$column];
        }
    }
}
