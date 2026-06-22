<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notion Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800">🚀 Notion Dashboard</h1>
            <div class="flex gap-3">
                {{-- Sync Button --}}
                <form action="{{ route('notion.sync') }}" method="POST">
                    @csrf
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition">
                        🔄 Sync Notion
                    </button>
                </form>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6 font-medium">
             
            </div>
        @endif

        {{-- Analytics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Entries</h3>
                <p class="text-4xl font-black text-slate-800 mt-2">{{ $analytics['total'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Active</h3>
                <p class="text-4xl font-black text-green-600 mt-2">{{ $analytics['active'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Archived</h3>
                <p class="text-4xl font-black text-red-500 mt-2">{{ $analytics['archived'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Last Sync</h3>
                <p class="text-sm font-bold text-slate-800 mt-2">{{ $analytics['last_sync'] }}</p>
            </div>
        </div>

        {{-- Create New Page Form --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6">
            <h2 class="font-bold text-lg text-slate-800 mb-4">➕ Create New Page</h2>
            <form action="{{ route('notion.create') }}" method="POST" class="flex gap-3">
                @csrf
                <input
                    type="text"
                    name="title"
                    placeholder="Page title..."
                    required
                    class="flex-1 border border-slate-300 rounded-lg px-4 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                >
                <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold transition">
                    Create
                </button>
            </form>
            @error('title')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Database Items Table --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <h2 class="font-bold text-xl text-slate-800 mb-6">📋 Database Items</h2>

            @if(empty($items))
                <div class="text-center py-12 text-slate-400">
                    <p class="text-5xl mb-4">📭</p>
                    <p class="font-medium">No items found. Sync your Notion database first!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 text-xs uppercase tracking-wider border-b">
                                <th class="pb-4 pr-4">Title</th>
                                <th class="pb-4 pr-4">Notion ID</th>
                                <th class="pb-4 pr-4">Status</th>
                                <th class="pb-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($items as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 pr-4 font-medium text-slate-700">
                                   
                                    {{ $item['properties']['Title']['title'][0]['plain_text'] ?? 'Untitled' }}
                                </td>
                                <td class="py-4 pr-4 text-slate-400 text-xs font-mono">
                                    {{ Str::limit($item['id'], 20) }}
                                </td>
                                <td class="py-4 pr-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        {{ $item['archived'] ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $item['archived'] ? 'Archived' : 'Active' }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    @if(!$item['archived'])
                                        <form action="{{ route('notion.archive', $item['id']) }}" method="POST"
                                              onsubmit="return confirm('Archive this page?')">
                                            @csrf
                                            <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-lg font-medium transition">
                                                Archive
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-300">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</body>
</html>