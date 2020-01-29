<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Sitesettings (Sitesettings)
 * @author : Shardul Kulkarni <shardulk10@gmail.com>
 * @version : 1.1
 * @since : Oct 2019
 */
class Sitesettings extends BaseController
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }

    function editSiteSettings()
    {
        $this->global['pageTitle'] = 'Maxima Ventures LLP : Edit Site Settings';
        $this->loadViews("editSettings", $this->global, $data, NULL);
    }

    /**
     * This function is used to add new user to the system
     */
    function updateTestimonials($slideId)
    {

        $profile_name = $this->security->xss_clean($this->input->post('profile_name'));



        $slideInfo = array();
        $slideInfo['name'] = $profile_name;

        $result = $this->sitesettings_model->updateSitesettings($settingKey, $settingValue);

        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Site Settings has been updated successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Site Settings updation failed');
        }
        redirect('editSiteSettings/');


    }

}