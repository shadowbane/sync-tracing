<?php

namespace App\Services\Synchronize;

/**
 * Class Iteba.
 *
 * @package App\Services\Synchronize
 */
class Iteba extends AbstractConnector
{
    protected string $connectionName = 'iteba';

    /**
     * Set the Query.
     *
     * @return $this
     */
    public function setQuery(): AbstractConnector
    {
        $this->query = $this->db->select("
            SELECT
                (SELECT `name` FROM mahasiswa_details md WHERE mr.mahasiswa_uuid = md.uuid AND deleted_at IS NULL) as `name`,
               'ITEBA' as unit,
                nim as identifier,
                (SELECT COUNT(id) FROM mahasiswa_vaccinations mv WHERE mv.mahasiswa_detail_uuid = (
                    SELECT uuid FROM mahasiswa_details md WHERE mr.mahasiswa_uuid = md.uuid
                )) as vac_count
            FROM mahasiswa_registers mr
            WHERE id_jenis_keluar IS NULL
            AND deleted_at IS NULL;
        ");

        return $this;
    }
}
