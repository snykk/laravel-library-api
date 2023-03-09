<?php

namespace App\Console\Commands;

use App\Models\CmsAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateNewAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:create-admin {name=Administrator} {email=admin@admin.com} {password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CMS super-admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get an argument with string data type.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getStringArgument(string $key): string
    {
        $value = $this->argument($key);

        return is_string($value) ? $value : '';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newAdmin = CmsAdmin::create([
            'name'     => $this->getStringArgument('name'),
            'email'    => $this->getStringArgument('email'),
            'password' => Hash::make($this->getStringArgument('password')),
        ]);

        $newAdmin->assignRole('super-administrator');

        $this->line('<info>Admin ' . $this->getStringArgument('email') . ' has been created.</info>');
    }
}
