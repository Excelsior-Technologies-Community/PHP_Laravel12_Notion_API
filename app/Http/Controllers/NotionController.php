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

    // GET /api/notion
    public function index()
    {
        $data = $this->notion->getDatabaseItems();
        return response()->json($data);
    }

    // POST /api/notion
    public function create(Request $request)
    {
        $page = $this->notion->createPage([
            'title' => $request->title
        ]);

        return response()->json($page);
    }
}