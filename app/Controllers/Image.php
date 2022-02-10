<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ImagesModel;
use CodeIgniter\API\ResponseTrait;

class Image extends ResourceController
{

	//Upload hero images
	public function uploadHeroImage()
	{
		$this->model = new ImagesModel();

		$file = $this->request->getFile('image');
		$title = $file->getName();
		$filesize = number_format(filesize($file) / 1048576, 1);

		$temp = explode(".", $title);
		$newfilename = round(microtime(true)) . '.' . end($temp);

		$countHero = $this->model->select('*')->where('flag', 'hero')->countAllResults();

		if ($countHero >= 4) {
			$response = [
				'status' => 500,
				'error' => true,
				'message' => 'Gambar tidak boleh lebih dari 5'
			];
		} else {
			if ($filesize >= 3) {
				$response = [
					'status' => 500,
					'error' => true,
					'message' => 'Ukuran foto harus dibawah 3 MB'
				];
			} else {
				if ($file->move("images", $newfilename)) {
					$fileModel = new ImagesModel();
					$data = [
						"title" => $newfilename,
						"path" => base_url() . "/images/" . $newfilename,
						"flag" => 'hero'
					];
					if ($fileModel->insert($data)) {
						$response = [
							'status' => 201,
							'error' => false,
							'message' => 'Berhasil upload',
							'data' => [
								'file_size' => $filesize,
							]
						];
					} else {
						$response = [
							'status' => 500,
							'error' => true,
							'message' => 'Failed to save image',
						];
					}
				} else {
					$response = [
						'status' => 500,
						'error' => true,
						'message' => 'Failed to upload image',
						'data' => []
					];
				}
			}
		}


		return $this->respondCreated($response);
	}


	//Upload images
	public function uploadGalleryImage()
	{
		$this->model = new ImagesModel();

		$file = $this->request->getFile('image');
		$title = $file->getName();
		$flag = $this->request->getVar('flag');
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
			if ($file->move("images", $newfilename)) {
				$fileModel = new ImagesModel();
				$data = [
					"title" => $newfilename,
					"path" => base_url() . "/images/" . $newfilename,
					"flag" => 'gallery'
				];
				if ($fileModel->insert($data)) {
					$response = [
						'status' => 201,
						'error' => false,
						'message' => 'Berhasil upload',
						'data' => [
							'file_size' => $filesize,
						]
					];
				} else {
					$response = [
						'status' => 500,
						'error' => true,
						'message' => 'Failed to save image',
					];
				}
			} else {
				$response = [
					'status' => 500,
					'error' => true,
					'message' => 'Failed to upload image',
					'data' => []
				];
			}
		}



		return $this->respondCreated($response);
	}

	// Showing all hero images
	public function listsHero()
	{
		$this->model = new ImagesModel();
		$data = $this->model->select('*')
			->where('flag', 'hero')->findAll();

		return $this->respondCreated($data, 200);
	}

	// Showing all gallery
	public function listGallery()
	{
		$this->model = new ImagesModel();
		$data = $this->model->select('*')
			->where('flag', 'gallery')->findAll();

		return $this->respondCreated($data, 200);
	}

	//Delete images according id in database and filename (title) in /images/ 
	public function deleteImage()
	{
		$id = $this->request->getVar('id');
		$title = 'images/' . $this->request->getVar('title');
		$unlink = unlink($title);

		$fileModel = new ImagesModel();


		if ($unlink) {
			$fileModel->delete(['id' => $id]);
			$response = [
				'status' => 200,
				'error' => true,
				'message' => 'Image Deleted!',
				'data' => []
			];
			return $this->respondCreated($response, 200);
		} else {
			return $this->respondCreated("Failed", 200);
		}
	}
}
