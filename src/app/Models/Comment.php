<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
	protected $table      = 'comments';
	protected $primaryKey = 'id';

	protected $useAutoIncrement = true;

	protected $returnType     = 'array';
	protected $useSoftDeletes = false;

	protected $allowedFields = ['name', 'email', 'date', 'message'];

	// Dates
	protected $useTimestamps = false;


	// Validation
	protected $validationRules = [
		'name'     => 'required|max_length[50]|min_length[3]',
		'email'        => 'required|max_length[254]|valid_email',
		'date'     => 'required|max_length[20]|min_length[8]',
		'message'     => 'required|max_length[500]|min_length[3]',
	];
	protected $validationMessages = [
		'email' => [
			'valid_email' => 'Проверьте правильность введенного адреса электронной почты формат',
		],
	];



}
