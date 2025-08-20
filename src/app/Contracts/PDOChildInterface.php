<?php

namespace App\Contracts;

interface PDOChildInterface
{
    public function __construct($dsn, $username = null, $password = null);
}