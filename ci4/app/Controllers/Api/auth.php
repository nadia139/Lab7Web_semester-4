<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        $login = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $model = new UserModel();
        $user = $model->where('useremail', $login)->first();

        if (!$user) {
            $user = $model->where('username', $login)->first();
        }

        if ($user) {
            // ✅ PAKAI password_verify() untuk hash bcrypt
            if (password_verify($password, $user['userpassword'])) {
                return $this->respond([
                    'status' => 200,
                    'error' => null,
                    'messages' => 'Login Berhasil',
                    'data' => [
                        'id' => $user['id'],
                        'email' => $user['useremail'],
                        'username' => $user['username'] ?? $user['useremail'],
                        'token' => base64_encode("TOKEN-SECRET-" . ($user['username'] ?? $user['useremail']))
                    ]
                ], 200);
            }
        }

        return $this->failUnauthorized('Email atau Password yang Anda masukkan salah.');
    }
}