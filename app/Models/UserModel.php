<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $allowedFields = ['username', 'password', 'role'];
  
	protected $useTimestamps = true;
	protected $useSoftDeletes = false;
}
