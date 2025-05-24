<?php

namespace App\Services;

use App\Models\Run;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordNotificationService
{
    private string $webhookUrl;

    // Dungeon images mapping
    private array $dungeonImages = [
        'The MOTHERLODE!!' => 'https://wow.zamimg.com/uploads/screenshots/normal/756154.jpg',
        'Darkflame Cleft' => 'https://wow.zamimg.com/uploads/screenshots/normal/1150151.jpg',
        'Theater of Pain' => 'https://wow.zamimg.com/uploads/screenshots/normal/927194.jpg',
        'The Rookery' => 'https://wow.zamimg.com/uploads/screenshots/normal/1150152.jpg',
        'Cinderbrew Meadery' => 'https://wow.zamimg.com/uploads/screenshots/normal/1150153.jpg',
        'Operation: Floodgate' => 'https://wow.zamimg.com/uploads/screenshots/normal/1150154.jpg',
        'Mechagon Workshop' => 'https://wow.zamimg.com/uploads/screenshots/normal/826465.jpg',
        'Priory of the Sacred Flame' => 'https://wow.zamimg.com/uploads/screenshots/normal/1150155.jpg',
    ];

    // Key level colors (based on difficulty)
    private function getKeyLevelColor(int $keyLevel): int
    {
        if ($keyLevel >= 20) return 0xFF6B35; // Legendary Orange
        if ($keyLevel >= 15) return 0xA335EE; // Epic Purple
        if ($keyLevel >= 10) return 0x0070DD; // Rare Blue
        if ($keyLevel >= 7) return 0x1EFF00;  // Uncommon Green
        return 0xFFFFFF; // Common White
    }

    // Get performance emoji based on key level and status
    private function getPerformanceEmoji(Run $run): string
    {
        if ($run->status === 'failed') return '💀';
        if ($run->key_level >= 20) return '🏆';
        if ($run->key_level >= 15) return '⭐';
        if ($run->key_level >= 10) return '🎯';
        return '✅';
    }

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->webhookUrl = config('services.discord.webhook_url', 'https://discord.com/api/webhooks/1375091868676653126/NUTyTHlZD1PWmUF_j4muru8ehzrCt8Ynlk_nDGR65L60O8SeQcfkd8eYxH4SI4UfydJz');
    }

    public function sendRunApprovedNotification(Run $run): void
    {
        try {
            // Load relationships
            $run->load(['team.users', 'season']);

            // Format the time
            $minutes = floor($run->time_taken_seconds / 60);
            $seconds = $run->time_taken_seconds % 60;
            $timeFormatted = sprintf('%02d:%02d', $minutes, $seconds);

            // Get team members with their roles if available
            $teamMembers = $run->team->users->map(function ($user) {
                $role = $user->pivot->role ?? 'Member';
                $roleEmoji = match(strtolower($role)) {
                    'tank' => '🛡️',
                    'healer' => '💚',
                    'dps', 'dps1', 'dps2', 'dps3' => '⚔️',
                    default => '👤'
                };
                return $roleEmoji . ' ' . $user->name;
            })->join("\n");

            // Get dungeon image
            $dungeonImage = $this->dungeonImages[$run->dungeon_name] ?? 'https://wow.zamimg.com/uploads/screenshots/normal/756154.jpg';

            // Get color based on key level
            $embedColor = $this->getKeyLevelColor($run->key_level);

            // Get performance emoji
            $performanceEmoji = $this->getPerformanceEmoji($run);

            // Create the main embed
            $embed = [
                'title' => $performanceEmoji . ' Mythic+ Run Approved! ' . $performanceEmoji,
                'description' => "**{$run->team->name}** just conquered **{$run->dungeon_name}**!",
                'color' => $embedColor,
                'image' => [
                    'url' => 'https://wow.zamimg.com/uploads/screenshots/normal/1150156.jpg' // WoW mythic+ banner
                ],
                'fields' => [
                    [
                        'name' => '🏰 Dungeon',
                        'value' => $run->dungeon_name,
                        'inline' => true
                    ],
                    [
                        'name' => '🔥 Key Level',
                        'value' => '+' . $run->key_level,
                        'inline' => true
                    ],
                    [
                        'name' => '⏱️ Completion Time',
                        'value' => $timeFormatted,
                        'inline' => true
                    ],
                    [
                        'name' => '🛡️ Team',
                        'value' => "**{$run->team->name}**",
                        'inline' => true
                    ],
                    [
                        'name' => '🏅 Season',
                        'value' => "**{$run->season->name}**",
                        'inline' => true
                    ],
                    [
                        'name' => '✅ Status',
                        'value' => '**' . strtoupper($run->status) . '**',
                        'inline' => true
                    ]
                ],
                'author' => [
                    'name' => 'DGMP - Danish Guild Mythic Plus',
                    'icon_url' => 'https://wow.zamimg.com/images/wow/icons/large/achievement_guildperk_fasttrack_rank2.jpg',
                    'url' => 'https://www.wowhead.com/mythic-keystones-and-dungeons-guide'
                ],
                'footer' => [
                    'text' => 'Run approved at ' . now()->format('d/m/Y H:i'),
                    'icon_url' => 'https://wow.zamimg.com/images/wow/icons/medium/inv_relics_hourglass.jpg'
                ],
                'timestamp' => now()->toISOString()
            ];

            // Add team members as a separate field with better formatting
            if ($teamMembers) {
                $embed['fields'][] = [
                    'name' => '👥 Team Composition',
                    'value' => $teamMembers,
                    'inline' => false
                ];
            }

            // Add Warcraft Log link if available
            if ($run->warcraft_log_url) {
                $embed['fields'][] = [
                    'name' => '📊 Combat Analysis',
                    'value' => "[🔗 View Warcraft Logs]({$run->warcraft_log_url})\n*Click to analyze performance data*",
                    'inline' => false
                ];
            }

            // Create achievement-style message
            $achievementMessage = $this->createAchievementMessage($run);

            $payload = [
                'content' => $achievementMessage,
                'embeds' => [$embed]
            ];

            // Send the webhook
            $response = Http::timeout(10)->post($this->webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Discord notification sent successfully for run ID: ' . $run->id);
            } else {
                Log::error('Failed to send Discord notification for run ID: ' . $run->id . '. Response: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Error sending Discord notification for run ID: ' . $run->id . '. Error: ' . $e->getMessage());
        }
    }

    private function createAchievementMessage(Run $run): string
    {
        $achievementEmojis = [
            20 => '🏆 **LEGENDARY ACHIEVEMENT UNLOCKED!** 🏆',
            15 => '⭐ **EPIC ACHIEVEMENT UNLOCKED!** ⭐',
            10 => '🎯 **RARE ACHIEVEMENT UNLOCKED!** 🎯',
            7 => '🌟 **ACHIEVEMENT UNLOCKED!** 🌟',
        ];

        foreach ($achievementEmojis as $level => $message) {
            if ($run->key_level >= $level) {
                return $message;
            }
        }

        return '✅ **RUN COMPLETED!** ✅';
    }
}
