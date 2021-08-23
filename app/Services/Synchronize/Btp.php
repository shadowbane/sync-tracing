<?php

namespace App\Services\Synchronize;

/**
 * Class BTP.
 *
 * @package App\Services\Synchronize
 */
class Btp extends AbstractConnector
{
    protected string $connectionName = 'btp';

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
               'BTP' as unit,
                nim as identifier,
                (SELECT COUNT(id) FROM mahasiswa_vaccinations mv WHERE mv.mahasiswa_detail_uuid = (
                    SELECT uuid FROM mahasiswa_details md WHERE mr.mahasiswa_uuid = md.uuid
                )) as vaccine_count
            FROM mahasiswa_registers mr
            WHERE id_jenis_keluar IS NULL
            AND deleted_at IS NULL;
        ");

        return $this;
    }
}
