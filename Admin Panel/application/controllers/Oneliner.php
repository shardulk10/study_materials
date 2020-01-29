<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Slide (UserController)
 * Slide class to control all slide related operations.
 * @author : Shardul Kulkarni <shardulk10@gmail.com>
 * @version : 1.1
 * @since : Oct 2019
 */
class Oneliner extends BaseController
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('oneliner_model');
        $this->isLoggedIn();
    }

   	public function index(){
   		$this->global['pageTitle'] = 'Maxima Ventures LLP : Manage One Liner';

        $this->loadViews("dashboard", $this->global, NULL , NULL);
   	}

   	/**
     * This function is used to load the Oneliner list
     */
    function manageOneliner()
    {
      $searchText = $this->security->xss_clean($this->input->post('searchText'));
      $data['searchText'] = $searchText;

      $this->load->library('pagination');

      $count = $this->oneliner_model->onelinerListingCount($searchText);

      $returns = $this->paginationCompress ( "manageOneliner/", $count, 10 );
      // print_r($data);die;

      $data['slideRecords'] = $this->oneliner_model->onelinerListing($searchText, $returns["page"], $returns["segment"]);

      $this->global['pageTitle'] = 'Maxima Ventures LLP : Oneliner Listing';

      $this->loadViews("oneliner", $this->global, $data, NULL);

    }

    /**
     * This function is used load Oneliner edit information
     * @param number $onelinerId : Optional : This is oneliner id
     */
    function editOneliner($onelinerId = NULL)
    {
            if($onelinerId == null)
            {
                redirect('manageOneliner');
            }

            $data['slideInfo'] = $this->oneliner_model->getOnelinerInfo($onelinerId)[0];

            $this->global['pageTitle'] = 'Maxima Ventures LLP : Edit Oneliner';

            $this->loadViews("editOneliner", $this->global, $data, NULL);

    }

    /**
     * This function is used to add new Oneliner to the system
     */
    function updateOneliner($slideId)
    {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('oneliner','Oneliner','trim|required');

            if($this->form_validation->run() == FALSE) {
                $this->newOneliner();
            } else {

                $oneliner = $this->security->xss_clean($this->input->post('oneliner'));

        				$slideInfo = array();
        				$slideInfo['oneliner'] = $oneliner;
                $slideInfo['updatedby'] = $this->session->userdata('userId');
                $slideInfo['updated_datetime'] = @date('Y-m-d H:i:s');

                $result = $this->oneliner_model->updateOneliner($slideInfo, $slideId);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'One Liner has been updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'One Liner updation failed');
                }
                redirect('editOneliner/'.$slideId);
            }

    }


    /**
     * This function is used to load the add new form for oneliner
     */
    function newOneliner()
    {

            $this->global['pageTitle'] = 'Maxima Ventures LLP : Add New Oneliner';

            $this->loadViews("newOneliner");

    }

    /**
     * This function is used to add new Oneliner to the system
     */
    function addNewOneliner()
    {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('oneliner','Oneliner','trim|required');
            if($this->form_validation->run() == FALSE) {
                $this->newOneliner();
            } else {

                $oneliner = $this->security->xss_clean($this->input->post('oneliner'));

        				$slideInfo = array();
        				$slideInfo['oneliner'] = $oneliner;
                $slideInfo['createdby'] = $this->session->userdata('userId');
        				$slideInfo['created_datetime'] = @date('Y-m-d H:i:s');
        				$slideInfo['updated_datetime'] = $slideInfo['created_datetime'];

                $result = $this->oneliner_model->addNewOneliner($slideInfo);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'New Oneliner created successfully');
                    redirect('manageOneliner');
                } else {
                    $this->session->set_flashdata('error', 'Oneliner creation failed');
                    redirect('newOneliner');
                }

            }

    }


    /**
     * This function is used to delete Oneliner
     */
    function deleteOneliner($slideId=0)
    {
        $result = $this->oneliner_model->deleteOneliner($slideId);

    		if($result > 0) {
    			$this->session->set_flashdata('success', 'Oneliner has been deleted successfully');
    		} else {
    			$this->session->set_flashdata('error', 'Oneliner deletion failed');
    		}
    		redirect('manageOneliner');
    }


}