<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DiscordNotificationService;
use App\Models\Run;

class TestDiscordNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:test-notification {run_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Discord notification for an approved run';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $runId = $this->argument('run_id');

        if ($runId) {
            $run = Run::with(['team.users', 'season'])->find($runId);
        } else {
            $run = Run::with(['team.users', 'season'])->first();
        }

        if (!$run) {
            $this->error('No run found to test with. Please create a run first or specify a valid run ID.');
            return 1;
        }

        $this->info('Testing Discord notification for run ID: ' . $run->id);
        $this->info('Team: ' . $run->team->name);
        $this->info('Dungeon: ' . $run->dungeon_name);
        $this->info('Key Level: +' . $run->key_level);

        try {
            $discordService = new DiscordNotificationService();
            $discordService->sendRunApprovedNotification($run);

            $this->info('✅ Discord notification sent successfully!');
            $this->info('Check your Discord channel to see the message.');

        } catch (\Exception $e) {
            $this->error('❌ Failed to send Discord notification: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
