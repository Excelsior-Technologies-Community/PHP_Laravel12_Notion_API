<?php

return [
    'token' => env('NOTION_KEY'),
    'version' => env('NOTION_VERSION', '2022-06-28'),
    'database_id' => env('NOTION_DATABASE_ID'),
];