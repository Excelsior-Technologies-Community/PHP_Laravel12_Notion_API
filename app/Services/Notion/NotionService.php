<?php

namespace App\Services\Notion;

use Illuminate\Support\Facades\Http;
use App\Models\NotionPage;

class NotionService
{
    protected $apiKey;
    protected $databaseId;
    protected $baseUrl = 'https://api.notion.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.notion.key');
        $this->databaseId = config('services.notion.database_id');
    }

    public function getDatabaseItems()
    {
        $response = Http::withToken($this->apiKey)
            ->withHeaders(['Notion-Version' => '2022-06-28'])
            ->post("$this->baseUrl/databases/{$this->databaseId}/query");

        return $response->json()['results'] ?? [];
    }

    public function createPage(array $data)
    {
        return Http::withToken($this->apiKey)
            ->withHeaders(['Notion-Version' => '2022-06-28'])
            ->post("$this->baseUrl/pages", [
                'parent' => ['database_id' => $this->databaseId],
                'properties' => [
                    'Title' => [
                        'title' => [['text' => ['content' => $data['title']]]]
                    ]
                ]
            ])->json();
    }

    public function archivePage($pageId)
    {
        return Http::withToken($this->apiKey)
            ->withHeaders(['Notion-Version' => '2022-06-28'])
            ->patch("$this->baseUrl/pages/$pageId", [
                'archived' => true
            ])->json();
    }

    public function getAnalytics()
    {
        $items = $this->getDatabaseItems();
        return [
            'total'     => count($items),
            'active'    => count(array_filter($items, fn($i) => !$i['archived'])),
            'archived'  => count(array_filter($items, fn($i) => $i['archived'])),
            'last_sync' => now()->format('d M Y, H:i'),
        ];
    }

    public function syncDatabase()
    {
        $remoteItems = $this->getDatabaseItems();

        foreach ($remoteItems as $item) {
           
            $titleProperty = $item['properties']['Title']['title'] ?? [];
            $title = !empty($titleProperty)
                ? ($titleProperty[0]['plain_text'] ?? 'Untitled') 
                : 'Untitled';

            NotionPage::updateOrCreate(
                ['notion_id' => $item['id']],
                [
                    'title'       => $title,
                    'is_archived' => $item['archived']
                ]
            );
        }

        return count($remoteItems);
    }

  
    public function extractTitle(array $item): string
    {
        $titleArr = $item['properties']['Title']['title'] ?? [];
        return !empty($titleArr) ? ($titleArr[0]['plain_text'] ?? 'Untitled') : 'Untitled';
    }
}