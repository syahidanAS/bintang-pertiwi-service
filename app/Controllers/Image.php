<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ImagesModel;
use CodeIgniter\API\ResponseTrait;

class Image extends ResourceController
{
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{
		//
	}
	public function uploadImage()
	{
		$file = $this->request->getFile('image');
		$title = $file->getName();
		$flag = $this->request->getVar('flag');

		$temp = explode(".", $title);
		$newfilename = round(microtime(true)) . '.' . end($temp);

		if ($file->move("images/hero", $newfilename)) {
			$fileModel = new ImagesModel();

			$data = [
				"title" => $newfilename,
				"path" => "images/hero/" . $newfilename,
				"flag" => $flag
			];
			if ($fileModel->insert($data)) {
				$response = [
					'status' => 201,
					'error' => false,
					'message' => 'File uploaded successfully',
					'data' => []
				];
			} else {
				$response = [
					'status' => 500,
					'error' => true,
					'message' => 'Failed to save image',
					'data' => []
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
		return $this->respondCreated($response);
	}

	public function listsHero()
	{

		$fileModel = new ImagesModel();
		$payload = $fileModel->findAll();
		$response = [
			'status' => 500,
			'error' => true,
			'message' => 'Failed to upload image',
			'data' => $payload
		];

		return $this->respondCreated($response);
	}

	
	public function deleteImage(){
		$id = $this->request->getVar('id');
		$path = $this->request->getVar('path');
		$unlink = unlink($path);

		$fileModel = new ImagesModel();
		$sql = $fileModel->delete(['id' => $id]);
		
		if($unlink && $sql){
			$response = [
				'status' => 200,
				'error' => true,
				'message' => 'Image Deleted!',
				'data' => []
			];
			return $this->respondCreated($response, 200);
		}else{
			return $this->respondCreated("Failed", 200);
		}
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		//
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		//
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		//
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		//
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		//
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		//
	}
}
