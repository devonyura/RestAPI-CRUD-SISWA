<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;

class JwtHelper 
{
	function generateJWT($data)
	{
		$issuedAt = time();
		$expireAt = $issuedAt + JWTConfig::$tokenExpiry;
	
		$payload = [
			'iat' => $issuedAt,
			'exp' => $expireAt,
			'data' => $data
		];
	
		return JWT::encode($payload, JWTConfig::$secretKey, JWTConfig::$algorithm);
	}
	
	function validateJWT($token)
	{
		try {
			$decoded = JWT::decode($token, new Key(JWTConfig::$secretKey, JWTConfig::$algorithm));
			return (array) $decoded->data;
		} catch (Exception $e) {
			return false;
		}
	}
}

