<?php

namespace src\controller\entity\repository;

/**
 * Trait settlement
 * @package entity
 */
trait settlementRepositoryController
{
    /**
     *
     * Get all settlement from one month
     *
     * @param $month string Month of settlement searched
     * @param $year string Year of settlement searched
     *
     * @return array Array of settlements of the requested period
     */
    public function getAllSettlementsOfMonth( $month, $year )
    {
        return $this->db->fetchAll( $this->table, '*', NULL, 'WHERE MONTH(date) = `'.$month.'` AND YEAR(date) = `'.$year.'`');
    }

    /**
     *
     * Get all commissions from given settlement
     *
     * @param $settlement_id int Settlement to get the commissions
     *
     * @return array Array of settlement's commissions
     */
    public function getAllCommissionsfromSettlementId( $settlement_id )
    {
        return $this->getAll( ['settlement' => $settlement_id ] );
    }
}
