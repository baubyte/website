<?php

namespace App\Models;

use App\Entities\ProfileEntity;
use CodeIgniter\Model;

class ProfileModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'profiles';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = ProfileEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ['name', 'surname','avatar','email_contact','description_en','description_es', 'specialty_es', 'specialty_en','language_es','language_en','github_url','linkedin_url','instagram_url'];
	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	public function getProfiles()
	{
		return $this->db->table('profiles')->select('id, name, surname, github_url, linkedin_url, instagram_url')->where('deleted_at IS NULL');
	}
}
