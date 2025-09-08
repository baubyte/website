<?php

namespace App\Models\Settings;

use CodeIgniter\Model;
use App\Models\Traits\Cache;

class MaintenanceModel extends Model
{
    use Cache;

    protected $table            = 'maintenance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get maintenance status
     *
     * @return int 1 = maintenance mode, 0 = normal mode
     */ 
    public function getMaintenanceStatus()
    {
        $cacheName = "maintenance_status";

        if (null === $status = $this->getCache($cacheName)) {
            $status = (int)$this->select('status')->first()->status;
            $this->saveCache($status, $cacheName);
        }

        return $status;
    }

    /**
     * Update maintenance status
     *
     * @return bool
     */
    public function updateMaintenanceStatus(bool $status = true)
    {
        $status = ($status) ? 1 : 0;
        $this->set('status', $status);
        $this->update($this->select('id')->first()->id);
        $this->saveCache($status, "maintenance_status");
        return true;
    }
}
