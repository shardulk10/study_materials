<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Emailsubscription_model extends CI_Model
{
    /**
     * This function is used to get the Email Subscription listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function emailsubscriptionListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.email, BaseTbl.subscribed, BaseTbl.createdby');
        $this->db->from('tbl_emailsubscription as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%')";
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
     * This function is used to get the Email Subscription listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function emailsubscriptionListing($searchText = '', $page, $segment)
    {

        $this->db->select('BaseTbl.id, BaseTbl.email, BaseTbl.subscribed, BaseTbl.createdby');

        $this->db->from('tbl_emailsubscription as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%')";
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
     * This function used to get email subscription information by id
     * @param number $slideId : This is email subscription id
     * @return array $result : This is email subscription information
     */
    function getEmailsubscriptionInfo($slideId)
    {
        $this->db->select('*');
        $this->db->from('tbl_emailsubscription');
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
     * This function is used to add new email subscription to system
     * @return boolen
     */
    function updateEmailsubscription($slideInfo, $emailsubscriptionId)
    {
        $this->db->trans_start();
        $this->db->where('id', $emailsubscriptionId);
        $this->db->update('tbl_emailsubscription', $slideInfo);
        $this->db->trans_complete();
        return true;
    }

    /**
     * This function is used to add new email subscription to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewEmailsubscription($slideInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_emailsubscription', $slideInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function is used to delete email subscription
     * @param number $slideId : This is email subscription id
     * @param array $emailsubscriptionInfo : This is Emailsubscription updation info
     */
    function deleteEmailsubscription($emailsubscriptionId)
    {
        $slideInfo = array();
        $this->db->where('id', $emailsubscriptionId);
        $this->db->delete('tbl_emailsubscription');
        return $this->db->affected_rows();
    }

}

