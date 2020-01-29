<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model
{

    function updateSettingsData($key, $value)
    {
        $this->db->trans_start();
        $this->db->where('key', $key);
        $this->db->update('tbl_settings', $value);
        $this->db->trans_complete();
        return true;
    }

    function getSettingsData()
    {
        $this->db->select('key,value');
        $this->db->from('tbl_settings');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}