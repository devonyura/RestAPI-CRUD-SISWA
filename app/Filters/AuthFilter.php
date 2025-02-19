<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;
use App\Helpers\JwtHelper;
use Exception;

class AuthFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		// Routes yang dikecualikan dari JWT Auth
		$excludedRoutes = ['api/auth/login', 'api/auth/register'];

		// Ambil URI saat ini
		$currentURI = service('request')->getPath();

		// Jika route termasuk dalam pengecualian, tidak perlu validasi JWT
		if (in_array($currentURI, $excludedRoutes)) {
			return;
		}

		// Validasi token JWT // Ambil header Authorization
		$authHeader = $request->getHeader('Authorization');
		if (!$authHeader) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Token tidak ditemukan. Silakan login untuk mendapatkan token.'
				])
				->setStatusCode(401);
		}

		// Ambil nilai token dari header
		$tokenParts = explode(' ', $authHeader->getValue());

		// Pastikan formatnya "Bearer <token>"
		if (count($tokenParts) !== 2 || strtolower($tokenParts[0]) !== 'bearer') {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Format token tidak valid. Gunakan format "Bearer <token>".'
				])
				->setStatusCode(401);
		}

		$token = $tokenParts[1];

		try {
			$jwtHelper = new JwtHelper();
			$user = $jwtHelper->validateJWT($token);

			// Simpan user ke request untuk dipakai di controller (Opsional)
			$request->user = $user;

		} catch (Exception $e) {

			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => $e->getMessage()
				])
				->setStatusCode(401);
		}

	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Tidak digunakan
	}
}
