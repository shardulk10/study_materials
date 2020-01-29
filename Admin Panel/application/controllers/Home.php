<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Frontend (UserController)
 * Slide class to control all Frontend related operations.
 * @author : Shardul Kulkarni <shardulk10@gmail.com>
 * @version : 1.1
 * @since : Oct 2019
 */
class Home extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Staticpages_model');
        $this->load->model('Settings_model');
        $this->load->model('emailsubscription_model');
        $this->load->model('brands_model');
        $this->load->model('testimonials_model');
        $this->site_name = 'Maxima Ventures LLP';
    }

    public function index(){
        
        $this->global['pageTitle'] = $this->site_name.': Home';
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        $pageInfo['home_brand'] = $this->brands_model->getActiveBrandsHome();
        
        $pageInfo['home_testimonials'] = $this->testimonials_model->getActiveTestimonialsHome();
        
        $pageInfo['home_short_about_us'] = $this->Staticpages_model->getStaticDetailsUsingSlug('home_short_about_us');

        $pageInfo['home_grow_business'] = $this->Staticpages_model->getStaticDetailsUsingSlug('home_grow_business');

        $pageInfo['define_objectives'] = $this->Staticpages_model->getStaticDetailsUsingSlug('define_objectives');

        $pageInfo['develop_a_plan'] = $this->Staticpages_model->getStaticDetailsUsingSlug('develop_a_plan');

        $pageInfo['implementation'] = $this->Staticpages_model->getStaticDetailsUsingSlug('implementation');

        $pageInfo['monitor_results'] = $this->Staticpages_model->getStaticDetailsUsingSlug('monitor_results');


        $pageInfo['our_commitment'] = $this->Staticpages_model->getStaticDetailsUsingSlug('our_commitment');

        $footerInfo['footer_bottom_content'] = $this->Staticpages_model->getStaticDetailsUsingSlug('footer_bottom_content');
        
        if(isset($_POST['sbtService']) && !empty($_POST['sbtService']) && $_POST['sbtService'] == 'sbtService') {
          $service = !empty($_POST['selService']) ? ucfirst($_POST['selService']) : "";
          $name = !empty($_POST['name']) ? ucwords($_POST['name']) : "";
          $phone = !empty($_POST['phone']) ? $_POST['phone'] : "";
          $toMaxima = "";
          if(!empty($service) && !empty($name) && !empty($phone)) {
            $toMaxima = "Dear Maxima,\n\n  I have the following query.  Please respond at the earliest.\n\n Service : ".$service."\n"." Name : ".$name."\n"." Phone : ".$phone."\n\n Thanks And Regards\n".$name;
            
            mail("contact@maximaventuresllp.com","New Enquiry From ".$name." For ".$service,$toMaxima);
            // mail("shardulk10@gmail.com","New Enquiry From ".$name,$toMaxima);
            redirect(""); 
          } else {
            $pageInfo['error_message_form'] = "Please fill all Information.";
          }
        }
        
        $headerInfo['active_home'] = true;

        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/home', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }

    public function about_us(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
    
        $this->global['pageTitle'] = $this->site_name.': About Us';

        $pageInfo['about_us_para_1'] = $this->Staticpages_model->getStaticDetailsUsingSlug('about_us_para_1');

        $pageInfo['about_us_para_2'] = $this->Staticpages_model->getStaticDetailsUsingSlug('about_us_para_2');

        $pageInfo['about_us_why_choose_us'] = $this->Staticpages_model->getStaticDetailsUsingSlug('about_us_why_choose_us');
        
        $headerInfo['active_about_us'] = true;
        
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/about_us', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }

    public function service(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Service';


        $pageInfo['service_content'] = $this->Staticpages_model->getStaticDetailsUsingSlug('service_content');

        $pageInfo['service_para_2'] = $this->Staticpages_model->getStaticDetailsUsingSlug('service_para_2');

        $pageInfo['service_para_3'] = $this->Staticpages_model->getStaticDetailsUsingSlug('service_para_3');

        $headerInfo['active_service'] = true;
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/service', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }

    public function partners(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Partners';
        
        $pageInfo['brands'] = $this->brands_model->getActiveBrands();
        
        $headerInfo['active_partners'] = true;
        
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/partners', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }

    public function testimonials(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Testimonials';

        $pageInfo['testimonials'] = $this->testimonials_model->getActiveTestimonials();
        
        $headerInfo['active_testimonials'] = true;
        
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/testimonials', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }

    public function contact_us(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Testimonials';
        
        $headerInfo['active_contact_us'] = true;
        
        $name = "";
        $email = "";
        $phone = "";
        $message = "";
          
        if(isset($_POST['sbtContactUsForm']) && !empty($_POST['sbtContactUsForm']) && $_POST['sbtContactUsForm'] == 'sbtContactUsForm') {
          
          $name = !empty($_POST['name']) ? ucwords($_POST['name']) : "";
          $email = !empty($_POST['email']) ? $_POST['email'] : "";
          $phone = !empty($_POST['phone']) ? $_POST['phone'] : "";
          $comments = !empty($_POST['comments']) ? $_POST['comments'] : "";
          
          $toMaxima = "";
          $toClient = "";
          
          if(!empty($name) && !empty($email) && !empty($phone) && !empty($comments)) {

            $headerMaxima = 'From:'.$email;            
            $toMaxima = "Dear Maxima,\n\n  I have the following query.  Please respond at the earliest.\n\n"."Name : ".$name."\n Phone : ".$phone."\n Email : ".$email."\n Comments : ".$comments."\n\n Thanks And Regards\n".$name;
            mail("contact@maximaventuresllp.com","New Enquiry From ".$name." For ".$service,$toMaxima,$headerMaxima);
            
            $headerCustomer = 'From:contact@maximaventuresllp.com';            
            $toClient = "Dear ".$name.",\n\n At the outset, Team Maxima Ventures LLP is really thankful to you for the interest you have shown in our products/services.  We will get back to you on your query within 48 working hours.  In case of any urgent query, please feel free to contact us through WhatsApp on the numbers given below.\n\nAssuring you of our best services at all times.\n\n Thanks And Regards\n Team Maxima Ventures LLP\n M: 9619903590/9323609443";
            mail($email,"Thanks for Contacting Maxima Ventures LLP ",$toClient,$headerCustomer);
            // mail("contact@maximaventuresllp.com","Thanks for Contacting Maxima Ventures LLP ",$toClient);
            
            redirect(base_url()."contact_us"); 
          } else {
            $pageInfo['error_message_form'] = "Please fill all Information.";
          }
        }

        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/contact_us', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }
    
    public function grow_your_welth(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Grow Your Welth';

        $pageInfo['grow_your_welth_full_content'] = $this->Staticpages_model->getStaticDetailsUsingSlug('grow_your_welth_full_content');

        $headerInfo['active_grow_your_welth'] = true;
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/grow_your_welth', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }
    
    public function products(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Products';

        $pageInfo['products_full_content'] = $this->Staticpages_model->getStaticDetailsUsingSlug('products_full_content');

        $headerInfo['active_products'] = true;
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/products', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }
    
    public function our_team(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Our Team';

        $headerInfo['active_ourteam'] = true;
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/ourteam', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }
    
    public function disclaimer(){
        $pageInfo = array();
        $headerInfo = array();
        $footerInfo = array();
        
        $this->global['pageTitle'] = $this->site_name.': Disclaimer';

        $headerInfo['active_ourteam'] = true;
        $this->load->view('frontend/frontend_header', $headerInfo);
        $this->load->view('frontend/disclaimer', $pageInfo);
        $this->load->view('frontend/frontend_footer', $footerInfo);
    }

}