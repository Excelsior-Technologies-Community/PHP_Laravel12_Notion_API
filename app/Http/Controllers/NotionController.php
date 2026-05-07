<?php

namespace App\Http\Controllers;

use App\Services\Notion\NotionService;
use Illuminate\Http\Request;

class NotionController extends Controller
{
    protected $notion;

    public function __construct(NotionService $notion)
    {
        $this->notion = $notion;
    }

    // GET all pages
    public function index()
    {
        return response()->json([
            'success' => true,
            'pages' => $this->notion->getDatabaseItems()
        ]);
    }

    // CREATE page
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $page = $this->notion->createPage([
            'title' => $request->title
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'page' => $page
        ]);
    }

    // SEARCH page
    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        $results = $this->notion->searchPages($keyword);

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    // ARCHIVE page
    public function archive($id)
    {
        $response = $this->notion->archivePage($id);

        return response()->json($response);
    }
}