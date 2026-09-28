<?php

namespace App\Actions\Fortify;

use App\Actions\Teams\CreateTeam;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function __construct(private CreateTeam $createTeam)
    {
        //
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $nameParts = preg_split('/\s+/', trim($input['name']), -1, PREG_SPLIT_NO_EMPTY);
            $firstName = array_shift($nameParts) ?: $input['name'];
            $lastName = count($nameParts) > 0 ? array_pop($nameParts) : $firstName;

            $user = User::create([
                'name' => $input['name'],
                'first_name' => $firstName,
                'middle_name' => count($nameParts) > 0 ? implode(' ', $nameParts) : null,
                'last_name' => $lastName,
                'email' => $input['email'],
                'password' => $input['password'],
                'role' => 'mechanic',
            ]);

            $this->createTeam->handle($user, $user->name."'s Team", isPersonal: true);

            return $user;
        });
    }
}
