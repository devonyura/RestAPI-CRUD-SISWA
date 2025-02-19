<?php

namespace App\Controllers\Auth;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use App\Helpers\JwtHelper;

class AuthController extends ResourceController
{
	public function register()
	{
		$rules = [
			'username' => 'required|is_unique[users.username]',
			'password' => 'required|min_length[6]'
		];

    $data = $this->request->getJSON();

		$userData = [
			'username' => $data->username,
			'password' => password_hash($data->password, PASSWORD_DEFAULT),
			'role' => 'admin' // Default role admin
		];
			
		if (!$this->validate($rules)) {
			return $this->failValidationErrors($this->validator->getErrors());
		}

    $model = new UserModel();
		$model->insert($userData);

		return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Registrasi Berhasil!',
        'data' => $userData
    ]);
	}

	public function login()
	{
		$model = new UserModel();
		$jwtHelper = new JwtHelper();
		$data = $this->request->getJSON();

		$user = $model->where('username', $data->username)->first();

		if (!$user || !password_verify($data->password, $user['password'])) {
			return $this->failUnauthorized('Username atau password salah');
		}

		// Generate JWT
		$token = $jwtHelper->generateJWT(['id' => $user['id'], 'username' => $user['username'],'role' => $user['role']]);

		return $this->respond(['token' => $token]);
	}
}
