<?php

namespace Tests\Feature;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login()
    {
        $this->seed(AdminSeeder::class);

        // api_token column has a value now
        $response = $this->post('api/admin/login', [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ])->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'code',
                    'message',
                    'validation',
                    'data' => [
                        'admin',
                    ],
                ]
            );

        $admin = Admin::first();

        $response->assertJson([
            'code' => Response::HTTP_OK,
            'message' => 'Logged in!',
            'validation' => null,
            'data' => [
                'admin' => [
                    "id" => "$admin->id",
                    "name" => "$admin->name",
                    "email" => "$admin->email",
                    "api_token" => "$admin->api_token",
                ],
            ],
        ]);

    }

    // public function test_login_failed_with_an_email_that_does_not_exist_in_admins_table()
    // {
    //     $response = $this->post('/login-post', [
    //         'email' => 'invalid@example.com',
    //         'password' => 'invalid_password',
    //     ]);

    //     // the email does not exist in the admins table
    //     $response->assertSessionHasErrors(['email']);
    // }

    // public function test_login_failed_with_wrong_password()
    // {
    //     $this->seed(AdminSeeder::class);

    //     $response = $this->post('/login-post', [
    //         'email' => 'admin@admin.com',
    //         'password' => 'pass',
    //     ]);

    //     $this->assertEquals('Bad credentials', session('error'));
    //     $response->assertRedirect(route('admin.login'));
    // }

    // public function test_logout_successfully()
    // {
    //     $response = $this->post('/logout');
    //     $response->assertRedirect('/login');
    // }
}
