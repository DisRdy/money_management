<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Audit Log Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('audit-logs.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Audit Logs
                </a>
            </div>

            {{-- Log Details Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $auditLog->model_name }} #{{ $auditLog->model_id }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $auditLog->created_at->format('d M Y, H:i:s') }}
                            </p>
                        </div>
                        <span
                            class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $auditLog->action_color }}">
                            {{ $auditLog->action_label }}
                        </span>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Actor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Performed By</label>
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-semibold">
                                    {{ strtoupper(substr($auditLog->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $auditLog->user->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($auditLog->user->role ?? 'N/A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Target User --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Record Owner</label>
                            @if($auditLog->targetUser)
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 font-semibold">
                                        {{ strtoupper(substr($auditLog->targetUser->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $auditLog->targetUser->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($auditLog->targetUser->role) }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </div>

                        {{-- IP Address --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">IP Address</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $auditLog->ip_address ?? '—' }}</p>
                        </div>

                        {{-- User Agent --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">User Agent</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 truncate" title="{{ $auditLog->user_agent }}">
                                {{ Str::limit($auditLog->user_agent, 50) ?? '—' }}
                            </p>
                        </div>
                    </div>

                    {{-- Changes Section --}}
                    @if($auditLog->action === 'updated')
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Changes Made</h4>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-100 dark:bg-gray-600/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                Field</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Old
                                                Value</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">New
                                                Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($auditLog->new_values ?? [] as $field => $newValue)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400">
                                                    {{ $auditLog->old_values[$field] ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-green-600 dark:text-green-400">
                                                    {{ $newValue }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Created Data Section --}}
                    @if($auditLog->action === 'created' && $auditLog->new_values)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Created Data</h4>
                            <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4">
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($auditLog->new_values as $field => $value)
                                        @if(!in_array($field, ['id', 'tenant_id', 'user_id']))
                                            <div>
                                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst(str_replace('_', ' ', $field)) }}</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                                    {{ is_array($value) ? json_encode($value) : $value }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    @endif

                    {{-- Deleted Data Section --}}
                    @if($auditLog->action === 'deleted' && $auditLog->old_values)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Deleted Data</h4>
                            <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4">
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($auditLog->old_values as $field => $value)
                                        @if(!in_array($field, ['id', 'tenant_id', 'user_id']))
                                            <div>
                                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst(str_replace('_', ' ', $field)) }}</dt>
                                                <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                                    {{ is_array($value) ? json_encode($value) : $value }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>