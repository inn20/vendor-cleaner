<?php

namespace Inn20\VendorCleaner\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ClearUpCommand  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor-cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup vendor directory.';

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
        $vendorDir = config('vendorcleaner.dir');
        $rules = config('vendorcleaner.rules');

        $filesystem = new Filesystem();

        foreach ($rules as $packageDir => $rule) {
            if (!$filesystem->exists($vendorDir . '/' . $packageDir)) {
                continue;
            }

            $patterns = explode(' ', $rule);
            foreach($patterns as $pattern){
                try{
                    $finder = new Finder();
                    $finder->name($pattern)->in( $vendorDir . '/' . $packageDir);

                    // we can't directly iterate over $finder if it lists dirs we're deleting
                    $files = iterator_to_array($finder);

                    /** @var \SplFileInfo $file */
                    foreach($files as $file){
                        if($file->isDir()){
                            //$filesystem->deleteDirectory($file);
                            //$filesystem->copyDirectory($file, $vendorBackupDir);
                            $filesystem->deleteDirectory($file);
                        }elseif($file->isFile()){
                            $filesystem->delete($file);
                            //$filesystem->move($file, $vendorBackupDir);
                        }
                    }
                }catch(\Exception $e){
                    $this->error("Could not parse $packageDir ($pattern): ".$e->getMessage());
                }
            }

        }

    }
}
