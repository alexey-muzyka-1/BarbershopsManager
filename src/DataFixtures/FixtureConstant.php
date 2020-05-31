<?php

declare(strict_types=1);

namespace App\DataFixtures;

class FixtureConstant
{
    public const FAKE_ACTIVE_USERS_AMOUNT = 25;
    public const FAKE_BARBERSHOPS_AMOUNT = 30;

    public const USER_ADMIN = 'user_user_admin';
    public const USER_BY_FAKER = 'user_user_by_faker_';

    public const COMPANY_1 = 'work_group_manager';
    public const COMPANY_2 = 'work_senior_developer';
    public const COMPANY_3 = 'work_middle_developer';
    public const COMPANY_4 = 'work_junior_developer';

    public const ALL_COMPANIES = [
        self::COMPANY_1,
        self::COMPANY_2,
        self::COMPANY_3,
        self::COMPANY_4,
    ];

    public static function getRundomCompanyReference()
    {
        return self::getRundomFromArray(FixtureConstant::ALL_COMPANIES);
    }

    public static function getRundomFromArray(array $data)
    {
        $rand = array_rand($data);

        return $data[$rand];
    }
}
