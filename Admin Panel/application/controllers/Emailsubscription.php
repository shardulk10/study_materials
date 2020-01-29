<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Slide (UserController)
 * Slide class to control all slide related operations.
 * @author : Shardul Kulkarni <shardulk10@gmail.com>
 * @version : 1.1
 * @since : Oct 2019
 */
class Emailsubscription extends BaseController
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('emailsubscription_model');
        $this->isLoggedIn();
    }

   	public function index(){
   		$this->global['pageTitle'] = 'Maxima Ventures LLP : Manage Email Subscription';

        $this->loadViews("dashboard", $this->global, NULL , NULL);
   	}

   	/**
     * This function is used to load the Email Subscription list
     */
    function manageEmailsubscription()
    {
      $searchText = $this->security->xss_clean($this->input->post('searchText'));
      $data['searchText'] = $searchText;

      $this->load->library('pagination');

      $count = $this->emailsubscription_model->emailsubscriptionListingCount($searchText);

      $returns = $this->paginationCompress ( "manageEmailsubscription/", $count, 10 );
      // print_r($data);die;

      $data['slideRecords'] = $this->emailsubscription_model->emailsubscriptionListing($searchText, $returns["page"], $returns["segment"]);

      $this->global['pageTitle'] = 'Maxima Ventures LLP : Email Subscription Listing';

      $this->loadViews("emailsubscription", $this->global, $data, NULL);

    }

    /**
     * This function is used load Email Subscription edit information
     * @param number $emailsubscriptionId : Optional : This is email subscription id
     */
    function editEmailsubscription($emailsubscriptionId = NULL)
    {
            if($emailsubscriptionId == null)
            {
                redirect('manageEmailsubscription');
            }

            $data['slideInfo'] = $this->emailsubscription_model->getEmailSubscriptionInfo($emailsubscriptionId)[0];

            $this->global['pageTitle'] = 'Maxima Ventures LLP : Edit Email Subscription';

            $this->loadViews("editEmailsubscription", $this->global, $data, NULL);

    }

    /**
     * This function is used to add new Email Subscription to the system
     */
    function updateEmailsubscription($slideId)
    {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('email','Email','trim|required');

            if($this->form_validation->run() == FALSE) {
                $this->newEmailsubscription();
            } else {

                $email = $this->security->xss_clean($this->input->post('email'));
                $subscribed = $this->security->xss_clean($this->input->post('subscribed'));

        				$slideInfo = array();
        				$slideInfo['email'] = $email;
                $slideInfo['subscribed'] = $subscribed;
                $slideInfo['updatedby'] = $this->session->userdata('userId');
                $slideInfo['updated_datetime'] = @date('Y-m-d H:i:s');

                $result = $this->emailsubscription_model->updateEmailsubscription($slideInfo, $slideId);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'Email Subscription has been updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Email Subscription updation failed');
                }
                redirect('editEmailsubscription/'.$slideId);
            }

    }


    /**
     * This function is used to load the add new form for email subscription
     */
    function newEmailsubscription()
    {

            $this->global['pageTitle'] = 'Maxima Ventures LLP : Add New Email';

            $this->loadViews("newEmailsubscription");

    }

    /**
     * This function is used to add new Email Subscription to the system
     */
    function addNewEmailsubscription()
    {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('email','Email','trim|required');
            if($this->form_validation->run() == FALSE) {
                $this->newEmailsubscription();
            } else {

                $email = $this->security->xss_clean($this->input->post('email'));
                $subscribed = $this->security->xss_clean($this->input->post('subscribed'));

        				$slideInfo = array();
        				$slideInfo['email'] = $email;
                $slideInfo['subscribed'] = $subscribed;
                $slideInfo['createdby'] = $this->session->userdata('userId');
        				$slideInfo['created_datetime'] = @date('Y-m-d H:i:s');
        				$slideInfo['updated_datetime'] = $slideInfo['created_datetime'];

                $result = $this->emailsubscription_model->addNewEmailsubscription($slideInfo);

                if($result > 0) {
                    $this->session->set_flashdata('success', 'New Email Subscription created successfully');
                    redirect('manageEmailsubscription');
                } else {
                    $this->session->set_flashdata('error', 'Email Subscription creation failed');
                    redirect('newEmailsubscription');
                }

            }

    }


    /**
     * This function is used to delete Email Subscription
     */
    function deleteEmailsubscription($slideId=0)
    {
        $result = $this->emailsubscription_model->deleteEmailsubscription($slideId);

    		if($result > 0) {
    			$this->session->set_flashdata('success', 'Email SUbscription has been deleted successfully');
    		} else {
    			$this->session->set_flashdata('error', 'Email Subscription deletion failed');
    		}
    		redirect('manageEmailsubscription');
    }


}