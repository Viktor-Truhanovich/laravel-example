<?php

namespace App\Console\Commands;

use App\Models\PackageService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PackageArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Package:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add packages to archive';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $packages = PackageService::where('date_end', '<', Carbon::now())->get();

        $packages->each(function ($package) {
            $package->update(['status' => PackageService::STATUS_ARCHIVE]);
        });
    }
}
