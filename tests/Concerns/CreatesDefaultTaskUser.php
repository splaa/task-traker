<?php

namespace Tests\Concerns;

use App\User;

trait CreatesDefaultTaskUser
{
    protected User $defaultTaskUser;

    protected function createDefaultTaskUser(): void
    {
        $user = new User([
            'first_name' => 'Default',
            'last_name' => 'Task User',
            'email' => 'default-task-user@example.com',
            'password' => bcrypt('password'),
        ]);

        // The tasks migration defaults its foreign key to 1. Set that fixture
        // explicitly: MySQL does not roll back AUTO_INCREMENT with transactions.
        // This bypasses no production mass-assignment rules or model guards.
        $user->user_id = 1;
        $user->save();
        $this->defaultTaskUser = $user;
    }
}
