<?php

use Illuminate\Support\Facades\DB;

/**
 * Get user role
 */
if (!function_exists('get_user_role')) {
    /**
     * Accept user role fetched from users model
     *
     * @param integer $role
     * @return string $user_role
     */

    function get_user_role($role)
    {
        return DB::table('m_role')->where(['id' => $role, 'status' => 1])->value('name');
    }
}

/**
 * Get Status list
 */
if (!function_exists('get_status_list')) {
    /**
     * Provide Status List
     *
     * @return array
     */

    function get_status_list()
    {
        return [
            0 => 'Inactive',
            1 => 'Active'
        ];
    }
}

/**
 * Get Status like Active or Inactive
 */
if (!function_exists('get_status')) {
    /**
     * Provide Status List
     *
     * @param integer $index
     * @return string
     */

    function get_status($index)
    {
        // Get status array
        $status = get_status_list();

        // Return status
        return $status[$index];
    }
}

/**
 * Get booking status
 */
if (!function_exists('get_booking_status')) {
    /**
     * Retrieves the booking status.
     *
     * @param integer $status
     * @return \Illuminate\Support\Collection
     */

    function get_booking_status($status)
    {
        return DB::table('m_booking_status')->where(['id' => $status, 'status' => 1])->value('name');
    }
}

/**
 * Get master booking status
 */
if (!function_exists('get_booking_master')) {
    /**
     * Retrieves the booking master.
     *
     * @return \Illuminate\Support\Collection
     */

    function get_booking_master()
    {
        return DB::table('m_booking_status')->where('status', 1)->get(['id', 'name']);
    }
}
