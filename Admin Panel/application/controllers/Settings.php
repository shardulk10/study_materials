<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Slide (UserController)
 * Slide class to control all slide related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 12 April 2018
 */
class Settings extends BaseController
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->isLoggedIn();
    }

   	public function index(){
   		$this->global['pageTitle'] = 'Update Settings';
      $key = '';
      $tempData = '';
        $slideInfoData = $this->settings_model->getSettingsData($key, $tempData);
        $data['slideInfo'] = array();
        $slideInfo = new stdClass();
        foreach($slideInfoData as $key => $value){
            $key = $value->key;
            $slideInfo->$key = $value->value;
        }
        $data['slideInfo'] = $slideInfo;
        $this->loadViews("editSettings", $this->global, $data , NULL);
   	}


    function updateSettingsData() {
        $data = $this->input->post(NULL, TRUE);
        $result = 0;
        foreach($data as $key => $value){
            $tempData = array();
            $tempData['value'] = $this->input->post($key);
            $this->settings_model->updateSettingsData($key, $tempData);
            $result = 1;
        }

        if($result > 0) {
            $this->session->set_flashdata('success', 'Settings Data has been updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Settings updation failed');
        }
        redirect('updateSettings');

    }
}