<?php

use App\Models\Settings\MaintenanceModel;

if (!function_exists('isModeMaintenance')) {
    /**
     * Check if maintenance mode is active  OR not
     *
     * @return boolean
     */
    function isModeMaintenance():bool{
        $maintenance = new MaintenanceModel();
        $status =  $maintenance->getMaintenanceStatus();
        return ($status == 1) ? true : false;
    }
}