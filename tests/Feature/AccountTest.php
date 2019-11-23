<?php

namespace Tests\Feature;

use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountTest extends TestCase
{
    /** @var \App\Entities\User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function update_profile_info()
    {
        $this->actingAs($this->user)
            ->patchJson('/account/profile', [
                'name' => 'Test User',
                'email' => 'test@test.app',
            ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Test User',
            'email' => 'test@test.app',
        ]);
    }

    /** @test */
    public function can_not_update_profile_required()
    {
        $userExisting = factory(User::class)->create();

        $this->actingAs($this->user)
            ->patchJson('/account/profile', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    /** @test */
    public function can_not_update_profile_email_already_taken()
    {
        $userExisting = factory(User::class)->create();

        $this->actingAs($this->user)
            ->patchJson('/account/profile', [
                'name' => 'Test User',
                'email' => $userExisting->email,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function update_password()
    {
        $this->actingAs($this->user)
            ->patchJson('/account/password', [
                'password' => 'updated-password',
                'password_confirmation' => 'updated-password',
            ])
            ->assertSuccessful();

        $this->assertTrue(Hash::check('updated-password', $this->user->password));
    }

    /** @test */
    public function can_not_update_password_unconfirmed()
    {
        $this->actingAs($this->user)
            ->patchJson('/account/password', [
                'password' => '123',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function can_not_update_password_minimum()
    {
        $this->actingAs($this->user)
            ->patchJson('/account/password', [
                'password' => '123',
                'password_confirmation' => '123',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
