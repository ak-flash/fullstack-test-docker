<?php

namespace App\Controllers;

class CommentsPage extends BaseController
{
	public function index()
	{
		return view('comments');
	}
}
