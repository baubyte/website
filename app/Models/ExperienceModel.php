<?php

namespace App\Models;

use App\Entities\ExperienceEntity;
use CodeIgniter\Model;

class ExperienceModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'experiences';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = ExperienceEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ['company', 'description_en','description_es', 'specialty_es', 'specialty_en','start','end'];
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

	public function getExperiences()
	{
		return $this->db->table('experiences')->select('id, company, start, end, specialty_es')->where('deleted_at IS NULL');
	}
}
