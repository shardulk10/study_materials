<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Oneliner_model extends CI_Model
{
    /**
     * This function is used to get the Oneliner listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function onelinerListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.oneliner, BaseTbl.createdby');
        $this->db->from('tbl_oneliner as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.oneliner  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }

		if($this->session->userdata("userId")==1) {
           //select all
        } else {
			//$this->db->where('BaseTbl.createdby', $this->session->userdata("userId"));
		}

        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get the oneliner listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function onelinerListing($searchText = '', $page, $segment)
    {

        $this->db->select('BaseTbl.id, BaseTbl.oneliner, BaseTbl.createdby');

        $this->db->from('tbl_oneliner as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.oneliner  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }

        if($this->session->userdata("userId")==1) {
           //select all
        } else {
            //$this->db->where('BaseTbl.createdby', $this->session->userdata("userId"));
        }

        $this->db->order_by("updated_datetime", "desc");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function used to get oneliner information by id
     * @param number $slideId : This is oneliner id
     * @return array $result : This is oneliner information
     */
    function getOnelinerInfo($slideId)
    {
        $this->db->select('*');
        $this->db->from('tbl_oneliner');
        if($this->session->userdata("userId")==1) {
           //select all
        } else {
            //$this->db->where('createdby', $this->session->userdata("userId"));
        }
        $this->db->where('id', $slideId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new oneliner to system
     * @return boolen
     */
    function updateOneliner($slideInfo, $onelinerId)
    {
        $this->db->trans_start();
        $this->db->where('id', $onelinerId);
        $this->db->update('tbl_oneliner', $slideInfo);
        $this->db->trans_complete();
        return true;
    }

    /**
     * This function is used to add new oneliner to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewOneliner($slideInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_oneliner', $slideInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function is used to delete oneliner
     * @param number $slideId : This is oneliner id
     * @param array $onelinerInfo : This is Oneliner updation info
     */
    function deleteOneliner($onelinerId)
    {
        $slideInfo = array();
        $this->db->where('id', $onelinerId);
        $this->db->delete('tbl_oneliner');
        return $this->db->affected_rows();
    }

}

