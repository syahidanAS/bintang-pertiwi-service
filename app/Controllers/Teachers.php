<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TeachersModel;
use CodeIgniter\API\ResponseTrait;

class Teachers extends ResourceController
{
	public function index(){
		$this->model = new TeachersModel();
		$data = $this->model->select('*')->findAll();

		return $this->respondCreated($data, 200);
	}
	public function createTeacher(){
		$this->model = new TeachersModel();

		$name = $this->request->getVar('name');
		$major = $this->request->getVar('major');
		$campus = $this->request->getVar('campus');
		$file = $this->request->getFile('image');
		$title = $file->getName();

		$filesize = number_format(filesize($file) / 1048576, 1);

		$temp = explode(".", $title);
		$newfilename = round(microtime(true)) . '.' . end($temp);

		// $countHero = $this->model->select('*')->where('flag', $flag)->countAllResults();


		if ($filesize >= 3) {
			$response = [
				'status' => 500,
				'error' => true,
				'message' => 'Ukuran foto harus dibawah 3 MB'
			];
		} else {
			if ($file->move("images/teachers", $newfilename)) {
				$fileModel = new TeachersModel();
				$data = [
					"name" => $name,
					"major" => $major,
					"campus" => $campus,
					"image_filename" => $newfilename,
					"image_url" => base_url() . "/images/teachers/" . $newfilename,
				];
				if ($fileModel->insert($data)) {
					$response = [
						'status' => 201,
						'error' => false,
						'message' => 'Berhasil upload'
					];
					return $this->respondCreated($response, 201);
				} else {
					$response = [
						'status' => 409,
						'error' => true,
						'message' => 'Failed to save image',
					];
					return $this->respondCreated($response, 409);
				}
			} else {
				$response = [
					'status' => 409,
					'error' => true,
					'message' => 'Failed to upload image',
				];
				return $this->respondCreated($response, 409);
			}
		}



		
		
	}
	public function updateTeacher(){
		$id = $this->request->getVar('id');
		$name = $this->request->getVar('name');
		$major = $this->request->getVar('major');
		$campus = $this->request->getVar('campus');
		$file = $this->request->getFile('image');
		
		$filesize = number_format(filesize($file) / 1048576, 1);



		if(!$file){
			$data = [
				"name" => $name,
				"major" => $major,
				"campus" => $campus
			];
			$fileModel = new TeachersModel();
			$result = $fileModel->update($id,$data);
			
			if($result){
				$response = [
					'message' => 'success',
					'error' => false,
					'data' => $data
				];
				return $this->respondUpdated($response);
			}else{
				$response = [
					'message' => 'internal server error',
					'error' => true
				];
				return $this->respond($response, 500);
			}		
		}else{
			if($filesize > 3){
				$response = [
					'message' => 'image to large',
					'error' => true
				];
				return $this->respond($response, 409);
			}else{
				$image_filename = $file->getName();
				$temp = explode(".", $image_filename);
				$newfilename = round(microtime(true)) . '.' . end($temp);
				$fileModel = new TeachersModel();

				$this->model = new TeachersModel();
				$findImage = $this->model->select('*')->where('id', $id)->first();
				$filename = $findImage['image_filename'];

				$fileDir = 'images/teachers/' . $filename;
				$unlink = unlink($fileDir);

				$move = $file->move("images/teachers", $newfilename);

				if($unlink && $move){
					$data = [
						"name" => $name,
						"major" => $major,
						"campus" => $campus,
						"image_filename" => $newfilename,
						"image_url" => base_url() . "/images/teachers/" . $newfilename,
					];

					$fileModel = new TeachersModel();
					$result = $fileModel->update($id,$data);

					return $this->respondUpdated("Berhasil");
				}else{
					return $this->respond("Gagal", 409);
				}
				

				// $data = [
				// 	"name" => $name,
				// 	"major" => $major,
				// 	"campus" => $campus,
				// 	"image_filename" => $newfilename,
				// 	"image_url" => base_url() . "/images/teachers/" . $newfilename,
				// ];



				
			}
		}

	}
	public function deleteTeacher(){
		$id = $this->request->getVar('id');
		$fileDir = 'images/teachers/' . $this->request->getVar('image_filename');
		$unlink = unlink($fileDir);

		$fileModel = new TeachersModel();


		if ($unlink) {
			$fileModel->delete(['id' => $id]);
			$response = [
				'status' => 200,
				'error' => true,
				'message' => 'Teacher Deleted!',
				'data' => []
			];
			return $this->respondCreated($response, 200);
		} else {
			return $this->respondCreated("Failed", 409);
		}
	}
	public function showTeacherInfo(){
		
	}
}
