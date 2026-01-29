<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            {{-- Page Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Audit Logs</h1>
                <p class="mt-1 text-sm text-gray-600">Track all transaction activities within your family account.</p>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('audit-logs.index') }}"
                        class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        {{-- Action Filter --}}
                        <div>
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <select name="action" id="action"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Actions</option>
                                <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created
                                </option>
                                <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated
                                </option>
                                <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted
                                </option>
                            </select>
                        </div>

                        {{-- Actor Filter --}}
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Performed
                                By</label>
                            <select name="user_id" id="user_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">From
                                Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- End Date --}}
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Filter Actions --}}
                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                            </button>
                            @if(request()->hasAny(['action', 'user_id', 'start_date', 'end_date']))
                                <a href="{{ route('audit-logs.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Logs Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($logs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date & Time
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actor
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Target
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Details
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            IP Address
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($logs as $log)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="font-medium">{{ $log->created_at->format('d M Y') }}</div>
                                                <div class="text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-sm">
                                                        {{ strtoupper(substr($log->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $log->user->name ?? 'Unknown' }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ ucfirst($log->user->role ?? 'N/A') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->action_color }}">
                                                    {{ $log->action_label }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                <div class="font-medium text-gray-900">{{ $log->model_name }}
                                                    #{{ $log->model_id }}</div>
                                                @if($log->targetUser)
                                                    <div class="text-gray-500 text-xs">by {{ $log->targetUser->name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                @if($log->action === 'updated' && $log->new_values)
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button @click="open = !open" type="button"
                                                            class="text-indigo-600 hover:text-indigo-900 text-xs flex items-center">
                                                            <span>View Changes</span>
                                                            <svg class="w-4 h-4 ml-1 transition-transform"
                                                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </button>
                                                        <div x-show="open" x-cloak
                                                            class="mt-2 bg-gray-50 rounded-md p-3 text-xs max-w-md">
                                                            @foreach($log->new_values as $field => $newValue)
                                                                <div class="mb-2 last:mb-0">
                                                                    <span
                                                                        class="font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                                                    <div class="flex items-center mt-1">
                                                                        <span
                                                                            class="text-red-600 line-through mr-2">{{ $log->old_values[$field] ?? 'N/A' }}</span>
                                                                        <svg class="w-3 h-3 text-gray-400 mr-2" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                        </svg>
                                                                        <span class="text-green-600">{{ $newValue }}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif($log->action === 'created' && $log->new_values)
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button @click="open = !open" type="button"
                                                            class="text-indigo-600 hover:text-indigo-900 text-xs flex items-center">
                                                            <span>View Data</span>
                                                            <svg class="w-4 h-4 ml-1 transition-transform"
                                                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </button>
                                                        <div x-show="open" x-cloak
                                                            class="mt-2 bg-green-50 rounded-md p-3 text-xs max-w-md">
                                                            @foreach($log->new_values as $field => $value)
                                                                @if(!in_array($field, ['id', 'tenant_id', 'user_id']))
                                                                    <div class="mb-1 last:mb-0">
                                                                        <span
                                                                            class="font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                                                        <span
                                                                            class="text-green-700 ml-1">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif($log->action === 'deleted' && $log->old_values)
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button @click="open = !open" type="button"
                                                            class="text-indigo-600 hover:text-indigo-900 text-xs flex items-center">
                                                            <span>View Deleted Data</span>
                                                            <svg class="w-4 h-4 ml-1 transition-transform"
                                                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </button>
                                                        <div x-show="open" x-cloak
                                                            class="mt-2 bg-red-50 rounded-md p-3 text-xs max-w-md">
                                                            @foreach($log->old_values as $field => $value)
                                                                @if(!in_array($field, ['id', 'tenant_id', 'user_id']))
                                                                    <div class="mb-1 last:mb-0">
                                                                        <span
                                                                            class="font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                                                        <span
                                                                            class="text-red-700 ml-1">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->ip_address ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No audit logs found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->hasAny(['action', 'user_id', 'start_date', 'end_date']))
                                    No logs match your current filters.
                                @else
                                    Audit logs will appear here once transactions are created, updated, or deleted.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>