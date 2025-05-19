# FocusCameraInterview
This is my solution for the Focus Camera Interview Assessment in Laravel

Goal: create an API that lets you manage a list of tasks.

## What It Does (Features)

With this API, you can:
* **Create a new Task**
* **See a list of all Tasks**
* **Get the details of a single Task**
* **Update an existing Task**
* **Delete a Task**

Each task in the system has:
* An `id` (a unique number that automatically ascends)
* A `title` (this is required for every task)
* A `description` (this is optional)
* An `is_completed` status (to mark if it's done or not, defaults to `false`)
* `created_at` and `updated_at` timestamps (Laravel handles these automatically)

## How to Get It Running (Setup Instructions)

To get this project running on your own computer, here are the steps I'd suggest:

1.  **Download or Clone the Project:**
    * If you have this project as a zip file, unzip it.
    * If it were in a Git repository, you'd normally `git clone` it.

2.  **Go into the Project Folder:**
    Open your terminal or command prompt and navigate into the `task-manager-api` directory.

3.  **Install Required Packages (Dependencies):**
    I used Composer to manage the PHP packages Laravel needs. Run this command:
    composer install

4.  **Set Up the Environment File:**
    * Laravel uses a `.env` file for configuration. I started by copying the example file:
        cp .env.example .env
        
    * Then, I generated a unique application key (this is important for security):
        php artisan key:generate

    * For the database, I set it up to use SQLite. This is simple because it's just a file. Laravel will automatically create it at `database/database.sqlite` when needed.

5.  **Create the Database Tables:**
    I defined the structure for the `tasks` table (and others Laravel uses) in migration files. To create these tables in the database, I ran:
    php artisan migrate

6.  **Start the Local Server:**
    To run the API, I used Laravel's built-in development server:
    php artisan serve

    This usually makes the API available at `http://127.0.0.1:8000`. The API paths will start with `/api/`.

## How to Use the API (Endpoints)

Here are the API endpoints I created:

* **Create a new task:** `POST /api/tasks`
    * You need to send JSON data in the body like this:
        `{ "title": "Your task title (required)", "description": "Some details (optional)", "is_completed": false (optional, defaults to false) }`
    * If successful, it responds with `201 Created` and shows you the task that was created.
    * If there's a problem with the data (like no title), it responds with `422 Unprocessable Entity`.

* **List all tasks:** `GET /api/tasks`
    * If successful, it responds with `200 OK` and a list of all tasks.

* **Get a single task:** `GET /api/tasks/{id}` (replace `{id}` with the task's ID number)
    * If successful, it responds with `200 OK` and the details of that task.
    * If no task with that ID exists, it responds with `404 Not Found`.

* **Update a task:** `PUT /api/tasks/{id}` (replace `{id}` with the task's ID number)
    * You send JSON data in the body with the fields you want to change:
        `{ "title": "New title (optional)", "description": "New description (optional)", "is_completed": true (optional) }`
        (Note: If you send a `title`, it can't be empty.)
    * If successful, it responds with `200 OK` and the updated task details.
    * If the task ID doesn't exist, it's a `404 Not Found`. If the data is bad, it's a `422 Unprocessable Entity`.

* **Delete a task:** `DELETE /api/tasks/{id}` (replace `{id}` with the task's ID number)
    * If successful, it responds with `204 No Content` (meaning it worked, but there's nothing to show in the body).
    * If the task ID doesn't exist, it's a `404 Not Found`.
    
    
## My Approach (How I Built It)

To complete this Task Manager API assignment, I took the following steps and made these implementation choices:

* **Project Setup & Core Components:** I began by setting up a new Laravel project.  I then created the necessary `Task` model (to represent a task), a database migration file (to define the `tasks` table structure), and a `TaskController` (to handle the API logic for tasks).

* **Database with SQLite:** For the database, I configured the Laravel project to use SQLite. I opted for SQLite because it's file-based, which simplifies setup for a development task like this as it doesn't require a separate database server. The `tasks` table schema included `id`, `title`, `description`, `is_completed`, and the automatic `created_at`/`updated_at` timestamps, as specified.

* **RESTful API Design:** I designed the API endpoints to follow RESTful principles, as required. This involved:
    * Using standard HTTP methods: `GET` (to retrieve tasks), `POST` (to create tasks), `PUT` (to update tasks), and `DELETE` (to remove tasks).
    * Ensuring the API returns appropriate HTTP status codes (e.g., `200` for success, `201` for created, `204` for successful deletion with no content, `404` for not found, and `422` for validation errors).

* **Eloquent ORM for Database Interactions:** As per the requirement to use Eloquent ORM, all interactions with the `tasks` database table were handled through the `Task` model. This allowed for a clean, object-oriented way to perform create, read, update, and delete operations (e.g., `Task::all()`, `Task::create()`, `$task->fill()`, `$task->save()`, `$task->delete()`). For retrieving, updating, and deleting specific tasks, I utilized Laravel's implicit route model binding in the `TaskController`.

* **Request Validation:** To meet the requirement for validating incoming requests, I implemented validation logic within the `TaskController`'s `store` (create) and `update` methods using Laravel's `Validator` facade. This ensures that, for example, the `title` is always provided and that fields match their expected types (string, boolean). If validation fails, the API returns a `422` status code with details about the errors.

* **Handling "Not Found" Errors:** To handle cases where a specific task ID might not exist during `show`, `update`, or `delete` operations, I leveraged Laravel's implicit route model binding (e.g. `Task $task` in the controller method signature). This feature automatically attempts to find the `Task` model based on the ID in the URL. If the task doesn't exist, Laravel automatically triggers a `404 Not Found` response, fulfilling the need for appropriate error handling without needing to manually call `Task::findOrFail()` in those controller methods.

* **Controller Logic & JSON Responses:** The `TaskController` houses all the specific logic for each API endpoint. Each method in the controller is responsible for processing the request, interacting with the `Task` model (often injected via route model binding), and then returning a JSON response, as required for an API.

* **API Routing:** I defined the API routes in Laravel's `routes/api.php` file, using `Route::apiResource('tasks', TaskController::class);`. This efficiently sets up all the standard RESTful routes for the `Task` resource and maps them to the corresponding methods in the `TaskController`.

* **Key Files I Worked On:**
    * `app/Models/Task.php`: Defined the Task model, its `$fillable` properties (for mass assignment), and `$casts` (for data types like boolean).
    * `app/Http/Controllers/Api/TaskController.php`: Implemented all the API methods (index, store, show, update, destroy), including validation and using route model binding.
    * `database/migrations/2025_05_19_183849_create_tasks_table.php` (timestamped file): Defined the database structure for the `tasks` table.
    * `routes/api.php`: Set up the API endpoints.
    * `.env`: Ensured this was configured for SQLite (which Laravel did by default)
    * This `README.md` file: To document the project.