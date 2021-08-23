<?php

namespace App\Services\Synchronize;

/**
 * Class Hrms.
 *
 * @package App\Services\Synchronize
 */
class Hrms extends AbstractConnector
{
    protected string $connectionName = 'hrms';

    /**
     * Set the Query.
     *
     * @return $this
     */
    public function setQuery(): AbstractConnector
    {
        $this->query = $this->db->select("
            SELECT
                `name`,
               'Yayasan' as unit,
                ( SELECT nip FROM employees WHERE eb.uuid = employees.detail_uuid AND terminate_id IS NULL AND deleted_at IS NULL ORDER BY id DESC LIMIT 1 ) AS identifier,
                ( SELECT COUNT( id ) FROM employee_vaccinations WHERE eb.uuid = employee_vaccinations.detail_uuid AND deleted_at IS NULL ORDER BY id DESC LIMIT 1 ) AS vaccine_count
            FROM
                employee_biodatas eb
            WHERE
                ( SELECT nip FROM employees WHERE eb.uuid = employees.detail_uuid AND terminate_id IS NULL AND deleted_at IS NULL ORDER BY id DESC LIMIT 1 ) IS NOT NULL
        ");

        return $this;
    }
}
