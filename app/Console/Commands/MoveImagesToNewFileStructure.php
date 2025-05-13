<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveImagesToNewFileStructure extends Command
{
    protected $signature = 'images:move';
    protected $description = 'Move images to match the new filesystem structure';

    public function handle()
    {
        // Move blog images
        $oldFiles = Storage::disk('public')->files('news_images');
        foreach ($oldFiles as $oldFile) {
            $filename = basename($oldFile);
            $newPath = $filename;
            
            // Copy the file to the new location
            if (Storage::disk('public')->exists($oldFile)) {
                $fileContents = Storage::disk('public')->get($oldFile);
                Storage::disk('news_images')->put($newPath, $fileContents);
                
                $this->info("Moved: {$oldFile} to news_images disk as {$newPath}");
            }
        }
        
        // Move event banner images
        $oldBanners = Storage::disk('public')->files('event-banners');
        foreach ($oldBanners as $oldBanner) {
            $filename = basename($oldBanner);
            $newPath = $filename;
            
            // Copy the file to the new location
            if (Storage::disk('public')->exists($oldBanner)) {
                $fileContents = Storage::disk('public')->get($oldBanner);
                Storage::disk('events')->put($newPath, $fileContents);
                
                $this->info("Moved: {$oldBanner} to events disk as {$newPath}");
            }
        }
        
        $this->info('Image migration completed!');
        
        return Command::SUCCESS;
    }
}