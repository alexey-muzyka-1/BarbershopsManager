<?php

declare(strict_types=1);

namespace App\Model\User;

class UserErrorConstants
{
    public const USER_EXISTS_ERROR = 'User already exists.';
    public const USER_ALREADY_CONFIRMED = 'User is already confirmed.';
    public const USER_IS_NOT_ACTIVE = 'User is not active.';
    public const USER_IS_ALREADY_ACTIVE = 'User is already active.';
    public const USER_IS_ALREADY_BLOCKED = 'User is already blocked.';
    public const USER_IS_NOT_FOUND = 'User is not found.';
    public const USER_USERNAME_IS_NOT_FOUND = 'User could not be found.';
    public const USER_WITH_THIS_EMAIL_EXISTS = 'User with this email already exists.';

    public const USER_INCORRECT_EMAIL = 'Incorrect email.';
    public const USER_EMAIL_IS_NOT_SPECIFIED = 'Email is not specified.';
    public const USER_EMAIL_IS_ALREADY_IN_USE = 'Email is already in use.';

    public const USER_INCORRECT_CONFIRM_TOKEN = 'Incorrect or already confirmed token.';
    public const USER_TOKEN_IS_EXPIRED = 'Reset token is expired.';
    public const USER_INCORRECT_TOKEN = 'Incorrect changing token.';

    public const USER_HASH_ERROR = 'Unable to generate hash.';

    public const USER_NETWORK_ATTACHED = 'Network is already attached.';
    public const USER_NETWORK_IS_NOT_ATTACHED = 'Network is not attached.';

    public const USER_RESET_IS_REQUESTED = 'Resetting is already requested.';
    public const USER_RESET_IS_NOT_REQUESTED = 'Resetting is not requested.';

    public const USER_UNDEFINED_ROLE = 'Undefined role exception.';
    public const USER_EQUALS_ROLES = 'Role is already selected.';
    public const USER_CHANGE_ROLE_FOR_YOURSELF = 'Unable to change role for yourself.';

    public const USER_INVALID_USER_CLASS = 'Invalid user class.';

    public const USER_CHANGING_IS_NOT_REQUESTED = 'Changing is not requested.';

    public const USER_LAST_IDENTITY = 'Unable to detach the last identity.';
}
