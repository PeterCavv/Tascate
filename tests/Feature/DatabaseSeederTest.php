<?php

use App\Models\User;
use App\Models\Tasca;
use App\Models\Post;
use App\Models\Review;
use App\Models\Picture;
use App\Models\Customer;
use App\Models\Friendship;
use App\Models\Reservation;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\Role as UseRole;

uses(RefreshDatabase::class);

it('seeds the database', function () {
    //Assert
    $this->assertDatabaseCount(User::class, 0);
    $this->assertDatabaseCount(Tasca::class, 0);
    $this->assertDatabaseCount(Post::class, 0);
    $this->assertDatabaseCount(Review::class, 0);
    $this->assertDatabaseCount(Picture::class, 0);
    $this->assertDatabaseCount(Customer::class, 0);
    $this->assertDatabaseCount(Friendship::class, 0);
    $this->assertDatabaseCount(Reservation::class, 0);
    $this->assertDatabaseCount(Role::class, 0);

    //Act
    $this->artisan('db:seed');

    //Assert
    $this->assertDatabaseCount(User::class, 11);
    $this->assertDatabaseCount(Tasca::class, 4);
    $this->assertDatabaseCount(Post::class, 10);
    $this->assertDatabaseCount(Review::class, 20);
    $this->assertDatabaseCount(Picture::class, 20);
    $this->assertDatabaseCount(Customer::class, 10);
    $this->assertDatabaseCount(Friendship::class, 2);
    $this->assertDatabaseCount(Reservation::class, 15);
    $this->assertDatabaseCount(Role::class, 5);
})->skip('falla intermitentemente');

it('seeds the database only once', function () {
    //Assert
    $this->assertDatabaseCount(User::class, 0);
    $this->assertDatabaseCount(Tasca::class, 0);
    $this->assertDatabaseCount(Post::class, 0);
    $this->assertDatabaseCount(Review::class, 0);
    $this->assertDatabaseCount(Picture::class, 0);
    $this->assertDatabaseCount(Customer::class, 0);
    $this->assertDatabaseCount(Friendship::class, 0);
    $this->assertDatabaseCount(Reservation::class, 0);
    $this->assertDatabaseCount(Role::class, 0);

    //Act
    $this->artisan('db:seed');
    $this->artisan('db:seed');

    //Assert
    $this->assertDatabaseCount(User::class, 11);
    $this->assertDatabaseCount(Tasca::class, 4);
    $this->assertDatabaseCount(Post::class, 10);
    $this->assertDatabaseCount(Review::class, 20);
    $this->assertDatabaseCount(Picture::class, 20);
    $this->assertDatabaseCount(Customer::class, 10);
    $this->assertDatabaseCount(Friendship::class, 2);
    $this->assertDatabaseCount(Reservation::class, 15);
    $this->assertDatabaseCount(Role::class, 5);
})->skip('falla intermitentemente');


it('adds all roles', function () {
    // Assert
    $this->assertDatabaseCount(Role::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');

    $roles = UseRole::cases();
    $this->assertDatabaseCount(Role::class, count($roles));

    // Assert
    foreach($roles as $role){
        $this->assertDatabaseHas(Role::class, [
            'name' => $role->value
        ]);
    }
});

it('adds roles only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=RoleSeeder');

    // Assert
    $roles = UseRole::cases();
    $this->assertDatabaseCount(Role::class, count($roles));
});

it('creates default admin user', function () {
    // Assert
    $this->assertDatabaseCount(User::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');

    // Assert
    $this->assertDatabaseCount(User::class, 11);
    // porque se crea un usuario admin por defecto
    // y 10 mas aleatorios
    $this->assertDatabaseHas(User::class, [
        'name' => 'Admin Test User',
        'email' => 'test@example.com',
    ]);

    $admin = User::where('email', 'test@example.com')->first();
    expect($admin->hasRole(UseRole::ADMIN->value))->toBeTrue();
});

it('creates default admin user only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=UserSeeder');

    // Assert
    // porque se crea un usuario admin por defecto
    // y 10 mas aleatorios
    $this->assertDatabaseCount(User::class, 11);
});

it('creates tascas', function () {
    // Assert
    $this->assertDatabaseCount(Tasca::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=TascaSeeder');

    // Assert
    $this->assertDatabaseCount(Tasca::class, 4);
});

it('creates tascas only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=TascaSeeder');
    $this->artisan('db:seed --class=TascaSeeder');

    // Assert
    $this->assertDatabaseCount(Tasca::class, 4);
});

it('creates customers', function () {
    // Assert
    $this->assertDatabaseCount(Customer::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');

    // Assert
    $this->assertDatabaseCount(Customer::class, 10);
});

it('creates customers only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');

    // Assert
    $this->assertDatabaseCount(Customer::class, 10);
});

it('creates reviews', function () {
    // Assert
    $this->assertDatabaseCount(Review::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=TascaSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=ReviewSeeder');

    // Assert
    $this->assertDatabaseCount(Review::class, 20);
});

it('creates reviews only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=TascaSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=ReviewSeeder');
    $this->artisan('db:seed --class=ReviewSeeder');

    // Assert
    $this->assertDatabaseCount(Review::class, 20);
});

it('creates reservations', function () {
    // Assert
    $this->assertDatabaseCount(Reservation::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=TascaSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=ReservationSeeder');

    // Assert
    $this->assertDatabaseCount(Reservation::class, 15);
});

it('creates reservations only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=TascaSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=ReservationSeeder');
    $this->artisan('db:seed --class=ReservationSeeder');

    // Assert
    $this->assertDatabaseCount(Reservation::class, 15);
});

it('creates posts', function () {
    // Assert
    $this->assertDatabaseCount(Post::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=PostSeeder');

    // Assert
    $this->assertDatabaseCount(Post::class, 10);
});

it('creates posts only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=PostSeeder');
    $this->artisan('db:seed --class=PostSeeder');

    // Assert
    $this->assertDatabaseCount(Post::class, 10);
});

it('creates pictures', function () {
    // Assert
    $this->assertDatabaseCount(Picture::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=PostSeeder');
    $this->artisan('db:seed --class=PictureSeeder');

    // Assert
    $this->assertDatabaseCount(Picture::class, 20);
});

it('creates pictures only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=PostSeeder');
    $this->artisan('db:seed --class=PictureSeeder');
    $this->artisan('db:seed --class=PictureSeeder');

    // Assert
    $this->assertDatabaseCount(Picture::class, 20);
});

it('creates friendships', function () {
    // Assert
    $this->assertDatabaseCount(Friendship::class, 0);

    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=FriendshipSeeder');

    // Assert
    $this->assertDatabaseCount(Friendship::class, 2);
});

it('creates friendships only once', function () {
    // Act
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=CustomerSeeder');
    $this->artisan('db:seed --class=FriendshipSeeder');
    $this->artisan('db:seed --class=FriendshipSeeder');

    // Assert
    $this->assertDatabaseCount(Friendship::class, 2);
});
