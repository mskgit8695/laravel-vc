<?php

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
        $user_role = '';
        switch ($role) {
            case 1:
                $user_role = 'Super Admin';
                break;
            case 2:
                $user_role = 'Admin';
                break;
            default:
                $user_role = 'Employee';
        }

        return $user_role;
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
