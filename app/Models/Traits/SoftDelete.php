<?php
namespace App\Models\Traits;

use Config\Gestion\GestionConfig;

trait SoftDelete
{
    /**
     * Retorna los registros eliminados
     *
     * @return array
     */
    public function findAllDeleted(): array
    {
        //Retorna los registros eliminados
        return $this->onlyDeleted()->findAll();
    }

    /**
     * Retorna un registro eliminado
     *
     * @param integer $id
     * @return object|null
     */
    public function findDeleted(int $id): ?object{
        //Retorna un registro eliminado
        return $this->withDeleted()->find($id);
    }
    /**
     * Restaura un registro eliminado
     *
     * @param integer $id
     * @return boolean
     */
    public function restore(int $id): bool
    {
        //Restaura el registro
        $this->set('deleted_at',NULL);
        return $this->withDeleted()->update($id);
    }

    /**
     * Elimina un registro de forma permanente
     *
     * @param integer $id
     * @return boolean
     */
    public function deletePermanent(int $id): bool
    {
        //Elimina el registro
        return $this->delete($id, true);
    }
}