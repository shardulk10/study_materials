<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Slide (BrandsController)
 * Slide class to control all slide related operations.
 * @author : Shardul Kulkarni <shardulk10@gmail.com>
 * @version : 1.1
 * @since : Oct 2019
 */
class Brands extends BaseController
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('brands_model');
        $this->isLoggedIn();
    }

   	public function index(){
   		$this->global['pageTitle'] = 'Maxima Ventures LLP : Manage Brands';
        $this->loadViews("dashboard", $this->global, NULL , NULL);
   	}

   	/**
     * This function is used to load the Brands list
     */
    function manageBrands()
    {
			$data['cube_id'] = $this->input->post('cube_id');
			$searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $count = $this->brands_model->brandsListingCount($searchText);
			$returns = $this->paginationCompress ( "manageBrands/", $count, 10 );
            $data['slideRecords'] = $this->brands_model->brandsListing($searchText, $returns["page"], $returns["segment"], $data['cube_id']);

            $this->global['pageTitle'] = 'Maxima Ventures LLP : Brands Listing';
            $this->loadViews("brands", $this->global, $data, NULL);
    }

    /**
     * This function is used load Brands Edit Information
     * @param number $slideId : Optional : This is brands id
     */
    function editBrands($slideId = NULL)
    {
            if($slideId == null) {
                redirect('manageBrands');
            }
            $data['slideInfo'] = $this->brands_model->getBrandsInfo($slideId)[0];
			$this->global['pageTitle'] = 'Maxima Ventures LLP : Edit Brands';
            $this->loadViews("editBrands", $this->global, $data, NULL);
    }

    /**
     * This function is used to update brands to the system
     */
    function updateBrands($slideId)
    {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('brands_name','Name','trim|required');

            if($this->form_validation->run() == FALSE) {
                $this->newBrands();
            } else {

                $brands_name = $this->security->xss_clean($this->input->post('brands_name'));
                $published = $this->security->xss_clean($this->input->post('published'));

                $slideInfo = array();
                $slideInfo['name'] = $brands_name;
                $slideInfo['published'] = $published;
                $slideInfo['updatedby'] = $this->session->userdata('userId');
                $slideInfo['updated_datetime'] = @date('Y-m-d H:i:s');

                if(!empty($_FILES['brands_image']['name'])){
                  $fileUpload = $this->do_upload('brands_image','brands/');
                  if($fileUpload['success'] == 1){
                     $uploadedFile = base_url().'assets/uploads/brands/'.$fileUpload['upload_data']['file_name'];
                     $slideInfo['image_path'] = $uploadedFile;
                  }
                }
                $result = $this->brands_model->updateBrands($slideInfo, $slideId);

                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'Brands has been updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Brands updation failed');
                }
                redirect('editBrands/'.$slideId);
            }

    }


    /**
     * This function is used to load the add new form
     */
    function newBrands()
    {
            $this->global['pageTitle'] = 'Maxima Ventures LLP : Add New Brands';
            $this->loadViews("newBrands");
    }

    /**
     * This function is used to add new Brands to the system
     */
    function addNewBrands()
    {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('brands_name','Name','trim|required');
            if($this->form_validation->run() == FALSE){
                $this->newBrands();
            } else {

                $brands_name = $this->security->xss_clean($this->input->post('brands_name'));
                $published = $this->security->xss_clean($this->input->post('published'));

				$slideInfo = array();
				$slideInfo['name'] = $brands_name;
				$slideInfo['published'] = $published;
                $slideInfo['createdby'] = $this->session->userdata('userId');
				$slideInfo['created_datetime'] = @date('Y-m-d H:i:s');
				$slideInfo['updated_datetime'] = $slideInfo['created_datetime'];

                if(!empty($_FILES['brands_image']['name'])){
                    $fileUpload = $this->do_upload('brands_image','brands/');
                    if($fileUpload['success'] == 1){
                       $uploadedFile = base_url().'assets/uploads/brands/'.$fileUpload['upload_data']['file_name'];
                       $slideInfo['image_path'] = $uploadedFile;
                    }
                }

                $result = $this->brands_model->addNewBrands($slideInfo);

                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Brand created successfully');
                    redirect('manageBrands');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Brand creation failed');
                    redirect('newBrands');
                }
            }
    }


    /**
     * This function is used to delete Brands
     */
    function deleteBrands($slideId=0)
    {
        $result = $this->brands_model->deleteBrands($slideId);

    		if($result > 0) {
    			$this->session->set_flashdata('success', 'Brand has been deleted successfully');
    		} else {
    			$this->session->set_flashdata('error', 'Brand deletion failed');
    		}
    		redirect('manageBrands');
    }
}