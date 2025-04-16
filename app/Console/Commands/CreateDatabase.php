<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class CreateDatabase extends Command
{
    protected $signature = 'db:create';
    protected $description = 'Create a new database';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        try {
            $pdo = new PDO(
                sprintf(
                    'mysql:host=%s;port=%s',
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.port')
                ),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password')
            );

            $pdo->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS %s CHARACTER SET %s COLLATE %s;',
                $database,
                $charset,
                $collation
            ));

            $this->info(sprintf('Successfully created database %s', $database));
        } catch (\Exception $e) {
            $this->error(sprintf('Failed to create database: %s', $e->getMessage()));
        }
    }
} 