<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\Event;

class UpdateImagePathsForFilesystemConfig extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        // Update blog post images
        $blogs = DB::table('blogs')->whereNotNull('image')->get();
        foreach ($blogs as $blog) {
            $imagePath = $blog->image;
            
            // Handle different patterns that might exist in your database
            if (Str::startsWith($imagePath, 'public/')) {
                $imagePath = Str::replaceFirst('public/', '', $imagePath);
            }
            
            if (Str::startsWith($imagePath, 'news_images/')) {
                $imagePath = Str::replaceFirst('news_images/', '', $imagePath);
            }
            
            if (Str::startsWith($imagePath, 'events/')) {
                $imagePath = Str::replaceFirst('events/', '', $imagePath);
            }
            
            // Only update if the path has changed
            if ($imagePath !== $blog->image) {
                DB::table('blogs')
                    ->where('id', $blog->id)
                    ->update(['image' => $imagePath]);
            }
        }
        
        // Update event banner images
        $events = DB::table('events')->whereNotNull('banner_image')->get();
        foreach ($events as $event) {
            $bannerPath = $event->banner_image;
            
            // Handle different patterns that might exist in your database
            if (Str::startsWith($bannerPath, 'public/')) {
                $bannerPath = Str::replaceFirst('public/', '', $bannerPath);
            }
            
            if (Str::startsWith($bannerPath, 'event-banners/')) {
                $bannerPath = Str::replaceFirst('event-banners/', '', $bannerPath);
            }
            
            if (Str::startsWith($bannerPath, 'events/')) {
                $bannerPath = Str::replaceFirst('events/', '', $bannerPath);
            }
            
            // Only update if the path has changed
            if ($bannerPath !== $event->banner_image) {
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['banner_image' => $bannerPath]);
            }
        }
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // Note: It's difficult to perfectly reverse this migration
        // since we're removing prefixes and don't know which prefix each record had.
        // For simplicity, we won't implement a down migration.
        // In a production environment, you might want to back up the data first.
    }
}