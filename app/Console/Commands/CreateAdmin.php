<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create
                            {--name= : Nama admin}
                            {--email= : Email admin}
                            {--password= : Password (min. 8 karakter). Lebih aman dikosongkan supaya ditanya interaktif}';

    protected $description = 'Buat akun admin baru (tabel admins) untuk login di /admin';

    public function handle(): int
    {
        $data = [
            'name' => $this->option('name') ?: $this->ask('Nama admin'),
            'email' => $this->option('email') ?: $this->ask('Email admin'),
            'password' => $this->option('password') ?: $this->secret('Password (min. 8 karakter)'),
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        Admin::create($data); // password di-hash oleh cast 'hashed'

        $this->info("Admin {$data['email']} berhasil dibuat. Masuk lewat /admin.");

        return self::SUCCESS;
    }
}
