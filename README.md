# Web Directory API

This is a proof-of-concept Restful API developed for Cavendish consulting.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/jerrywap/website-directory.git
    cd web-directory
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Copy the `.env.example` file to `.env` then configure your database:
    ```bash
    cp .env.example .env
    ```

4. Generate an application key:
    ```bash
    php artisan key:generate
    ```

5. Set up your database configuration in the `.env` file.

6. Run migrations and seed the database:
    ```bash
    php artisan migrate --seed
    ```

7. Serve the application:
    ```bash
    php artisan serve
    ```

## API Endpoints

### Authentication

- **Register:** `POST /api/register`
    ```json
    {
      "name": "Jerry Chukwudi",
      "email": "jerrychukwudi@email.com",
      "password": "password",
      "password_confirmation": "password"
    }
    ```

- **Login:** `POST /api/login`
    ```json
    {
      "email": "jerrychukwudi@email.com",
      "password": "password"
    }
    ```

- **Logout:** `POST /api/logout` (Authenticated)

### Websites

- **Get all websites (with search and voting count):** `GET /api/websites`
    - Query parameters:
        - `search`: Search term to filter websites by name, URL, or category. `GET /api/websites?search=Yundt`
    - Example response:
    ```json
    [
      {
        "id": 1,
        "name": "Example Website",
        "url": "https://example.com",
        "categories": [
          {
            "id": 1,
            "name": "Technology"
          }
        ],
        "votes_count": 5
      }
    ]
    ```

- **Create a website:** `POST /api/websites` (Authenticated)
    ```json
    {
      "name": "Example Website",
      "url": "https://example.com",
      "categories": [1, 2]
    }
    ```

- **Vote for a website:** `POST /api/websites/{id}/vote` (Authenticated)
    - Example response:
    ```json
    {
      "message": "Vote registered"
    }
    ```

- **Unvote a website:** `POST /api/websites/{id}/unvote` (Authenticated)
    - Example response:
    ```json
    {
      "message": "Vote removed"
    }
    ```

- **Delete a website:** `DELETE /api/websites/{id}` (Admin)
    - Example response:
    ```json
    {
      "message": "Website deleted"
    }
    ```

### Categories

- **Get all categories (with websites):** `GET /api/categories`
    - Example response:
    ```json
    [
      {
        "id": 1,
        "name": "Technology",
        "websites": [
          {
            "id": 1,
            "name": "Example Website",
            "url": "https://example.com",
            "votes_count": 5
          }
        ]
      }
    ]
    ```

- **Create a category:** `POST /api/categories` (Authenticated)
    ```json
    {
      "name": "Technology"
    }
    ```

### Search

- **Search websites:** `GET /api/websites?search={query}`
    - Query parameter:
        - `search`: Search term to filter websites by name, URL, or category.
    - Example response:
    ```json
    [
      {
        "id": 1,
        "name": "Example Website",
        "url": "https://example.com",
        "categories": [
          {
            "id": 1,
            "name": "Technology"
          }
        ],
        "votes_count": 5
      }
    ]
    ```

## Running Tests

To run tests, use the following command:
```bash
php artisan test
