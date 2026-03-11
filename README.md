# PHP_Laravel12_Notion_API 

## Project Description

The PHP_Laravel12_Notion_API project is a Laravel 12 based REST API that simulates integration with Notion. It allows developers to fetch and create pages from a Notion-like database using a mocked service, without requiring real Notion API credentials.

This project is designed for learning, testing, and development purposes to understand how Laravel services, controllers, and APIs interact with external platforms like Notion. The project can later be extended to work with real Notion API keys for production.



## Features

- Fetch list of pages (GET API)

- Create a new page (POST API)

- Mocked Notion API for testing without real keys

- Clean structure with Service & Controller

- Easy to extend for real Notion integration


## Technologies 

1. PHP 8+ – Backend language

2. Laravel 12 – PHP framework

3. MySQL – Database

4. Postman / curl – API testing

5. Composer – Dependency management

6. MVC + Service Layer – Clean code structure


## Future Enhancements

- Integrate real Notion API using actual credentials

- Add authentication for secure API usage

- Implement update and delete operations

- Add frontend interface using Vue.js or React



---


## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Notion_API "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Notion_API

```

#### Explanation:

This command installs a fresh Laravel 12 application and creates the project folder.

The cd command moves into the newly created project directory.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Notion_API
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Notion_API

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel to MySQL and creates default tables for the project.




## STEP 3: Install HTTP Client & Notion Package 

### Install the package:

```
composer require fiveam-code/laravel-notion-api

```

#### Explanation

Installs the Laravel Notion API package to help communicate with Notion (or mock it).




## STEP 4: Publish the Notion Config

### Run:

```
php artisan vendor:publish --provider="NotionX\LaravelNotionApi\ServiceProvider"

```

### This creates:

```
config/notion.php

```


#### Explanation:

Creates config/notion.php for configuring your Notion API settings.




## STEP 5: Setup Notion Config File

### Open config/notion.php and replace with:

```
<?php

return [
    'token' => env('NOTION_KEY'),
    'version' => env('NOTION_VERSION', '2022-06-28'),
    'database_id' => env('NOTION_DATABASE_ID'),
];

```

#### Explanation:

Sets up the Notion API configuration using environment variables (can be mocked for now).





## STEP 6: Create Notion API Service

### Create directory:

```
mkdir app/Services/Notion

```

### Create file: app/Services/Notion/NotionService.php

```
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

```

#### Explanation:

Handles all Notion API logic (mocked here) for listing and creating pages.





## STEP 7: Create Controller

### Run:

```
php artisan make:controller NotionController

```

### Open: app/Http/Controllers/NotionController.php

```
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

```
#### Explanation:

Controller connects routes to the Notion service for GET and POST requests.




## STEP 8: Add API Routes

### Open: routes/api.php

```
use App\Http\Controllers\NotionController;

Route::get('notion', [NotionController::class, 'index']);
Route::post('notion', [NotionController::class, 'create']);

```

#### Explanation:

Defines API endpoints /api/notion for GET (list pages) and POST (create page).




## STEP 9:Test Your APIs

###  Test It

### Start Laravel dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Runs your Laravel project locally.



## STEP 10: Check In Postman

### GET Request:

1. Method: GET

2. URL:

```
 http://127.0.0.1:8000/api/notion

```

3. Click Send → You will see the JSON output.


<img width="1446" height="911" alt="Screenshot 2026-03-11 143513" src="https://github.com/user-attachments/assets/54974386-69ff-4677-8935-5ba27bdaa9ac" />






### POST Request:

1. Method: POST

2. URL:

```
http://127.0.0.1:8000/api/notion

```

3. Body → Raw → JSON:

```
{
  "title": "My Test Page"
}

```

4. Click Send → You will see the newly created mocked page.


<img width="1430" height="918" alt="Screenshot 2026-03-11 143552" src="https://github.com/user-attachments/assets/316907c3-53b2-43ae-ab63-07d1161d16d0" />





---


# Project Folder Structure:

```
PHP_Laravel12_Notion_API/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── NotionController.php
│   └── Services/
│       └── Notion/
│           └── NotionService.php
├── config/
│   └── notion.php
├── routes/
│   └── api.php
├── .env
├── artisan
└── composer.json

```
