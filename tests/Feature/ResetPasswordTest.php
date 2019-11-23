<?php

namespace Tests\Feature;

use App\Entities\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /** @var \App\Entities\User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test
     */
    public function can_not_request_empty_email()
    {
        $this->postJson('/password/email', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test
     */
    public function can_not_request_random_email()
    {
        $this->postJson('/password/email', [
            'email' => 'random@test.org',
        ])
            ->assertStatus(400)
            ->assertJsonStructure(['email']);
    }

    /** @test
     */
    public function can_request_email_reset_password()
    {
        Notification::fake();

        $this->postJson('/password/email', [
            'email' => $this->user->email,
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['status'])
            ->assertJson(['status' => 'We have e-mailed your password reset link!']);

        Notification::assertSentTo(
            [$this->user], ResetPassword::class
        );
    }

    /** @test
     */
    public function can_not_reset_empty_required()
    {
        $this->postJson('/password/reset', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['token', 'email', 'password']);
    }

    /** @test
     */
    public function can_not_reset_invalid_email()
    {
        DB::table('password_resets')->insert([
            'email'      => $this->user->email,
            'token'      => Hash::make('token-here'),
            'created_at' => now(),
        ]);

        $this->postJson('/password/reset', [
            'email' => 'random@test.org',
            'token' => 'token-here',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertStatus(400)
            ->assertJsonStructure(['email']);
    }

    /** @test
     */
    public function can_not_reset_invalid_token()
    {
        DB::table('password_resets')->insert([
            'email'      => $this->user->email,
            'token'      => Hash::make('token-here'),
            'created_at' => now(),
        ]);

        $this->postJson('/password/reset', [
            'email' => $this->user->email,
            'token' => 'invalid-token-here',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertStatus(400)
            ->assertJsonStructure(['email']);
    }

    /** @test
     */
    public function can_reset()
    {
        DB::table('password_resets')->insert([
            'email'      => $this->user->email,
            'token'      => Hash::make('token-here'),
            'created_at' => now(),
        ]);

        $this->postJson('/password/reset', [
            'email' => $this->user->email,
            'token' => 'token-here',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['status']);
    }
}
