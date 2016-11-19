<?php

namespace Tests\Components\Users;

use App\Users\Exceptions\CannotCreateUser;
use App\Users\NewUserData;
use App\Users\User;
use App\Users\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestsRepository;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations, TestsRepository;

    /**
     * @var \App\Users\UserRepository
     */
    protected $repo = UserRepository::class;

    /** @test */
    function find_by_username()
    {
        $this->createUser();

        $this->assertInstanceOf(User::class, $this->repo->findByUsername('johndoe'));
    }

    /** @test */
    function find_by_email_address()
    {
        $this->createUser();

        $this->assertInstanceOf(User::class, $this->repo->findByEmailAddress('john@example.com'));
    }

    /** @test */
    function we_can_create_a_user()
    {
        $this->assertInstanceOf(User::class, $this->repo->create(new NewUserData(
            'John Doe',
            'john@example.com',
            'johndoe',
            'password'
        )));
    }

    /** @test */
    function we_cannot_create_a_user_with_the_same_email_address()
    {
        $this->expectException(CannotCreateUser::class);

        $this->repo->create(new NewUserData('John Doe', 'john@example.com', 'johndoe', 'password'));
        $this->repo->create(new NewUserData('John Foo', 'john@example.com', 'johnfoo', 'password'));
    }

    /** @test */
    function we_cannot_create_a_user_with_the_same_username()
    {
        $this->expectException(CannotCreateUser::class);

        $this->repo->create(new NewUserData('John Doe', 'john@example.com', 'johndoe', 'password'));
        $this->repo->create(new NewUserData('John Doe', 'john.doe@example.com', 'johndoe', 'password'));
    }

    /** @test */
    function we_can_update_a_user()
    {
        $user = $this->createUser();

        $user = $this->repo->update($user, ['username' => 'foo', 'name' => 'bar']);

        $this->assertEquals('foo', $user->username());
        $this->seeInDatabase('users', ['username' => 'foo', 'name' => 'bar']);
    }
}