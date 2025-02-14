<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		// Izinkan akses dari semua origin
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');

		// Handle preflight request
		if ($request->getMethod() === 'OPTIONS') {
			exit;
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		return $response;
	}
}
