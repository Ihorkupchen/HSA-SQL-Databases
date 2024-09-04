<?php

require 'UserModel.php';

class UserFactory
{
    public function randomUser(): UserModel
    {
        $name = 'User' . uniqid();
        $email = $name . '@example.com';
        $dateOfBirth = $this->getRandomDate();

        return new UserModel($name, $email, $dateOfBirth);
    }

    private function getRandomDate(): string
    {
        $start = strtotime('1924-01-01');
        $end = strtotime('2006-12-31');
        $timestamp = mt_rand($start, $end);

        return date("Y-m-d", $timestamp);
    }
}