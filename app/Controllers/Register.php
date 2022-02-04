<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\ProfileModel;

class Register extends ResourceController
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

		$model = new UserModel();
		$user = $model->where("email", $this->request->getVar('email'))->first();
		if (!$user) {
			$rules = [
				'email' => 'required',
				'password' => 'required|min_length[6]',
				'full_name' => 'required',
				'address' => 'required',
				'phone' => 'required'
			];
			if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

			$dataUser = [
				'email' => $this->request->getVar('email'),
				'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
				'role' => $this->request->getVar('role'),
				'is_member' => $this->request->getVar('is_member'),
				'is_verified' => $this->request->getVar('is_verified')
			];
			$modelUser = new UserModel();
			$registered1 = $modelUser->save($dataUser);
			$uid = $modelUser->getInsertID();

			$dataProfile = [
				'full_name' => $this->request->getVar('full_name'),
				'address' => $this->request->getVar('address'),
				'phone' => $this->request->getVar('phone'),
				'uid' => $uid
			];
			$modelProfile = new ProfileModel();
			$registered2 = $modelProfile->save($dataProfile);



			if ($registered1 && $registered2) {
				return $this->respondCreated([
					'status' => 'created',
					'message' => 'success'
				]);
			} else {
				return $this->respond([
					'status' => 'failed',
					'message' => 'something went wrong!'
				]);
			}
		} else {
			$response = [
				'message' => 'Email already taken!',
			];
			return $this->respond($response, 409);
		}
	}
}
