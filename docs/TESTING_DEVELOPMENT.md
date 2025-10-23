# Testing & Development Documentation

## Overview
This document provides comprehensive guidelines for testing and development of the Gateway Dashboard application, including testing strategies, development workflows, and best practices.

## Table of Contents
1. [Development Environment Setup](#development-environment-setup)
2. [Testing Strategy](#testing-strategy)
3. [Unit Testing](#unit-testing)
4. [Feature Testing](#feature-testing)
5. [Integration Testing](#integration-testing)
6. [API Testing](#api-testing)
7. [Browser Testing](#browser-testing)
8. [Performance Testing](#performance-testing)
9. [Security Testing](#security-testing)
10. [Development Workflow](#development-workflow)
11. [Code Quality](#code-quality)
12. [Debugging](#debugging)
13. [Testing Tools](#testing-tools)
14. [CI/CD Pipeline](#cicd-pipeline)

---

## Development Environment Setup

### Prerequisites
```bash
# Required software
PHP >= 8.1
Laravel >= 10.0
PostgreSQL >= 13.0
Redis >= 6.0
Node.js >= 16.0
Composer >= 2.0
NPM >= 8.0
Git >= 2.0
```

### Local Development Setup
```bash
# Clone repository
git clone <repository-url> gateway_dashboard
cd gateway_dashboard

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database
# Edit .env file with your database credentials

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Build frontend assets
npm run dev

# Start development server
php artisan serve
```

### Docker Development Setup
```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: gateway_dashboard_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - gateway_network

  nginx:
    image: nginx:alpine
    container_name: gateway_dashboard_nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - gateway_network

  postgres:
    image: postgres:13
    container_name: gateway_dashboard_postgres
    restart: unless-stopped
    environment:
      POSTGRES_DB: gateway
      POSTGRES_USER: gateway_user
      POSTGRES_PASSWORD: password
    ports:
      - "5433:5432"
    volumes:
      - postgres_data:/var/lib/postgresql/data
    networks:
      - gateway_network

  redis:
    image: redis:6-alpine
    container_name: gateway_dashboard_redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - gateway_network

volumes:
  postgres_data:

networks:
  gateway_network:
    driver: bridge
```

### Development Tools
```bash
# Install development tools
composer require --dev phpunit/phpunit
composer require --dev laravel/telescope
composer require --dev barryvdh/laravel-debugbar
composer require --dev squizlabs/php_codesniffer
composer require --dev phpstan/phpstan

# Install frontend development tools
npm install --save-dev @testing-library/jest-dom
npm install --save-dev @testing-library/react
npm install --save-dev @testing-library/user-event
npm install --save-dev jest
npm install --save-dev cypress
```

---

## Testing Strategy

### Testing Pyramid
```
        /\
       /  \
      / E2E \     End-to-End Tests (10%)
     /______\
    /        \
   /Integration\  Integration Tests (20%)
  /____________\
 /              \
/   Unit Tests   \  Unit Tests (70%)
/________________\
```

### Test Categories

#### 1. Unit Tests (70%)
- **Purpose**: Test individual components in isolation
- **Scope**: Models, Services, Helpers, Utilities
- **Tools**: PHPUnit, Jest
- **Coverage**: 90%+ code coverage

#### 2. Integration Tests (20%)
- **Purpose**: Test component interactions
- **Scope**: Controllers, API endpoints, Database operations
- **Tools**: PHPUnit, Laravel Testing
- **Coverage**: Critical business logic

#### 3. End-to-End Tests (10%)
- **Purpose**: Test complete user workflows
- **Scope**: User journeys, API workflows
- **Tools**: Cypress, Laravel Dusk
- **Coverage**: Critical user paths

---

## Unit Testing

### PHPUnit Configuration
```xml
<!-- phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
        <exclude>
            <directory>./app/Console</directory>
            <directory>./app/Exceptions</directory>
            <directory>./app/Http/Middleware</directory>
        </exclude>
    </coverage>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

### Model Tests
```php
<?php
// tests/Unit/Models/UserTest.php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);
    }

    public function test_user_soft_delete()
    {
        $user = User::factory()->create();
        
        $user->softDelete();
        
        $this->assertFalse($user->isActive());
        $this->assertEquals(0, $user->is_active);
    }

    public function test_user_restore()
    {
        $user = User::factory()->create(['is_active' => 0]);
        
        $user->restore();
        
        $this->assertTrue($user->isActive());
        $this->assertEquals(1, $user->is_active);
    }

    public function test_user_role_scopes()
    {
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'operator']);
        User::factory()->create(['role' => 'viewer']);

        $this->assertEquals(1, User::admins()->count());
        $this->assertEquals(1, User::operators()->count());
        $this->assertEquals(1, User::viewers()->count());
    }

    public function test_user_active_scope()
    {
        User::factory()->create(['is_active' => 1]);
        User::factory()->create(['is_active' => 0]);

        $this->assertEquals(1, User::active()->count());
        $this->assertEquals(1, User::inactive()->count());
    }
}
```

### Service Tests
```php
<?php
// tests/Unit/Services/UserServiceTest.php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function test_create_user()
    {
        $userData = [
            'username' => 'testuser',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'admin',
            'is_active' => 1
        ];

        $user = $this->userService->createUser($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('admin', $user->role);
        $this->assertTrue($user->isActive());
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $result = $this->userService->updateUser($user, $updateData);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();

        $result = $this->userService->deleteUser($user);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertFalse($user->isActive());
    }

    public function test_restore_user()
    {
        $user = User::factory()->create(['is_active' => 0]);

        $result = $this->userService->restoreUser($user);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertTrue($user->isActive());
    }

    public function test_get_paginate()
    {
        User::factory()->count(15)->create();

        $users = $this->userService->getPaginate('', 10);

        $this->assertEquals(10, $users->count());
        $this->assertEquals(2, $users->lastPage());
    }

    public function test_search_users()
    {
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);
        User::factory()->create(['name' => 'Bob Johnson']);

        $users = $this->userService->getPaginate('John', 10);

        $this->assertEquals(2, $users->count());
        $this->assertTrue($users->contains('name', 'John Doe'));
        $this->assertTrue($users->contains('name', 'Bob Johnson'));
    }
}
```

---

## Feature Testing

### Controller Tests
```php
<?php
// tests/Feature/Http/Controllers/UserControllerTest.php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_users_list()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        User::factory()->count(5)->create();

        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
        $response->assertViewHas('users');
    }

    public function test_create_returns_create_form()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $response = $this->get('/users/create');

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    public function test_store_creates_new_user()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $userData = [
            'username' => 'newuser',
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
            'is_active' => 1
        ];

        $response = $this->post('/users', $userData);

        $response->assertRedirect('/users');
        $response->assertSessionHas('success', 'User created successfully.');
        
        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'operator'
        ]);
    }

    public function test_show_returns_user_details()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $targetUser = User::factory()->create();

        $response = $this->get("/users/{$targetUser->id}");

        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $targetUser);
    }

    public function test_edit_returns_edit_form()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $targetUser = User::factory()->create();

        $response = $this->get("/users/{$targetUser->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $targetUser);
    }

    public function test_update_modifies_user()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $targetUser = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'operator'
        ];

        $response = $this->put("/users/{$targetUser->id}", $updateData);

        $response->assertRedirect("/users/{$targetUser->id}");
        $response->assertSessionHas('success', 'User updated successfully.');
        
        $targetUser->refresh();
        $this->assertEquals('Updated Name', $targetUser->name);
        $this->assertEquals('updated@example.com', $targetUser->email);
        $this->assertEquals('operator', $targetUser->role);
    }

    public function test_destroy_soft_deletes_user()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $targetUser = User::factory()->create();

        $response = $this->delete("/users/{$targetUser->id}");

        $response->assertRedirect('/users');
        $response->assertSessionHas('success', 'User deactivated successfully.');
        
        $targetUser->refresh();
        $this->assertFalse($targetUser->isActive());
    }

    public function test_destroy_prevents_self_deletion()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $response = $this->delete("/users/{$user->id}");

        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You cannot delete your own account.');
        
        $user->refresh();
        $this->assertTrue($user->isActive());
    }
}
```

### API Tests
```php
<?php
// tests/Feature/Api/UserApiTest.php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_users_returns_json()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        User::factory()->count(5)->create();

        $response = $this->actingAs($user)->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'username',
                        'name',
                        'email',
                        'role',
                        'is_active',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]
        ]);
    }

    public function test_create_user_via_api()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $userData = [
            'username' => 'apiuser',
            'name' => 'API User',
            'email' => 'apiuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
            'is_active' => 1
        ];

        $response = $this->actingAs($user)->postJson('/api/users', $userData);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'User created successfully'
        ]);
        
        $this->assertDatabaseHas('users', [
            'username' => 'apiuser',
            'name' => 'API User',
            'email' => 'apiuser@example.com'
        ]);
    }

    public function test_update_user_via_api()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create();

        $updateData = [
            'name' => 'Updated API User',
            'email' => 'updated@example.com'
        ];

        $response = $this->actingAs($user)->putJson("/api/users/{$targetUser->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'User updated successfully'
        ]);
        
        $targetUser->refresh();
        $this->assertEquals('Updated API User', $targetUser->name);
        $this->assertEquals('updated@example.com', $targetUser->email);
    }

    public function test_delete_user_via_api()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/users/{$targetUser->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'User deactivated successfully'
        ]);
        
        $targetUser->refresh();
        $this->assertFalse($targetUser->isActive());
    }
}
```

---

## Integration Testing

### Database Integration Tests
```php
<?php
// tests/Feature/Database/ClientServiceAssignmentTest.php

namespace Tests\Feature\Database;

use App\Models\Client;
use App\Models\Service;
use App\Models\ClientServiceAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientServiceAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_service_assignment_creation()
    {
        $client = Client::factory()->create();
        $service = Service::factory()->create();

        $assignment = ClientServiceAssignment::create([
            'client_id' => $client->id,
            'service_id' => $service->id
        ]);

        $this->assertDatabaseHas('service_assigns', [
            'client_id' => $client->id,
            'service_id' => $service->id
        ]);

        $this->assertTrue($client->services->contains($service));
        $this->assertTrue($service->clients->contains($client));
    }

    public function test_client_service_cascade_delete()
    {
        $client = Client::factory()->create();
        $service = Service::factory()->create();
        
        ClientServiceAssignment::create([
            'client_id' => $client->id,
            'service_id' => $service->id
        ]);

        $client->delete();

        $this->assertDatabaseMissing('service_assigns', [
            'client_id' => $client->id,
            'service_id' => $service->id
        ]);
    }

    public function test_unique_client_service_constraint()
    {
        $client = Client::factory()->create();
        $service = Service::factory()->create();

        ClientServiceAssignment::create([
            'client_id' => $client->id,
            'service_id' => $service->id
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        ClientServiceAssignment::create([
            'client_id' => $client->id,
            'service_id' => $service->id
        ]);
    }
}
```

### Redis Integration Tests
```php
<?php
// tests/Feature/Redis/ClientCacheTest.php

namespace Tests\Feature\Redis;

use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ClientCacheTest extends TestCase
{
    use RefreshDatabase;

    protected ClientService $clientService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientService = new ClientService();
    }

    public function test_client_redis_cache_creation()
    {
        $client = Client::factory()->create([
            'ak' => 'test_ak',
            'sk' => 'test_sk'
        ]);

        $this->clientService->updateClientRedisCache($client);

        $redisKey = $this->clientService->getClientRedisKey($client);
        $cachedData = Redis::get($redisKey);

        $this->assertNotNull($cachedData);
        
        $decodedData = json_decode($cachedData, true);
        $this->assertEquals($client->id, $decodedData['client_id']);
        $this->assertEquals($client->client_name, $decodedData['client_name']);
        $this->assertEquals(1, $decodedData['is_active']);
    }

    public function test_client_redis_cache_update()
    {
        $client = Client::factory()->create([
            'ak' => 'test_ak',
            'sk' => 'test_sk'
        ]);

        // Create initial cache
        $this->clientService->updateClientRedisCache($client);

        // Update client
        $client->update(['is_active' => 0]);
        $this->clientService->updateClientRedisCache($client);

        $redisKey = $this->clientService->getClientRedisKey($client);
        $cachedData = Redis::get($redisKey);

        $decodedData = json_decode($cachedData, true);
        $this->assertEquals(0, $decodedData['is_active']);
    }

    public function test_client_redis_cache_deletion()
    {
        $client = Client::factory()->create([
            'ak' => 'test_ak',
            'sk' => 'test_sk'
        ]);

        // Create cache
        $this->clientService->updateClientRedisCache($client);

        // Delete cache
        $this->clientService->deleteClientFromRedis($client);

        $redisKey = $this->clientService->getClientRedisKey($client);
        $cachedData = Redis::get($redisKey);

        $this->assertNull($cachedData);
    }
}
```

---

## API Testing

### Postman Collection
```json
{
  "info": {
    "name": "Gateway Dashboard API",
    "description": "API collection for Gateway Dashboard",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"admin@example.com\",\n  \"password\": \"password\"\n}"
            },
            "url": {
              "raw": "{{base_url}}/api/login",
              "host": ["{{base_url}}"],
              "path": ["api", "login"]
            }
          }
        }
      ]
    },
    {
      "name": "Users",
      "item": [
        {
          "name": "Get All Users",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/users",
              "host": ["{{base_url}}"],
              "path": ["api", "users"]
            }
          }
        },
        {
          "name": "Create User",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              },
              {
                "key": "Authorization",
                "value": "Bearer {{token}}"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"username\": \"testuser\",\n  \"name\": \"Test User\",\n  \"email\": \"test@example.com\",\n  \"password\": \"password123\",\n  \"password_confirmation\": \"password123\",\n  \"role\": \"operator\",\n  \"is_active\": 1\n}"
            },
            "url": {
              "raw": "{{base_url}}/api/users",
              "host": ["{{base_url}}"],
              "path": ["api", "users"]
            }
          }
        }
      ]
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000"
    },
    {
      "key": "token",
      "value": ""
    }
  ]
}
```

### API Test Scripts
```bash
#!/bin/bash
# api_test.sh

BASE_URL="http://localhost:8000/api"
TOKEN=""

# Login and get token
echo "Testing login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}')

TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.data.token')
echo "Token: $TOKEN"

# Test get users
echo "Testing get users..."
curl -s -X GET "$BASE_URL/users" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

# Test create user
echo "Testing create user..."
curl -s -X POST "$BASE_URL/users" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "username": "testuser",
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "operator",
    "is_active": 1
  }' | jq '.'

echo "API tests completed!"
```

---

## Browser Testing

### Laravel Dusk Setup
```bash
# Install Laravel Dusk
composer require --dev laravel/dusk

# Install Dusk
php artisan dusk:install

# Create browser tests
php artisan dusk:make UserManagementTest
```

### Browser Test Example
```php
<?php
// tests/Browser/UserManagementTest.php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/dashboard')
                    ->assertSee('Welcome');
        });
    }

    public function test_user_can_create_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/users')
                    ->clickLink('Create User')
                    ->type('username', 'newuser')
                    ->type('name', 'New User')
                    ->type('email', 'newuser@example.com')
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->select('role', 'operator')
                    ->press('Create User')
                    ->assertPathIs('/users')
                    ->assertSee('User created successfully');
        });
    }

    public function test_user_can_search_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/users')
                    ->type('search', 'John')
                    ->waitForText('John Doe')
                    ->assertSee('John Doe')
                    ->assertDontSee('Jane Smith');
        });
    }
}
```

---

## Performance Testing

### Load Testing with Artillery
```yaml
# artillery.yml
config:
  target: 'http://localhost:8000'
  phases:
    - duration: 60
      arrivalRate: 10
  defaults:
    headers:
      Content-Type: 'application/json'
      Authorization: 'Bearer {{ token }}'

scenarios:
  - name: "API Load Test"
    weight: 100
    flow:
      - post:
          url: "/api/login"
          json:
            email: "admin@example.com"
            password: "password"
          capture:
            - json: "$.data.token"
              as: "token"
      - loop:
          - get:
              url: "/api/users"
          - get:
              url: "/api/clients"
          - get:
              url: "/api/services"
        count: 10
```

### Performance Test Script
```bash
#!/bin/bash
# performance_test.sh

echo "Starting performance tests..."

# Install Artillery if not installed
if ! command -v artillery &> /dev/null; then
    npm install -g artillery
fi

# Run load tests
echo "Running load tests..."
artillery run artillery.yml

# Run stress tests
echo "Running stress tests..."
artillery run artillery-stress.yml

echo "Performance tests completed!"
```

---

## Security Testing

### Security Test Suite
```php
<?php
// tests/Feature/Security/SecurityTest.php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_csrf_protection()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->post('/users', [
            'username' => 'testuser',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
            'is_active' => 1
        ]);

        $response->assertStatus(419); // CSRF token mismatch
    }

    public function test_sql_injection_protection()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $maliciousInput = "'; DROP TABLE users; --";

        $response = $this->actingAs($user)->get("/users?search={$maliciousInput}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => 1]); // Table still exists
    }

    public function test_xss_protection()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $xssPayload = '<script>alert("XSS")</script>';

        $response = $this->actingAs($user)->post('/users', [
            'username' => 'testuser',
            'name' => $xssPayload,
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
            'is_active' => 1
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => $xssPayload // Should be stored as-is, not executed
        ]);
    }

    public function test_authorization_required()
    {
        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }

    public function test_role_based_access()
    {
        $viewer = User::factory()->create(['role' => 'viewer']);
        $operator = User::factory()->create(['role' => 'operator']);

        // Viewer should not be able to create users
        $response = $this->actingAs($viewer)->get('/users/create');
        $response->assertStatus(403);

        // Operator should be able to create users
        $response = $this->actingAs($operator)->get('/users/create');
        $response->assertStatus(200);
    }
}
```

---

## Development Workflow

### Git Workflow
```bash
# Feature development
git checkout -b feature/user-management
git add .
git commit -m "feat: add user management functionality"
git push origin feature/user-management

# Create pull request
# After review and approval, merge to main

# Hotfix
git checkout -b hotfix/fix-login-bug
git add .
git commit -m "fix: resolve login authentication issue"
git push origin hotfix/fix-login-bug
```

### Code Review Checklist
- [ ] Code follows PSR-12 standards
- [ ] All tests pass
- [ ] Code coverage meets requirements
- [ ] No security vulnerabilities
- [ ] Performance impact assessed
- [ ] Documentation updated
- [ ] Database migrations included
- [ ] Environment variables documented

---

## Code Quality

### PHPStan Configuration
```neon
# phpstan.neon
parameters:
    level: 8
    paths:
        - app
    excludePaths:
        - app/Console
        - app/Exceptions
        - app/Http/Middleware
    ignoreErrors:
        - '#Call to an undefined method#'
```

### CodeSniffer Configuration
```xml
<!-- phpcs.xml -->
<?xml version="1.0"?>
<ruleset name="Gateway Dashboard">
    <description>Coding standard for Gateway Dashboard</description>
    
    <file>app</file>
    
    <rule ref="PSR12"/>
    
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*/storage/*</exclude-pattern>
    <exclude-pattern>*/bootstrap/cache/*</exclude-pattern>
</ruleset>
```

### Quality Gates
```bash
#!/bin/bash
# quality_gate.sh

echo "Running quality gates..."

# Run PHPStan
echo "Running PHPStan..."
vendor/bin/phpstan analyse --memory-limit=1G

# Run CodeSniffer
echo "Running CodeSniffer..."
vendor/bin/phpcs

# Run tests
echo "Running tests..."
vendor/bin/phpunit --coverage-html coverage

# Check coverage
COVERAGE=$(vendor/bin/phpunit --coverage-text | grep "Lines" | awk '{print $4}' | sed 's/%//')
if (( $(echo "$COVERAGE < 80" | bc -l) )); then
    echo "Coverage is below 80%: $COVERAGE%"
    exit 1
fi

echo "Quality gates passed!"
```

---

## Debugging

### Debug Configuration
```php
// config/app.php
'debug' => env('APP_DEBUG', false),
'log_level' => env('LOG_LEVEL', 'debug'),
```

### Debug Tools
```bash
# Install debug tools
composer require --dev barryvdh/laravel-debugbar
composer require --dev laravel/telescope

# Publish configurations
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
php artisan telescope:install
```

### Debug Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Debug routes
php artisan route:list

# Debug config
php artisan config:show

# Debug database
php artisan tinker
```

---

## Testing Tools

### Testing Commands
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run tests with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel

# Run specific test
php artisan test --filter=UserTest

# Run browser tests
php artisan dusk

# Run specific browser test
php artisan dusk --filter=UserManagementTest
```

### Test Data Factories
```php
<?php
// database/factories/UserFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['admin', 'operator', 'viewer']),
            'is_active' => 1,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin',
            ];
        });
    }

    public function operator()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'operator',
            ];
        });
    }

    public function viewer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'viewer',
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 0,
            ];
        });
    }
}
```

---

## CI/CD Pipeline

### GitHub Actions Workflow
```yaml
# .github/workflows/tests.yml
name: Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      postgres:
        image: postgres:13
        env:
          POSTGRES_PASSWORD: password
          POSTGRES_DB: gateway_test
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

      redis:
        image: redis:6
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: pgsql, redis, gd, zip, mbstring, openssl, curl, json, bcmath, xml, tokenizer, fileinfo, dom, simplexml
        coverage: xdebug

    - name: Setup Node.js
      uses: actions/setup-node@v3
      with:
        node-version: '16'

    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"

    - name: Install Composer Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

    - name: Install NPM Dependencies
      run: npm ci

    - name: Generate key
      run: php artisan key:generate

    - name: Directory Permissions
      run: chmod -R 777 storage bootstrap/cache

    - name: Create Database
      run: |
        mkdir -p database
        touch database/database.sqlite

    - name: Execute tests (Unit and Feature tests) via PHPUnit
      env:
        DB_CONNECTION: pgsql
        DB_DATABASE: gateway_test
        DB_USERNAME: postgres
        DB_PASSWORD: password
        DB_HOST: localhost
        REDIS_HOST: localhost
      run: php artisan test --coverage

    - name: Upload coverage to Codecov
      uses: codecov/codecov-action@v3
      with:
        file: ./coverage/lcov.info
        flags: unittests
        name: codecov-umbrella
        fail_ci_if_error: false
```

---

This comprehensive testing and development documentation covers all aspects of testing, development workflows, and quality assurance for the Gateway Dashboard application.
