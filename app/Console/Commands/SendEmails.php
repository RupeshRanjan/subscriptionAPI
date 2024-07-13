<?php

namespace App\Console\Commands;

use App\Mail\WebsitePost;
use App\Models\Post;
use App\Models\ScheduleRuns;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:post-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will check all websites and send all new posts to subscribers which haven\'t been sent yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastNotification = ScheduleRuns::where('task', 'website:post-notification')->orderBy('created_at', 'desc')->first();
        $post = Post::with('website.subscribers');
        if($lastNotification){
            $post->where('created_at', '>', $lastNotification->created_at);
        }
        $posts = $post->get();
        foreach ($posts as $post) {
            foreach ($post->website->subscribers as $user) {
                Mail::to($user)->send(new WebsitePost($post));
            }
        }
        ScheduleRuns::create(['task' => 'website:post-notification']);
    }
}
