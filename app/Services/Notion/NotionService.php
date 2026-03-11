<?php

namespace App\Services\Notion;

class NotionService
{
    // Mocked database items
    public function getDatabaseItems()
    {
        return [
            'results' => [
                [
                    'id' => '1',
                    'properties' => [
                        'Name' => [
                            'title' => [
                                ['text' => ['content' => 'Sample Page 1']]
                            ]
                        ]
                    ]
                ],
                [
                    'id' => '2',
                    'properties' => [
                        'Name' => [
                            'title' => [
                                ['text' => ['content' => 'Sample Page 2']]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    // Mock creating a new page
    public function createPage(array $data)
    {
        return [
            'object' => 'page',
            'id' => 'mocked_' . rand(100, 999),
            'properties' => [
                'Name' => [
                    'title' => [
                        ['text' => ['content' => $data['title'] ?? 'Untitled Page']]
                    ]
                ]
            ]
        ];
    }
}