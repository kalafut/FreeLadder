<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function unique($value, $params)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('unique',
			'The %s is already being used.');

		list($table, $field) = explode(".", $params, 2);

        $q = $CI->db->query("SELECT * FROM $table WHERE $field = ?", array($value));

        echo $q->num_rows();
        echo $CI->db->last_query();
        return ($q->num_rows() == 0);
	}
}

