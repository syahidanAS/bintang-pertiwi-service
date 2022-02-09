<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class Login extends ResourceController
{
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	use ResponseTrait;
	public function index()
	{


		helper(['form']);
		$rules = [
			'email' => 'required|valid_email',
			'password' => 'required|min_length[6]'
		];
		if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
		$model = new UserModel();

		$user = $model->where("email", $this->request->getVar('email'))->first();
		if (!$user) return $this->failUnauthorized('Account not found!', 401);
		$verify = password_verify($this->request->getVar('password'), $user['password']);
		if (!$verify) return $this->failUnauthorized('Account not found!', 401);

		$key = getenv('TOKEN_SECRET');
		$issuer_claim = "THE_CLAIM";
		$audience_claim = "THE_AUDIENCE";
		$issuedat_claim = time();
		$notbefore_claim = $issuedat_claim + 10;
		$expire_claim = $issuedat_claim + 172800;

		$payload = array(
			"iss" => $issuer_claim,
			"aud" => $audience_claim,
			"iat" => $issuedat_claim,
			"nbf" => $notbefore_claim,
			"exp" => $expire_claim,
			"uid" => $user['id'],
			"email" => $user['email']
		);

		$token = JWT::encode($payload, $key, 'HS256');
		$response = [
			'status' => 'authorized',
			'message' => 'success',
			'user' => [
				'uid' => $user['id'],
				'email' => $user['email'],
				'role' => $user['role'],
				'token' => $token,
			]
		];
		return $this->respond($response, 200);
	}
}
