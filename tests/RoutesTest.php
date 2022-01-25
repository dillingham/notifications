<?php

namespace HeadlessLaravel\Notifications\Tests;

use HeadlessLaravel\Notifications\Tests\Fixtures\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Route;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Route::get('login', 'LoginController@index')->name('login');
    }

    public function test_all_routes()
    {
        Route::notifications();
        $user = $this->authUser();

        DatabaseNotification::create([
            'id'              => '123',
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'type'            => 'invoice',
            'read_at'         => now(),
            'data'            => [],
        ]);

        $this->get('/notifications')->assertOk();
        $this->get('/notifications/count')->assertOk();
        $this->get('/notifications/read')->assertOk();
        $this->get('/notifications/unread')->assertOk();
        $this->post('/notifications/123/mark-as-read')->assertOk();
        $this->delete('/notifications/123')->assertOk();
        $this->post('/notifications/clear')->assertOk();
    }

    public function test_routes_only()
    {
        Route::notifications()->only(['all', 'count']);
        $this->authUser();

        $this->get('/notifications')->assertOk();
        $this->get('/notifications/count')->assertOk();

        $this->get('/notifications/read')->assertNotFound();
        $this->get('/notifications/unread')->assertNotFound();
        $this->post('/notifications/clear')->assertNotFound();
        $this->post('/notifications/123/mark-as-read')->assertNotFound();
        $this->delete('/notifications/123')->assertNotFound();
    }

    public function test_routes_except()
    {
        Route::notifications()->except(['markAsRead', 'destroy']);
        $this->authUser();

        $this->get('/notifications')->assertOk();
        $this->get('/notifications/count')->assertOk();
        $this->get('/notifications/read')->assertOk();
        $this->get('/notifications/unread')->assertOk();
        $this->post('/notifications/clear')->assertOk();

        $this->post('/notifications/123/mark-as-read')->assertNotFound();
        $this->delete('/notifications/123')->assertNotFound();
    }
}
