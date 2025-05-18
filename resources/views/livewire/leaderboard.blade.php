<div>
    <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <input wire:model.live="search" type="search" placeholder="Search teams or dungeons..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('team.name')" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Team
                                @if ($sortField === 'team.name')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('dungeon_name')" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Dungeon
                                @if ($sortField === 'dungeon_name')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('key_level')" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Key Level
                                @if ($sortField === 'key_level')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('time_taken')" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Time
                                @if ($sortField === 'time_taken')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('completed_at')" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Completed At
                                @if ($sortField === 'completed_at')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($runs as $run)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    {{ $run->team->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $run->dungeon_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    +{{ $run->key_level }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ gmdate('H:i:s', $run->time_taken) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $run->completed_at->format('Y-m-d H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $runs->links() }}
            </div>
        </div>
    </div>
</div>
