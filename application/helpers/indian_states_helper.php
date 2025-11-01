<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Indian States Helper
 * Contains all 28 states and 8 union territories of India
 */

if (!function_exists('get_indian_states')) {
    /**
     * Get list of all Indian states and union territories
     * @return array Associative array with state code as key and state name as value
     */
    function get_indian_states() {
        return array(
            // States (28)
            'AP' => 'Andhra Pradesh',
            'AR' => 'Arunachal Pradesh',
            'AS' => 'Assam',
            'BR' => 'Bihar',
            'CT' => 'Chhattisgarh',
            'GA' => 'Goa',
            'GJ' => 'Gujarat',
            'HR' => 'Haryana',
            'HP' => 'Himachal Pradesh',
            'JH' => 'Jharkhand',
            'KA' => 'Karnataka',
            'KL' => 'Kerala',
            'MP' => 'Madhya Pradesh',
            'MH' => 'Maharashtra',
            'MN' => 'Manipur',
            'ML' => 'Meghalaya',
            'MZ' => 'Mizoram',
            'NL' => 'Nagaland',
            'OR' => 'Odisha',
            'PB' => 'Punjab',
            'RJ' => 'Rajasthan',
            'SK' => 'Sikkim',
            'TN' => 'Tamil Nadu',
            'TG' => 'Telangana',
            'TR' => 'Tripura',
            'UP' => 'Uttar Pradesh',
            'UT' => 'Uttarakhand',
            'WB' => 'West Bengal',
            // Union Territories (8)
            'AN' => 'Andaman and Nicobar Islands',
            'CH' => 'Chandigarh',
            'DH' => 'Dadra and Nagar Haveli and Daman and Diu',
            'DL' => 'Delhi',
            'JK' => 'Jammu and Kashmir',
            'LA' => 'Ladakh',
            'LD' => 'Lakshadweep',
            'PY' => 'Puducherry'
        );
    }
}

