<?php

namespace Tests\Feature;

use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_register()
    {
        $this->postJson('/auth/register', [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }

    /** @test */
    public function can_not_register_with_existing_email()
    {
        factory(User::class)->create(['email' => 'test@test.app']);

        $this->postJson('/auth/register', [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function can_not_register_empty_name()
    {
        $this->postJson('/auth/register', [
            'email' => 'test@test.app',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function can_not_register_password_minimum()
    {
        $this->postJson('/auth/register', [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
