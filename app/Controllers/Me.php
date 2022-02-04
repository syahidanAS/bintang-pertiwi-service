<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\ProfileModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Me extends ResourceController
{
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	use ResponseTrait;
	public function index()
	{
		$key = getenv('TOKEN_SECRET');

		 $header = $this->request->getServer('HTTP_AUTHORIZATION');
		 if(!$header) return $this->failUnauthorized('Token Required');
		 $token = explode(' ', $header)[1];
		 $decoded = JWT::decode($token, new Key($key, 'HS256'));
		try {
			
			$this->model = new ProfileModel();
			$data = $this->model->select('users.email,role, profiles.full_name,address,phone')->join('users', 'profiles.uid = users.id')
				->where('uid', $decoded->uid)->findAll();
			$response = [
				'message' => 'autorizhed',
				'user' =>$data
			];
			return $this->respond($data, 200);
		} catch (\Exception $e) {
			$response = [
				'message' => $data,
			];
			return $this->respond($response, 401);
		}
	}
}
