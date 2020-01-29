<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Brands_model extends CI_Model
{

	/**
     * This function is used to get the brands listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function brandsListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id');
        $this->db->from('tbl_brands as BaseTbl');

		if($this->session->userdata("userId")==1)
        {
           //select all
        } else {
			//$this->db->where('BaseTbl.createdby', $this->session->userdata("userId"));
		}
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get the brands listing
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    /**
     * This function used to get all active cubes
     * @return array $result : This is active cube information
     */
    function getActiveBrands($userId=0)
    {
        $this->db->select('*');
        $this->db->from('tbl_brands');
        $this->db->where('published', 1);
        if($this->session->userdata("userId")==1)
        {
           //select all
        } else {
            //$this->db->where('site_id', $this->session->userdata("siteId"));
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    function getActiveBrandsHome($userId=0)
    {
        $this->db->select('*');
        $this->db->from('tbl_brands');
        $this->db->where('published', 1);
        $this->db->limit(6,0);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the Brands listing
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function brandsListing($searchText = '', $page, $segment, $cubeId)
    {

        $this->db->select('*');
        $this->db->from('tbl_brands as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if($this->session->userdata("userId")==1)
        {
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
     * This function used to get brands information by id
     * @param number $slideId : This is Brand id
     * @return array $result : This is Brand information
     */
    function getBrandsInfo($slideId)
    {
        $this->db->select('*');
        $this->db->from('tbl_brands');
        if($this->session->userdata("userId")==1)
        {
           //select all
        } else {
            //$this->db->where('createdby', $this->session->userdata("userId"));
        }
        $this->db->where('id', $slideId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new Brands to system
     * @return number $insert_id : This is last inserted id
     */
    function updateBrands($slideInfo, $slideId)
    {
        $this->db->trans_start();
        $this->db->where('id', $slideId);
        $this->db->update('tbl_brands', $slideInfo);
        $this->db->trans_complete();
        return true;
    }

    /**
     * This function is used to add new brands to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewBrands($slideInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_brands', $slideInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function is used to delete slide
     * @param number $slideId : This is brand id
     */
    function deleteBrands($slideId)
    {
        $slideInfo = array();
        $this->db->where('id', $slideId);
        $this->db->delete('tbl_brands');
        return $this->db->affected_rows();
    }


}