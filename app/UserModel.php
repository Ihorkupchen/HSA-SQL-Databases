<?php

readonly class UserModel
{
    public function __construct(
            public string $name,
            public string $email,
            public string $dateOfBirth
    ) {
    }
}