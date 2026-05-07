<?php

namespace App\Services\Notion;

class NotionService
{
    private $pages = [];

    public function __construct()
    {
        $this->pages = [
            [
                'id' => '1',
                'title' => 'Laravel Notes',
                'archived' => false,
            ],
            [
                'id' => '2',
                'title' => 'API Documentation',
                'archived' => false,
            ],
            [
                'id' => '3',
                'title' => 'Project Ideas',
                'archived' => false,
            ],
        ];
    }

    // Get all active pages
    public function getDatabaseItems()
    {
        return array_values(array_filter($this->pages, function ($page) {
            return !$page['archived'];
        }));
    }

    // Create new page
    public function createPage(array $data)
    {
        $newPage = [
            'id' => rand(100, 999),
            'title' => $data['title'] ?? 'Untitled Page',
            'archived' => false,
        ];

        $this->pages[] = $newPage;

        return $newPage;
    }

    // Search pages
    public function searchPages($keyword)
    {
        return array_values(array_filter($this->pages, function ($page) use ($keyword) {
            return stripos($page['title'], $keyword) !== false
                && !$page['archived'];
        }));
    }

    // Archive page
    public function archivePage($id)
    {
        foreach ($this->pages as &$page) {
            if ($page['id'] == $id) {
                $page['archived'] = true;

                return [
                    'message' => 'Page archived successfully',
                    'page' => $page
                ];
            }
        }

        return [
            'message' => 'Page not found'
        ];
    }
}