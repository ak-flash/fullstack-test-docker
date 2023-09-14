<?php

namespace App\Controllers\API\v1;

use App\Models\Comment;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Comments extends ResourceController
{
	use ResponseTrait;

	protected $helpers = ['form'];
	protected $modelName = 'App\Models\Comment';
	protected $format    = 'json';
	public $perPage = 3;

	public function index()
	{
		$sortBy = ($this->request->getVar('sortBy') === 'id') ? 'id' : 'date';
		$orderBy = ($this->request->getVar('orderBy') === 'DESC') ? 'DESC' : 'ASC';

		$data = [
			'comments' => $this->model
				->orderBy($sortBy, $orderBy)
				->paginate($this->perPage),

			'pager' => $this->request->getGet('orderBy'),
			'currentPage' => $this->model->pager->getCurrentPage(),
			'totalPages'  => $this->model->pager->getPageCount(),
		];

		return $this->respond($data);
	}

	public function create()
	{
		if (!$this->validate($this->model->validationRules)) {
			return $this->fail($this->validator->getErrors());
		}

		$data = [
			'name' => $this->request->getVar('name'),
			'email'  => $this->request->getVar('email'),
			'date'  => $this->request->getVar('date'),
			'message'  => $this->request->getVar('message'),
		];

		$comment = $this->model->insert($data);

		if($comment) {
			$response = [
				'status'   => 201,
				'error'    => null,
				'messages' => [
					'success' => 'Комментарий успешно добавлен'
				]
			];
		} else {
			$response = [
				'status'   => 400,
				'error'    => 400,
				'messages' => [
					'success' => 'Ошибка добавления'
				]
			];
		}


		return $this->respondCreated($response);
	}

	public function delete($id = null){

		$data = $this->model->find($id);

		if($data){
			$this->model->delete($id);

			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Комментарий успешно удалён'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('Комментарий не найден');
		}
	}
}
