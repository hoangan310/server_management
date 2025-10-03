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

    public function createIndexComponent()
    {
        $indexScaffold = new IndexLivewireScaffold($this->modelName);
        $indexScaffold->createIndex();
        $indexScaffold->createIndexBlade();
    }

    public function createCreateComponent()
    {
        $createLivewireComponentScaffold = new CreateLivewireScaffold($this->modelName);
        $createLivewireComponentScaffold->createCreateLivewireComponent();
        $createLivewireComponentScaffold->createCreateBlade();
    }

    public function createEditComponent()
    {
        $editLivewireComponentScaffold = new EditLivewireScaffold($this->modelName);
        $editLivewireComponentScaffold->createEditLivewireComponent();
        $editLivewireComponentScaffold->createEditBlade();
    }

    public function createShowComponent()
    {
        $showLivewireComponentScaffold = new ViewLivewireScaffold($this->modelName);
        $showLivewireComponentScaffold->createViewLivewireComponent();
        $showLivewireComponentScaffold->createViewBlade();
    }
}
