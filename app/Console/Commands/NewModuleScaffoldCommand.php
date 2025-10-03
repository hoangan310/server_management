<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;
use App\Services\Scaffold\NewModuleScaffold;

class NewModuleScaffoldCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new module';

    protected Filesystem $files;

    protected NewModuleScaffold $newModuleScaffold;

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $this->newModuleScaffold = new NewModuleScaffold($this->argument('name'));

        $modelClass = Str::studly($name);
        $tableName = Str::snake(Str::pluralStudly($name));
        $this->info("Tạo Model: $modelClass");
        Artisan::call('make:model', [
            'name' => $modelClass,
            '-m' => true, // tạo migration luôn
            '-f' => true, // tạo factory luôn
            '-s' => true, // tạo seeder luôn
        ]);
        $this->info(Artisan::output());

        // Tạo livewire components
        $this->info("Tạo Livewire Components: $modelClass");
        $livewireClass = Str::studly(Str::pluralStudly($name));
        Artisan::call('make:livewire', [
            'name' => "Admin/{$livewireClass}",
        ]);
        $this->info(Artisan::output());

        // Tạo thư mục livewire
        $livewirePath = app_path('Livewire/Admin/' . $modelClass);
        if (!is_dir($livewirePath)) {
            $this->files->makeDirectory($livewirePath, 0755, true);
        }

        $stubs = [
            'index' => 'admin.index',
            'create' => 'admin.create',
            'edit' => 'admin.edit',
            'show' => 'admin.show',
        ];
        foreach ($stubs as $key => $value) {
            $this->info("Tạo Livewire Component: $modelClass/$key");
            Artisan::call('make:livewire', [
                'name' => "Admin/{$modelClass}/{$key}",
            ]);
        }

        $this->info(Artisan::output());

        $this->newModuleScaffold->createModel();
        $this->newModuleScaffold->createLivewire();
        $this->newModuleScaffold->createLivewireComponents();


        return CommandAlias::SUCCESS;
    }
}
