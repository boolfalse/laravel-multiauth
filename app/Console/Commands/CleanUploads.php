<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CleanUploads extends Command
{
    protected $signature = 'cleanuploads';

    protected $description = 'Clean uploaded files.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = new Filesystem;

        // delete uploads from public
        $directory = 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . config('project.user.images_folder');
        $file->cleanDirectory($directory);
        $directory = 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . config('project.product.images_folder');
        $file->cleanDirectory($directory);

        // delete uploads from storage
        foreach (config('project.user.image_sizes') as $version => $sizes){
            $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . config('project.user.images_folder') . DIRECTORY_SEPARATOR . $version;
            $file->cleanDirectory($directory);
        }
        foreach (config('project.product.image_sizes') as $version => $sizes){
            $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . config('project.product.images_folder') . DIRECTORY_SEPARATOR . $version;
            $file->cleanDirectory($directory);
        }

        echo "Uploads successfully deleted!\n";
    }
}
