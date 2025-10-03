<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class NewModuleScaffold
{
    protected string $moduleName;
    protected string $modelName;
    protected string $tableName;
    protected string $livewireName;
    protected string $livewireComponents;
    protected Filesystem $files;
    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
        $this->modelName = Str::studly($moduleName);
        $this->tableName = Str::snake(Str::pluralStudly($moduleName));
        $this->files = new Filesystem();
    }

    public function createModel()
    {
        $modelScaffold = new ModelScaffold($this->modelName);
        $modelScaffold->createModel();
    }

    public function createIndexLivewireComponent()
    {
        $indexScaffold = new IndexLivewireScaffold($this->modelName);
        $indexScaffold->createIndex();
    }

    public function createCreateLivewireComponent()
    {
        $createLivewireComponentScaffold = new CreateLivewireScaffold($this->modelName);
        $createLivewireComponentScaffold->createCreateLivewireComponent();
    }

    public function createEditLivewireComponent()
    {
        $editLivewireComponentScaffold = new EditLivewireScaffold($this->modelName);
        $editLivewireComponentScaffold->createEditLivewireComponent();
    }

    public function createShowLivewireComponent()
    {
        $showLivewireComponentScaffold = new ShowLivewireScaffold($this->modelName);
        $showLivewireComponentScaffold->createShowLivewireComponent();
    }
}
