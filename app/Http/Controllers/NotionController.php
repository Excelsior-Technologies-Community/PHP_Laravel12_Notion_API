<?php

namespace App\Http\Controllers;

use App\Services\Notion\NotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotionController extends Controller
{
    protected $notion;

    public function __construct(NotionService $notion)
    {
        $this->notion = $notion;
    }

    public function dashboard()
    {
        $analytics = Cache::remember('notion_analytics', 600, function () {
            return $this->notion->getAnalytics();
        });

        $items = Cache::remember('notion_pages', 300, function () {
            return $this->notion->getDatabaseItems();
        });

        return view('notion.dashboard', compact('analytics', 'items'));
    }

    public function index()
    {
        $pages = Cache::remember('notion_pages', 300, function () {
            return $this->notion->getDatabaseItems();
        });

        return response()->json([
            'success' => true,
            'pages' => $pages
        ]);
    }

    public function sync()
    {
        $count = $this->notion->syncDatabase();
        Cache::forget('notion_pages');
        Cache::forget('notion_analytics');

        return redirect()->route('notion.dashboard')
            ->with('success', "Successfully synced {$count} items");
    }

    public function create(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $page = $this->notion->createPage(['title' => $request->title]);
        Cache::forget('notion_pages');
        Cache::forget('notion_analytics');

        return redirect()->route('notion.dashboard')
            ->with('success', 'Page created successfully!');
    }

    public function archive($id)
    {
        $this->notion->archivePage($id);
        Cache::forget('notion_pages');
        Cache::forget('notion_analytics');

        return redirect()->route('notion.dashboard')
            ->with('success', 'Page archived successfully!');
    }
}