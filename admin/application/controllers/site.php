<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site extends CI_Controller 
{
	public function __construct( )
	{
		parent::__construct();
		$this->db->query("SET time_zone = '+05:30'");
		$this->is_logged_in();
	}
	function is_logged_in( )
	{
		$is_logged_in = $this->session->userdata( 'logged_in' );
		if ( $is_logged_in !== 'true' || !isset( $is_logged_in ) ) {
            $this->session->sess_destroy();
			redirect( base_url() . 'index.php/login', 'refresh' );
		} //$is_logged_in !== 'true' || !isset( $is_logged_in )
	}
	function checkaccess($access)
	{
		$accesslevel=$this->session->userdata('accesslevel');
		if(!in_array($accesslevel,$access))
			redirect( base_url() . 'index.php/site?alerterror=You do not have access to this page. ', 'refresh' );
	}
	public function index()
	{
		//$access = array("1","2");
//		$access = array("1","2");
//		$this->checkaccess($access);
//        $data['category']=$this->category_model->getcategorydropdown();
        
        $cat=$this->category_model->getcategorytreeforlisting(0);
        $cat1=$this->getarray($cat,"");
        
        $data['category']=$cat1;
//		$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
		$data[ 'page' ] = 'dashboard';
		$data[ 'title' ] = 'Welcome';
		$this->load->view( 'template', $data );	
	}
	public function createuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data[ 'page' ] = 'createuser';
		$data[ 'title' ] = 'Create User';
		$this->load->view( 'template', $data );	
	}
	function createusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('contact','contactno','trim');
		$this->form_validation->set_rules('phoneno','phoneno','trim');
//		$this->form_validation->set_rules('website','Website','trim|max_length[50]');
//		$this->form_validation->set_rules('description','Description','trim|');
		$this->form_validation->set_rules('address','Address','trim|');
		$this->form_validation->set_rules('city','City','trim|max_length[30]');
		$this->form_validation->set_rules('pincode','Pincode','trim|max_length[20]');
		$this->form_validation->set_rules('state','state','trim|max_length[20]');
		$this->form_validation->set_rules('country','country','trim|max_length[20]');
		$this->form_validation->set_rules('facebookuserid','facebookuserid','trim|max_length[20]');
		$this->form_validation->set_rules('google','google','trim|max_length[20]');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('status','Status','trim');
		$this->form_validation->set_rules('dob','DOB','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
			$data['page']='createuser';
			$data['title']='Create New User';
			$this->load->view('template',$data);
		}
		else
		{
            $website=$this->input->post('website');
            $dob=$this->input->post('dob');
            $description=$this->input->post('description');
            $address=$this->input->post('address');
            $city=$this->input->post('city');
            $pincode=$this->input->post('pincode');
			$password=$this->input->post('password');
			if($dob != "")
			{
				$dob = date("Y-m-d",strtotime($dob));
			}
			$accesslevel=$this->input->post('accesslevel');
			$email=$this->input->post('email');
			$contact=$this->input->post('contact');
			$phoneno=$this->input->post('phoneno');
			$google=$this->input->post('google');
			$state=$this->input->post('state');
			$country=$this->input->post('country');
			$status=$this->input->post('status');
			$facebookuserid=$this->input->post('facebookuserid');
			$firstname=$this->input->post('firstname');
			$lastname=$this->input->post('lastname');
			if($this->user_model->create($firstname,$lastname,$dob,$password,$accesslevel,$email,$contact,$status,$facebookuserid,$website,$description,$address,$city,$pincode,$phoneno,$google,$state,$country)==0)
			$data['alerterror']="New user could not be created.";
			else
			$data['alertsuccess']="User created Successfully.";
			
			$data['table']=$this->user_model->viewusers();
			$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
//	function viewusers()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//		$data['table']=$this->user_model->viewusers();
//		$data['page']='viewusers';
//		$data['title']='View Users';
//		$this->load->view('template',$data);
//	}
    
    
    function viewusers()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewusers';
        
        
        $data['base_url'] = site_url("site/viewuserjson");
        
        
		$data['title']='View user';
		$this->load->view('template',$data);
	} 
    
    function viewuserjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
//        SELECT DISTINCT `user`.`id` as `id`,`user`.`firstname` as `firstname`,`user`.`lastname` as `lastname`,`accesslevel`.`name` as `accesslevel`	,`user`.`email` as `email`,`user`.`contact` as `contact`,`user`.`status` as `status`,`user`.`accesslevel` as `access`
//		FROM `user`
//	   INNER JOIN `accesslevel` ON `user`.`accesslevel`=`accesslevel`.`id` 
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`user`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`user`.`firstname`";
        $elements[1]->sort="1";
        $elements[1]->header="First Name";
        $elements[1]->alias="firstname";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`lastname`";
        $elements[2]->sort="1";
        $elements[2]->header="Last Name";
        $elements[2]->alias="lastname";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`accesslevel`.`name`";
        $elements[3]->sort="1";
        $elements[3]->header="Accesslevel";
        $elements[3]->alias="accesslevel";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`user`.`email`";
        $elements[4]->sort="1";
        $elements[4]->header="Email";
        $elements[4]->alias="email";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`user`.`contact`";
        $elements[5]->sort="1";
        $elements[5]->header="Contact";
        $elements[5]->alias="contact";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `user` INNER JOIN `accesslevel` ON `user`.`accesslevel`=`accesslevel`.`id`","WHERE `user`.`accesslevel`=1 OR `user`.`accesslevel`=2");
        
		$this->load->view("json",$data);
	} 
    
    
	function edituser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
		$data['page']='edituser';
		$data['page2']='block/userblock';
		$data['title']='Edit User';
		$this->load->view('template',$data);
	}
	function editusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('password','Password','trim|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('contact','contactno','trim');
		$this->form_validation->set_rules('phoneno','phoneno','trim');
		$this->form_validation->set_rules('google','google','trim');
		$this->form_validation->set_rules('state','state','trim');
		$this->form_validation->set_rules('country','country','trim');
		$this->form_validation->set_rules('website','Website','trim|max_length[50]');
//		$this->form_validation->set_rules('description','Description','trim|');
		$this->form_validation->set_rules('address','Address','trim|');
		$this->form_validation->set_rules('city','City','trim|max_length[30]');
		$this->form_validation->set_rules('pincode','Pincode','trim|max_length[20]');
        
		$this->form_validation->set_rules('fname','First Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('lname','Last Name','trim|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('status','Status','trim');
		$this->form_validation->set_rules('dob','DOB','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
			$data['before']=$this->user_model->beforeedit($this->input->post('id'));
			$data['page']='edituser';
			$data['page2']='block/userblock';
			$data['title']='Edit User';
			$this->load->view('template',$data);
		}
		else
		{
            $website=$this->input->post('website');
            $description=$this->input->post('description');
            $address=$this->input->post('address');
            $city=$this->input->post('city');
            $pincode=$this->input->post('pincode');
			$id=$this->input->post('id');
			$password=$this->input->post('password');
			$dob=$this->input->post('dob');
			if($dob != "")
			{
				$dob = date("Y-m-d",strtotime($dob));
			}
			$accesslevel=$this->input->post('accesslevel');
			$contact=$this->input->post('contact');
			$phoneno=$this->input->post('phoneno');
			$google=$this->input->post('google');
			$state=$this->input->post('state');
			$country=$this->input->post('country');
			$status=$this->input->post('status');
			$facebookuserid=$this->input->post('facebookuserid');
			$fname=$this->input->post('fname');
			$lname=$this->input->post('lname');
			if($this->user_model->edit($id,$fname,$lname,$dob,$password,$accesslevel,$contact,$status,$facebookuserid,$website,$description,$address,$city,$pincode,$phoneno,$google,$state,$country)==0)
			$data['alerterror']="User Editing was unsuccesful";
			else
			$data['alertsuccess']="User edited Successfully.";
			
			$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	
	function deleteuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->deleteuser($this->input->get('id'));
		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="User Deleted Successfully";
		$data['page']='viewusers';
		$data['title']='View Users';
		$this->load->view('template',$data);
	}
	function changeuserstatus()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->changestatus($this->input->get('id'));
		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="Status Changed Successfully";
		$data['redirect']="site/viewusers";
        $data['other']="template=$template";
        $this->load->view("redirect",$data);
	}
    
    
    
    /*-----------------User/Organizer Finctions added by avinash for frontend APIs---------------*/
    public function update()
	{
        $id=$this->input->get('id');
        $firstname=$this->input->get('firstname');
        $lastname=$this->input->get('lastname');
        $password=$this->input->get('password');
        $password=md5($password);
        $email=$this->input->get('email');
        $website=$this->input->get('website');
        $description=$this->input->get('description');
        $eventinfo=$this->input->get('eventinfo');
        $contact=$this->input->get('contact');
        $address=$this->input->get('address');
        $city=$this->input->get('city');
        $pincode=$this->input->get('pincode');
        $dob=$this->input->get('dob');
       // $accesslevel=$this->input->get('accesslevel');
        $accesslevel=2;
        $timestamp=$this->input->get('timestamp');
        $facebookuserid=$this->input->get('facebookuserid');
        $newsletterstatus=$this->input->get('newsletterstatus');
        $status=$this->input->get('status');
        $logo=$this->input->get('logo');
        $showwebsite=$this->input->get('showwebsite');
        $eventsheld=$this->input->get('eventsheld');
        $topeventlocation=$this->input->get('topeventlocation');
        $data['json']=$this->user_model->update($id,$firstname,$lastname,$password,$email,$website,$description,$eventinfo,$contact,$address,$city,$pincode,$dob,$accesslevel,$timestamp,$facebookuserid,$newsletterstatus,$status,$logo,$showwebsite,$eventsheld,$topeventlocation);
        print_r($data);
		//$this->load->view('json',$data);
	}
	public function finduser()
	{
        $data['json']=$this->user_model->viewall();
        print_r($data);
		//$this->load->view('json',$data);
	}
    public function findoneuser()
	{
        $id=$this->input->get('id');
        $data['json']=$this->user_model->viewone($id);
        print_r($data);
		//$this->load->view('json',$data);
	}
    public function deleteoneuser()
	{
        $id=$this->input->get('id');
        $data['json']=$this->user_model->deleteone($id);
		//$this->load->view('json',$data);
	}
    public function login()
    {
        $email=$this->input->get("email");
        $password=$this->input->get("password");
        $data['json']=$this->user_model->login($email,$password);
        //$this->load->view('json',$data);
    }
    public function authenticate()
    {
        $data['json']=$this->user_model->authenticate();
        //$this->load->view('json',$data);
    }
    public function signup()
    {
        $email=$this->input->get_post("email");
        $password=$this->input->get_post("password");
        $data['json']=$this->user_model->signup($email,$password);
        //$this->load->view('json',$data);
        
    }
    public function logout()
    {
        $this->session->sess_destroy();
        $data['json']=true;
        //$this->load->view('json',$data);
    }
    
    
    
    /*-----------------End of User/Organizer functions----------------------------------*/
    
    
    
	//category
    
	function viewcategoryold()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->category_model->viewcategory();
		$data['page']='viewcategory';
		$data['title']='View category';
		$this->load->view('template',$data);
	}
	function viewsubcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		//$data['table']=$this->category_model->viewsubcategory();
        $brandid=$this->input->get('brandid');
        $categoryid=$this->input->get('id');
        $data['check']=$this->category_model->selectedcategory($brandid,$categoryid);
        $data['brandcategoryid']=$this->category_model->getbrandcategoryid($brandid,$categoryid);
		$data['page']='viewsubcategory';
		$data['title']='View Sub-category';
		$this->load->view('template',$data);
	}
     function editsubcategorysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('brandcategoryid','brandcategoryid','trim|required');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$brandid=$this->input->get('brandid');
        $categoryid=$this->input->get('id');
        $data['check']=$this->category_model->selectedcategory($brandid,$categoryid);
        $data['brandcategoryid']=$this->category_model->getbrandcategoryid($brandid,$categoryid);
		$data['page']='viewsubcategory';
		$data['title']='View Sub-category';
		$this->load->view('template',$data);
		}
		else
		{
			$brandcategoryid=$this->input->post('brandcategoryid');
			$men=$this->input->post('men');
			$women=$this->input->post('women');
			$kids=$this->input->post('kids');
            echo "men=".$men;
            if($men=="1")
               {
                $this->category_model->editsubcategorysubmit($brandcategoryid,$men);
                
               }
               else
               {
                   echo "else";
               $this->category_model->deletesubcategorysubmit($brandcategoryid,1);
               }
               
            if($women=="2")
               {
                $this->category_model->editsubcategorysubmit($brandcategoryid,$women);
               }
               else
               {
               $this->category_model->deletesubcategorysubmit($brandcategoryid,2);
               }
            if($kids=="3")
               {
                $this->category_model->editsubcategorysubmit($brandcategoryid,$kids);
               }
               else
               {
               $this->category_model->deletesubcategorysubmit($brandcategoryid,3);
               }
			$brandid=$this->input->get('brandid');
        $categoryid=$this->input->get('id');
        $data['check']=$this->category_model->selectedcategory($brandid,$categoryid);
        $data['brandcategoryid']=$this->category_model->getbrandcategoryid($brandid,$categoryid);
		$data['page']='viewsubcategory';
		$data['title']='View Sub-category';
		$this->load->view('template',$data);
			//$data['other']="template=$template";
			//$this->load->view("redirect",$data);
			/*$data['page']='viewusers';
			$data['title']='View Users';
			$this->load->view('template',$data);*/
		}
	}
	public function createcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->category_model->getstatusdropdown();
		$data['category']=$this->category_model->getcategorydropdown();
		$data['typeofimage']=$this->category_model->gettypeofimagedropdown();
		$data[ 'page' ] = 'createcategory';
		$data[ 'title' ] = 'Create category';
		$this->load->view( 'template', $data );	
	}
   
	function createcategorysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('parent','parent','trim|');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('logo','logo','trim|');
		$this->form_validation->set_rules('typeofimage','typeofimage','trim|');
		$this->form_validation->set_rules('startdateofbanner','startdateofbanner','trim|');
		$this->form_validation->set_rules('enddateofbanner','enddateofbanner','trim|');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->category_model->getstatusdropdown();
			$data['category']=$this->category_model->getcategorydropdown();
            $data['typeofimage']=$this->category_model->gettypeofimagedropdown();
			$data[ 'page' ] = 'createcategory';
			$data[ 'title' ] = 'Create category';
			$this->load->view('template',$data);
		}
		else
		{
			$name=$this->input->post('name');
			$parent=$this->input->post('parent');
			$typeofimage=$this->input->post('typeofimage');
			$startdateofbanner=$this->input->post('startdateofbanner');
			$enddateofbanner=$this->input->post('enddateofbanner');
			$status=$typeofimage;
			$logo=$this->input->post('logo');
            
            $config['upload_path'] = './lib/images/png/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './lib/images/png/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
                
			}
            
            $config['upload_path'] = './lib/images/png/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="banner";
			$banner="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$banner=$uploaddata['file_name'];
                
                $config_r['source_image']   = './lib/images/png/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 1140;
                $config_r['height'] = 160;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $banner=$this->image_lib->dest_image;
                    //return false;
                }
                
                
			}
            
			if($this->category_model->createcategory($name,$parent,$status,$logo,$image,$typeofimage,$banner,$startdateofbanner,$enddateofbanner)==0)
			$data['alerterror']="New category could not be created.";
			else
			$data['alertsuccess']="category  created Successfully.";
			$data['table']=$this->category_model->viewcategory();
			$data['redirect']="site/viewcategory";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
	function editcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->category_model->beforeeditcategory($this->input->get('id'));
		$data['category']=$this->category_model->getcategorydropdown();
		$data[ 'status' ] =$this->category_model->getstatusdropdown();
		$data['typeofimage']=$this->category_model->gettypeofimagedropdown();
		$data['page']='editcategory';
		$data['title']='Edit category';
		$this->load->view('template',$data);
	}
	function editcategorysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('parent','parent','trim|');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('logo','logo','trim|');
		$this->form_validation->set_rules('typeofimage','typeofimage','trim|');
		$this->form_validation->set_rules('startdateofbanner','startdateofbanner','trim|');
		$this->form_validation->set_rules('enddateofbanner','enddateofbanner','trim|');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->category_model->getstatusdropdown();
			$data['category']=$this->category_model->getcategorydropdown();
            $data['typeofimage']=$this->category_model->gettypeofimagedropdown();
			$data['before']=$this->category_model->beforeeditcategory($this->input->post('id'));
			$data['page']='editcategory';
			$data['title']='Edit category';
			$this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			$name=$this->input->post('name');
			$parent=$this->input->post('parent');
			$typeofimage=$this->input->post('typeofimage');
			$startdateofbanner=$this->input->post('startdateofbanner');
			$enddateofbanner=$this->input->post('enddateofbanner');
			$status=$typeofimage;
			$logo=$this->input->post('logo');
			
            $config['upload_path'] = './lib/images/png/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
//			}
            
                
                $config_r['source_image']   = './lib/images/png/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
            }
            
            if($image=="")
            {
                $image=$this->category_model->getcategoryimagebyid($id);
                $image=$image->image;
            }
            
            $config['upload_path'] = './lib/images/png/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="banner";
			$banner="";
			if (  $this->upload->do_upload($filename))
			{
                
				$uploaddata = $this->upload->data();
				$banner=$uploaddata['file_name'];
                
                $config_r['source_image']   = './lib/images/png/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 1140;
                $config_r['height'] = 160;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $banner=$this->image_lib->dest_image;
                    //return false;
                }
                
                
			}
            if($banner=="")
            {
                
                $banner=$this->category_model->getcategorybannerbyid($id);
                $banner=$banner->banner;
                
            }
			if($this->category_model->editcategory($id,$name,$parent,$status,$logo,$image,$typeofimage,$banner,$startdateofbanner,$enddateofbanner)==0)
			$data['alerterror']="category Editing was unsuccesful";
			else
			$data['alertsuccess']="category edited Successfully.";
//			$data['table']=$this->category_model->viewcategory();
			$data['redirect']="site/viewcategory";
////			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			/*$data['page']='viewusers';
			$data['title']='View Users';
			$this->load->view('template',$data);*/
		}
	}
   
	function deletecategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->category_model->deletecategory($this->input->get('id'));
		$data['table']=$this->category_model->viewcategory();
		$data['alertsuccess']="category Deleted Successfully";
		$data['page']='viewcategory';
		$data['title']='View category';
		$this->load->view('template',$data);
	}
	
	
    
	//City
    
    function viewcity()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->city_model->viewcity();
		$data['page']='viewcity';
		$data['title']='View City';
		$this->load->view('template',$data);
	} 
    function viewonecitylocations()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->city_model->viewonecitylocations($this->input->get('cityid'));
		$data['page']='viewonecitylocations';
		$data['title']='View Locations';
		$this->load->view('template',$data);
	}
	public function createcity()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'page' ] = 'createcity';
		$data[ 'title' ] = 'Create city';
//		$data['location']=$this->location_model->getlocation();
//        $data['category']=$this->category_model->getcategory();
//        $data['topic']=$this->topic_model->gettopic();
//		$data['listingtype']=$this->event_model->getlistingtype();
//		$data['remainingticket']=$this->event_model->getremainingticket();
		$this->load->view( 'template', $data );	
	}
    function createcitysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['page']='createcity';
			$data['title']='Create New City';
//			$data['organizer']=$this->organizer_model->getorganizer();
//			$data['listingtype']=$this->event_model->getlistingtype();
//			$data['remainingticket']=$this->event_model->getremainingticket();
			$this->load->view('template',$data);
		}
		else
		{
			$name=$this->input->post('name');
			if($this->city_model->create($name)==0)
			$data['alerterror']="New City could not be created.";
			else
			$data['alertsuccess']="City created Successfully.";
			
			$data['table']=$this->city_model->viewcity();
			$data['redirect']="site/viewcity";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    public function createlocation()
	{
		$access = array("1");
		$this->checkaccess($access);
        $data['cityid']=$this->input->get('cityid');
		$data[ 'page' ] = 'createlocation';
		$data[ 'title' ] = 'Create Location';
//		$data['location']=$this->location_model->getlocation();
//        $data['category']=$this->category_model->getcategory();
//        $data['topic']=$this->topic_model->gettopic();
//		$data['listingtype']=$this->event_model->getlistingtype();
//		$data['remainingticket']=$this->event_model->getremainingticket();
		$this->load->view( 'template', $data );	
	}
    function createlocationsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('pincode','Pincode','trim|required');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['page']='createlocation';
			$data['title']='Create New Location';
//			$data['organizer']=$this->organizer_model->getorganizer();
//			$data['listingtype']=$this->event_model->getlistingtype();
//			$data['remainingticket']=$this->event_model->getremainingticket();
			$this->load->view('template',$data);
		}
		else
		{
			$name=$this->input->post('name');
			$pincode=$this->input->post('pincode');
			$cityid=$this->input->get_post('cityid');
			if($this->city_model->createlocation($name,$cityid,$pincode)==0)
			$data['alerterror']="New Location could not be created.";
			else
			$data['alertsuccess']="Location created Successfully.";
			
			$data['table']=$this->city_model->viewonecitylocations($cityid);
			$data['redirect']="site/viewonecitylocations?cityid=".$cityid;
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    function editcity()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->city_model->beforeedit($this->input->get('id'));
//		$data['organizer']=$this->organizer_model->getorganizer();
//		$data['listingtype']=$this->event_model->getlistingtype();
//		$data['remainingticket']=$this->event_model->getremainingticket();
//		$data['page2']='block/eventblock';
		$data['page']='editcity';
		$data['title']='Edit City';
		$this->load->view('template',$data);
	}
	function editcitysubmit()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
//			$data['organizer']=$this->organizer_model->getorganizer();
//			$data['listingtype']=$this->event_model->getlistingtype();
//			$data['remainingticket']=$this->event_model->getremainingticket();
			$data['before']=$this->city_model->beforeedit($this->input->post('id'));
//			$data['page2']='block/eventblock';
			$data['page']='editcity';
			$data['title']='Edit City';
			$this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			$name=$this->input->post('name');
			if($this->city_model->edit($id,$name)==0)
			$data['alerterror']="City Editing was unsuccesful";
			else
			$data['alertsuccess']="City edited Successfully.";
			
			$data['redirect']="site/viewcity";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	function editlocation()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->city_model->beforeeditlocation($this->input->get('id'));
//		$data['organizer']=$this->organizer_model->getorganizer();
//		$data['listingtype']=$this->event_model->getlistingtype();
//		$data['remainingticket']=$this->event_model->getremainingticket();
//		$data['page2']='block/eventblock';
		$data['page']='editlocation';
		$data['title']='Edit Location';
		$this->load->view('template',$data);
	}
	function editlocationsubmit()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('pincode','Pincode','trim|required');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
//			$data['organizer']=$this->organizer_model->getorganizer();
//			$data['listingtype']=$this->event_model->getlistingtype();
//			$data['remainingticket']=$this->event_model->getremainingticket();
			$data['before']=$this->city_model->beforeedit($this->input->post('id'));
//			$data['page2']='block/eventblock';
			$data['page']='editcity';
			$data['title']='Edit City';
			$this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->get_post('id');
			$cityid=$this->input->get_post('cityid');
			$name=$this->input->post('name');
			$pincode=$this->input->post('pincode');
			if($this->city_model->editlocation($id,$cityid,$name,$pincode)==0)
			$data['alerterror']="Location Editing was unsuccesful";
			else
			$data['alertsuccess']="Location edited Successfully.";
			
			$data['redirect']="site/viewonecitylocations?cityid=".$cityid;
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	
    
	function deletecity()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->city_model->deletecity($this->input->get('id'));
		$data['table']=$this->city_model->viewcity();
		$data['alertsuccess']="City Deleted Successfully";
		$data['page']='viewcity';
		$data['title']='View City';
		$this->load->view('template',$data);
	}
     
	function deletelocation()
	{
		$access = array("1");
		$this->checkaccess($access);
        $cityid=$this->input->get('cityid');
		$this->city_model->deletelocation($this->input->get('id'));
		$data['table']=$this->city_model->viewonecitylocations($cityid);
		$data['alertsuccess']="City Deleted Successfully";
		$data['page']='viewonecitylocations';
		$data['title']='View Location';
		$this->load->view('template',$data);
	}
    
  //listing
    
   
//	function viewlisting()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//		$data['table']=$this->listing_model->viewlisting();
//		$data['page']='viewlisting';
//		$data['title']='View listing';
//		$this->load->view('template',$data);
//	}
    
    
    function viewlisting()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewlisting';
        
        
        $data['base_url'] = site_url("site/viewlistingjson");
        
        
		$data['title']='View listing';
		$this->load->view('template',$data);
	} 
    
    function viewlistingjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
//        SELECT DISTINCT `user`.`id` as `id`,`user`.`firstname` as `firstname`,`user`.`lastname` as `lastname`,`accesslevel`.`name` as `accesslevel`	,`user`.`email` as `email`,`user`.`contact` as `contact`,`user`.`status` as `status`,`user`.`accesslevel` as `access`
//		FROM `user`
//	   INNER JOIN `accesslevel` ON `user`.`accesslevel`=`accesslevel`.`id` 
        
//        SELECT `id`, `name`, `user`, `lat`, `long`, `address`, `city`, `pincode`, `state`, `country`, `description`, `logo`, `contactno`, `email`, `website`, `facebook`, `twitter`, `googleplus`, `yearofestablishment`, `timeofoperation_start`, `timeofoperation_end`, `type`, `credits`, `isverified`, `video`, `deletestatus` FROM `listing` 
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`listing`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listing`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`listing`.`address`";
        $elements[2]->sort="1";
        $elements[2]->header="Address";
        $elements[2]->alias="address";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`listing`.`email`";
        $elements[3]->sort="1";
        $elements[3]->header="Email";
        $elements[3]->alias="email";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`listing`.`contactno`";
        $elements[4]->sort="1";
        $elements[4]->header="Contact No";
        $elements[4]->alias="contactno";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`listing`.`pointer`";
        $elements[5]->sort="1";
        $elements[5]->header="Pointer";
        $elements[5]->alias="pointer";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`listing`.`area`";
        $elements[6]->sort="1";
        $elements[6]->header="Areaid";
        $elements[6]->alias="areaid";
        
        $elements[7]=new stdClass();
        $elements[7]->field="`location`.`name`";
        $elements[7]->sort="1";
        $elements[7]->header="Area";
        $elements[7]->alias="area";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `listing` LEFT OUTER JOIN `location` ON `location`.`id`=`listing`.`area`","WHERE `listing`.`deletestatus`=1 AND `listing`.`type`=1");
        
		$this->load->view("json",$data);
	} 
    
     //$ret = array();
     public function getarray($data,$parents)
    {
        $ret = array();
        if($parents=="")
        {
            $name=$data->name;
        }
        else
        {
            $name=$parents." :: ".ucfirst($data->name);
        }
        $childrens=$data->children;
        $num=sizeof($childrens); 
        if($num>0)
        {
            for($i=0;$i<sizeof($data->children);$i++)
            {
                $newret=$this->getarray($data->children[$i],$name);
                $ret=array_merge($ret,$newret);   
            }
            
            return $ret;
        }
        else
        {
            $sinret=array();
            $sinret[$data->id]=$name;
            
            array_push($ret,$sinret);
            return $ret;
        }
    }
    
    
    
    
	public function createlisting()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->listing_model->getaccesslevels();
//		$data[ 'type' ] =$this->frontend_model->type();
		$data[ 'type' ] =$this->listing_model->gettypedropdown();
		$data[ 'isverified' ] =$this->listing_model->getisverifieddropdown();
		$data[ 'status' ] =$this->listing_model->getstatusdropdown();
		$data[ 'user' ] =$this->listing_model->getuserdropdown();
        $data[ 'city' ] =$this->city_model->getcitydropdown();
        
        $cat=$this->category_model->getcategorytreeforlisting(0);
        $cat1=$this->getarray($cat,"");
        
        $data['category']=$cat1;
//        print_r($data['category']);
//        $this->load->view( 'json', $data );
//        $data[ 'category' ] =$this->category_model->getcategoryforlistingdropdown();
        
        $data[ 'modeofpayment' ] =$this->modeofpayment_model->getmodeofpaymentforlistingdropdown();
        $data[ 'daysofoperation' ] =$this->modeofpayment_model->getdaysofoperationforlistingdropdown();
		$data[ 'page' ] = 'createlisting';
		$data[ 'title' ] = 'Create listing';
		$this->load->view( 'template', $data );	
	}
    public function cattt()
    {
    $data[ 'category' ] =$this->category_model->getcategoryforlistingdropdown();
        print_r($data[ 'category' ]);
    }
	function createlistingsubmit()
	{
//        print_r($_POST);
        
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('user','User','trim');
		$this->form_validation->set_rules('lat','latitude','trim');
		$this->form_validation->set_rules('long','longitude','trim');
		$this->form_validation->set_rules('address','Address','trim|');
		$this->form_validation->set_rules('city','City','trim|max_length[30]');
		$this->form_validation->set_rules('pincode','Pincode','trim|max_length[20]');
		$this->form_validation->set_rules('state','state','trim|max_length[20]');
		$this->form_validation->set_rules('country','country','trim|max_length[20]');
        $this->form_validation->set_rules('description','Description','trim|');
		$this->form_validation->set_rules('contact','contactno','trim');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
        $this->form_validation->set_rules('website','Website','trim');
		$this->form_validation->set_rules('facebookuserid','facebookuserid','trim');
		$this->form_validation->set_rules('googleplus','googleplus','trim');
		$this->form_validation->set_rules('twitter','twitter','trim');
		$this->form_validation->set_rules('yearofestablishment','yearofestablishment','trim');
		$this->form_validation->set_rules('timeofoperation_start','timeofoperation_start','trim');
		$this->form_validation->set_rules('timeofoperation_end','timeofoperation_end','trim');
		$this->form_validation->set_rules('type','type','trim');
//		$this->form_validation->set_rules('credits','credits','trim');
		$this->form_validation->set_rules('isverified','isverified','trim');
		$this->form_validation->set_rules('video','video','trim');
		$this->form_validation->set_rules('pointer','pointer','trim');
		$this->form_validation->set_rules('area','area','trim');
		$this->form_validation->set_rules('mobile','mobile','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['accesslevel']=$this->listing_model->getaccesslevels();
            $data[ 'type' ] =$this->listing_model->gettypedropdown();
            $data[ 'isverified' ] =$this->listing_model->getisverifieddropdown();
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data[ 'city' ] =$this->city_model->getcitydropdown();
            $data[ 'category' ] =$this->category_model->getcategoryforlistingdropdown();
            $data[ 'status' ] =$this->listing_model->getstatusdropdown();
            $data[ 'modeofpayment' ] =$this->modeofpayment_model->getmodeofpaymentforlistingdropdown();
            $data[ 'daysofoperation' ] =$this->modeofpayment_model->getdaysofoperationforlistingdropdown();
            $data[ 'page' ] = 'createlisting';
            $data[ 'title' ] = 'Create listing';
            $this->load->view( 'template', $data );	
		}
		else
		{
            $name=$this->input->post('name');
			$user=$this->input->post('user');
			$lat=$this->input->post('lat');
			$long=$this->input->post('long');
            $address=$this->input->post('address');
            $city=$this->input->post('city');
            $pincode=$this->input->post('pincode');
            $state=$this->input->post('state');
			$country=$this->input->post('country');
            $description=$this->input->post('description');
			$contact=$this->input->post('contact');
			$email=$this->input->post('email');
            $website=$this->input->post('website');
			$facebookuserid=$this->input->post('facebookuserid');
			$googleplus=$this->input->post('googleplus');
			$twitter=$this->input->post('twitter');
			$yearofestablishment=$this->input->post('yearofestablishment');
			$timeofoperation_start=$this->input->post('timeofoperation_start');
			$timeofoperation_end=$this->input->post('timeofoperation_end');
			$type=$this->input->post('type');
			$credits=$this->input->post('credits');
			$isverified=$this->input->post('isverified');
			$video=$this->input->post('video');
			$pointer=$this->input->post('pointer');
			$area=$this->input->post('area');
			$mobile=$this->input->post('mobile');
			$status=$this->input->post('status');
            
            $category=$this->input->post('category');
            $modeofpayment=$this->input->post('modeofpayment');
            $daysofoperation=$this->input->post('daysofoperation');
            $pointerstartdate=$this->input->post('pointerstartdate');
            $pointerenddate=$this->input->post('pointerenddate');
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="logo";
			$logo="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$logo=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $logo=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
            
        $message="<h3>All Details Of Listing</h3><br>Listing Name:'$name' <br>Listing address:'$address' <br>Listing state:'$state' <br>Listing contactno:'$contact' <br>Listing email:'$email' <br>Listing yearofestablishment:'$yearofestablishment' <br>";
//        echo $msg;
        //to user
        $this->load->library('email');
        $this->email->from('avinash@wohlig.com', 'For Any Information');
        $this->email->to($email);
        $this->email->subject('Thank You For Creating A Listing');
        $this->email->message($message);
//echo $message
        $this->email->send();
            
            
			if($this->listing_model->create($name,$user,$lat,$long,$address,$city,$pincode,$state,$country,$description,$contact,$email,$website,$facebookuserid,$googleplus,$twitter,$yearofestablishment,$timeofoperation_start,$timeofoperation_end,$type,$credits,$isverified,$video,$logo,$category,$modeofpayment,$daysofoperation,$pointer,$area,$mobile,$status,$pointerstartdate,$pointerenddate)==0)
			$data['alerterror']="New listing could not be created.";
			else
			$data['alertsuccess']="listing created Successfully.";
			
			$data['table']=$this->listing_model->viewlisting();
			$data['redirect']="site/viewlisting";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
	function editlisting()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'type' ] =$this->listing_model->gettypedropdown();
        $data[ 'isverified' ] =$this->listing_model->getisverifieddropdown();
        $data[ 'user' ] =$this->listing_model->getuserdropdown();
        $data[ 'city' ] =$this->city_model->getcitydropdown();
        $data[ 'area' ] =$this->city_model->getareadropdown();
		$data[ 'status' ] =$this->listing_model->getstatusdropdown();
		$data['before']=$this->listing_model->beforeedit($this->input->get('id'));
//        $data[ 'category' ] =$this->category_model->getcategoryforlistingdropdown();
        
        $cat=$this->category_model->getcategorytreeforlisting(0);
        $cat1=$this->getarray($cat,"");
        
        $data['category']=$cat1;
        
        $data[ 'selectedcategory' ] =$this->category_model->getselectedcategoryforlistingdropdown($this->input->get('id'));
        $data[ 'modeofpayment' ] =$this->modeofpayment_model->getmodeofpaymentforlistingdropdown();
        $data[ 'selectedmodeofpayment' ] =$this->modeofpayment_model->getselectedmodeofpaymentforlistingdropdown($this->input->get('id'));
        $data[ 'daysofoperation' ] =$this->modeofpayment_model->getdaysofoperationforlistingdropdown();
        $data[ 'selecteddaysofoperation' ] =$this->modeofpayment_model->getselecteddaysofoperationforlistingdropdown($this->input->get('id'));
		$data['page']='editlisting';
		$data['page2']='block/listingblock';
		$data['title']='Edit listing';
		$this->load->view('templatewith2',$data);
	}
       
	function editlistingsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('user','User','trim');
		$this->form_validation->set_rules('lat','latitude','trim');
		$this->form_validation->set_rules('long','longitude','trim');
		$this->form_validation->set_rules('address','Address','trim|');
		$this->form_validation->set_rules('city','City','trim|max_length[30]');
		$this->form_validation->set_rules('pincode','Pincode','trim|max_length[20]');
		$this->form_validation->set_rules('state','state','trim|max_length[20]');
		$this->form_validation->set_rules('country','country','trim|max_length[20]');
        $this->form_validation->set_rules('description','Description','trim|');
		$this->form_validation->set_rules('contact','contactno','trim');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
        $this->form_validation->set_rules('website','Website','trim');
		$this->form_validation->set_rules('facebookuserid','facebookuserid','trim');
		$this->form_validation->set_rules('googleplus','googleplus','trim');
		$this->form_validation->set_rules('twitter','twitter','trim');
		$this->form_validation->set_rules('yearofestablishment','yearofestablishment','trim');
		$this->form_validation->set_rules('timeofoperation_start','timeofoperation_start','trim');
		$this->form_validation->set_rules('timeofoperation_end','timeofoperation_end','trim');
		$this->form_validation->set_rules('type','type','trim');
//		$this->form_validation->set_rules('credits','credits','trim');
		$this->form_validation->set_rules('isverified','isverified','trim');
		$this->form_validation->set_rules('video','video','trim');
		$this->form_validation->set_rules('pointer','pointer','trim');
		$this->form_validation->set_rules('area','area','trim');
		$this->form_validation->set_rules('mobile','mobile','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
			$data[ 'type' ] =$this->listing_model->gettypedropdown();
            $data[ 'isverified' ] =$this->listing_model->getisverifieddropdown();
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data[ 'city' ] =$this->city_model->getcitydropdown();
            $data['before']=$this->listing_model->beforeedit($this->input->get('id'));
            $data[ 'category' ] =$this->category_model->getcategoryforlistingdropdown();
            $data[ 'status' ] =$this->listing_model->getstatusdropdown();
            $data[ 'area' ] =$this->city_model->getareadropdown();
            $data[ 'selectedcategory' ] =$this->category_model->getselectedcategoryforlistingdropdown($this->input->get('id'));
            $data[ 'modeofpayment' ] =$this->modeofpayment_model->getmodeofpaymentforlistingdropdown();
            $data[ 'selectedmodeofpayment' ] =$this->modeofpayment_model->getselectedmodeofpaymentforlistingdropdown($this->input->get('id'));
            $data[ 'daysofoperation' ] =$this->modeofpayment_model->getdaysofoperationforlistingdropdown();
            $data[ 'selecteddaysofoperation' ] =$this->modeofpayment_model->getselecteddaysofoperationforlistingdropdown($this->input->get('id'));
            $data['page']='editlisting';
            $data['title']='Edit listing';
            $this->load->view('template',$data);
		}
		else
		{
            $id=$this->input->post('id');
            $name=$this->input->post('name');
			$user=$this->input->post('user');
			$lat=$this->input->post('lat');
			$long=$this->input->post('long');
            $address=$this->input->post('address');
            $city=$this->input->post('city');
            $pincode=$this->input->post('pincode');
            $state=$this->input->post('state');
			$country=$this->input->post('country');
            $description=$this->input->post('description');
			$contact=$this->input->post('contact');
			$email=$this->input->post('email');
            $website=$this->input->post('website');
			$facebookuserid=$this->input->post('facebookuserid');
			$googleplus=$this->input->post('googleplus');
			$twitter=$this->input->post('twitter');
			$yearofestablishment=$this->input->post('yearofestablishment');
			$timeofoperation_start=$this->input->post('timeofoperation_start');
			$timeofoperation_end=$this->input->post('timeofoperation_end');
			$type=$this->input->post('type');
			$credits=$this->input->post('credits');
			$isverified=$this->input->post('isverified');
			$video=$this->input->post('video');
			$pointer=$this->input->post('pointer');
			$area=$this->input->post('area');
			$mobile=$this->input->post('mobile');
			$status=$this->input->post('status');
            
            $category=$this->input->post('category');
            $modeofpayment=$this->input->post('modeofpayment');
            $daysofoperation=$this->input->post('daysofoperation');
            $pointerstartdate=$this->input->post('pointerstartdate');
            $pointerenddate=$this->input->post('pointerenddate');
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="logo";
			$logo="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$logo=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $logo=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            if($logo=="")
            {
                $logo=$this->listing_model->getlogobylistingid($id);
                $logo=$logo->logo;
            }
            
			if($this->listing_model->edit($id,$name,$user,$lat,$long,$address,$city,$pincode,$state,$country,$description,$contact,$email,$website,$facebookuserid,$googleplus,$twitter,$yearofestablishment,$timeofoperation_start,$timeofoperation_end,$type,$credits,$isverified,$video,$logo,$category,$modeofpayment,$daysofoperation,$pointer,$area,$mobile,$status,$pointerstartdate,$pointerenddate)==0)
			$data['alerterror']="listing Editing was unsuccesful";
			else
			$data['alertsuccess']="listing edited Successfully.";
			
			$data['redirect']="site/viewlisting";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deletelisting()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->listing_model->deletelisting($this->input->get('id'));
//		$data['table']=$this->listing_model->viewlisting();
		$data['alertsuccess']="listing Deleted Successfully";
		$data['redirect']="site/viewlisting";
			//$data['other']="template=$template";
		$this->load->view("redirect",$data);
	}
	
    //mode of payment
    
	function viewmodeofpayment()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->modeofpayment_model->viewmodeofpayment();
		$data['page']='viewmodeofpayment';
		$data['title']='View modeofpayment';
		$this->load->view('template',$data);
	}
    
	public function createmodeofpayment()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'page' ] = 'createmodeofpayment';
		$data[ 'title' ] = 'Create modeofpayment';
		$this->load->view( 'template', $data );	
	}
    
	function createmodeofpaymentsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'page' ] = 'createmodeofpayment';
            $data[ 'title' ] = 'Create modeofpayment';
            $this->load->view( 'template', $data );
		}
		else
		{
            $name=$this->input->post('name');
			
			if($this->modeofpayment_model->create($name)==0)
			$data['alerterror']="New Mode of payment could not be created.";
			else
			$data['alertsuccess']="modeofpayment created Successfully.";
			
			$data['table']=$this->modeofpayment_model->viewmodeofpayment();
			$data['redirect']="site/viewmodeofpayment";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    function editmodeofpayment()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->modeofpayment_model->beforeedit($this->input->get('id'));
		$data['page']='editmodeofpayment';
		$data['title']='Edit modeofpayment';
		$this->load->view('template',$data);
	}
	function editmodeofpaymentsubmit()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['before']=$this->modeofpayment_model->beforeedit($this->input->get('id'));
            $data['page']='editmodeofpayment';
            $data['title']='Edit modeofpayment';
            $this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			$name=$this->input->post('name');
			if($this->modeofpayment_model->edit($id,$name)==0)
			$data['alerterror']="modeofpayment Editing was unsuccesful";
			else
			$data['alertsuccess']="modeofpayment edited Successfully.";
			
			$data['redirect']="site/viewmodeofpayment";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deletemodeofpayment()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->modeofpayment_model->deletemodeofpayment($this->input->get('id'));
		$data['table']=$this->modeofpayment_model->viewmodeofpayment();
		$data['alertsuccess']="modeofpayment Deleted Successfully";
		$data['page']='viewmodeofpayment';
		$data['title']='View modeofpayment';
		$this->load->view('template',$data);
	}
    
    
    //enquiry
    
	function viewenquirylistingcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->enquiry_model->viewenquirylistingcategory($this->input->get('email'));
		$data['page']='viewenquirylistingcategory';
		$data['title']='View All Enquiries';
		$this->load->view('template',$data);
	}
    
    
    
    function viewenquiry()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewenquiry';
        
        
        $data['base_url'] = site_url("site/viewenquiryjson");
        
        
		$data['title']='View Enquiry';
		$this->load->view('template',$data);
	} 
    
    function viewenquiryjson()
	{
		$access = array("1");
		$this->checkaccess($access);
//        SELECT `add`.`id`, `add`.`name`, `add`.`image`, `add`.`position`, `add`.`timestamp`, `add`.`fromtimestamp`, `add`.`totimestamp`, `add`.`details`, `add`.`deletestatus`,`position`.`name`as `positionname` 
//        FROM `add`
//        INNER JOIN `position` ON `position`.`id`=`add`.`position`
        
//        SELECT `enquiry`.`id`, `enquiry`.`name`, `enquiry`.`listing`, `enquiry`.`email`, `enquiry`.`phone`, `enquiry`.`user`, `enquiry`.`timestamp`, `enquiry`.`deletestatus`,`listing`.`name` AS `listingname`, `enquiry`.`type`, `category`.`name` AS `categoryname`,`user`.`firstname`,`user`.`lastname`
//        FROM `enquiry` 
//        LEFT OUTER JOIN `listing` ON `listing`.`id`=`enquiry`.`listing`
//        LEFT OUTER JOIN `category` ON `category`.`id`=`enquiry`.`category`
//        LEFT OUTER JOIN `user` ON `user`.`id`=`enquiry`.`user`
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`enquiry`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`enquiry`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Enquiry Name";
        $elements[1]->alias="name";
        
        
        $elements[2]=new stdClass();
        $elements[2]->field="`enquiry`.`email`";
        $elements[2]->sort="1";
        $elements[2]->header="Email";
        $elements[2]->alias="email";
//        
//        $elements[3]=new stdClass();
//        $elements[3]->field="`enquiry`.`phone`";
//        $elements[3]->sort="1";
//        $elements[3]->header="Phone No";
//        $elements[3]->alias="phone";
//        
//        $elements[4]=new stdClass();
//        $elements[4]->field="`enquiry`.`timestamp`";
//        $elements[4]->sort="1";
//        $elements[4]->header="Timestamp";
//        $elements[4]->alias="timestamp";
//        
//        $elements[5]=new stdClass();
//        $elements[5]->field="`enquiry`.`enquiryfrom`";
//        $elements[5]->sort="1";
//        $elements[5]->header="enquiryfrom";
//        $elements[5]->alias="enquiryfrom";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="timestamp";
            $orderorder="DESC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `enquiry`","WHERE 1","GROUP BY `enquiry`.`email`");
        
		$this->load->view("json",$data);
	} 
    
    
	public function createenquiry()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
		$data['typeofenquiry']=$this->enquiry_model->gettypeofenquirydropdown();
        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'user' ] =$this->listing_model->getuserdropdown();
		$data[ 'page' ] = 'createenquiry';
		$data[ 'title' ] = 'Create enquiry';
		$this->load->view( 'template', $data );	
	}
    
	public function createenquirylistingcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
		$data['typeofenquiry']=$this->enquiry_model->gettypeofenquirydropdown();
        $data['category']=$this->category_model->getcategorydropdown();
//		$data[ 'user' ] =$this->listing_model->getuserdropdown();
		$data[ 'page' ] = 'createenquirylistingcategory';
		$data[ 'title' ] = 'Create Enquiry Listing Category';
		$this->load->view( 'template', $data );	
	}
    
	function createenquirylistingcategorysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
//		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('listing','listing','trim');
//		$this->form_validation->set_rules('email','Email','trim|valid_email');
//		$this->form_validation->set_rules('phone','phone','trim');
		$this->form_validation->set_rules('category','category','trim');
		$this->form_validation->set_rules('typeofenquiry','typeofenquiry','trim');
		$this->form_validation->set_rules('comment','comment','trim');
//		$this->form_validation->set_rules('user','user','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
            $id=$this->input->get('id');
			$data['alerterror'] = validation_errors();
			$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
            $data['typeofenquiry']=$this->enquiry_model->gettypeofenquirydropdown();
            $data['category']=$this->category_model->getcategorydropdown();
    //		$data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data[ 'page' ] = 'createenquirylistingcategory';
            $data[ 'title' ] = 'Create Enquiry Listing Category';
            $this->load->view( 'template', $data );		
		}
		else
		{
//            $name=$this->input->post('name');
            $enquiryid=$this->input->post('id');
			$listing=$this->input->post('listing');
//			$email=$this->input->post('email');
//			$phone=$this->input->post('phone');
			$category=$this->input->post('category');
			$typeofenquiry=$this->input->post('typeofenquiry');
			$comment=$this->input->post('comment');
//			$user=$this->input->post('user');
            
			if($this->enquiry_model->createlistingcategory($enquiryid,$listing,$category,$typeofenquiry,$comment)==0)
			$data['alerterror']="New enquiry Listing could not be created.";
			else
			$data['alertsuccess']="enquiry Listing created Successfully.";
			
//			$data['table']=$this->enquiry_model->viewenquiry();
			$data['redirect']="site/viewenquirylistingcategory?email=".$enquiryid;
			//$data['other']="template=$template";
			$this->load->view("redirect2",$data);
		}
	}
    
	function createenquirysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('listing','listing','trim');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('phone','phone','trim');
		$this->form_validation->set_rules('category','category','trim');
		$this->form_validation->set_rules('typeofenquiry','typeofenquiry','trim');
		$this->form_validation->set_rules('comment','comment','trim');
//		$this->form_validation->set_rules('user','user','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
            $data['typeofenquiry']=$this->enquiry_model->gettypeofenquirydropdown();
            $data['category']=$this->category_model->getcategorydropdown();
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data[ 'page' ] = 'createenquiry';
            $data[ 'title' ] = 'Create enquiry';
            $this->load->view( 'template', $data );		
		}
		else
		{
            $name=$this->input->post('name');
			$listing=$this->input->post('listing');
			$email=$this->input->post('email');
			$phone=$this->input->post('phone');
			$category=$this->input->post('category');
			$typeofenquiry=$this->input->post('typeofenquiry');
			$comment=$this->input->post('comment');
//			$user=$this->input->post('user');
            
            if($typeofenquiry==1)
            {
            $listing=$this->listing_model->getallinfooflisting($listing);
            $tolisting= $listing->email;
            $listingname= $listing->name;
            $listingaddress= $listing->address;
            $listingstate= $listing->state;
            $listingcontactno= $listing->contactno;
            $listingemail= $listing->email;
            $listingyearofestablishment= $listing->yearofestablishment;
            
            $usermsg="<h3>All Details Of Listings which you makes an Enquiry</h3><br>Listing Name:'$listingname' <br>Listing address:'$listingaddress' <br>Listing state:'$listingstate' <br>Listing contactno:'$listingcontactno' <br>Listing email:'$listingemail' <br>Listing yearofestablishment:'$listingyearofestablishment' <br>";
            
            $this->load->library('email');
            $this->email->from('avinash@wohlig.com', 'For Any Information To User');
            $this->email->to($email);
            $this->email->subject('Listing Details');
            $this->email->message($usermsg);

            $this->email->send();
//            echo $this->email->print_debugger();
            
            //to listing
//            $firstname=$user->firstname;
//            $lastname=$user->lastname;
//            $email=$user->email;
//            $contact=$user->contact;
            $listingmsg="<h3>All Details Of user</h3><br>user Name:'$name' <br>user Email:'$email' <br>user contact:'$phone'<br>user Comment:'$comment'";

            $this->load->library('email');
            $this->email->from('avinash@wohlig.com', 'For Any Information Listing');
            $this->email->to($listingemail);
            $this->email->subject('User Details');
            $this->email->message($listingmsg);

            $this->email->send();

            }
            if($typeofenquiry==2)
            {
                $listingdetails=$this->listing_model->getallinfooflistingbycategory($category);
                foreach($listingdetails as $listing)
                {
                    $tolisting= $listing->email;
                    $listingname= $listing->name;
                    $listingaddress= $listing->address;
                    $listingstate= $listing->state;
                    $listingcontactno= $listing->contactno;
                    $listingemail= $listing->email;
                    $listingyearofestablishment= $listing->yearofestablishment;
                    
                    
                    $usermsg="<h3>All Details Of Listings which you makes an Enquiry</h3><br>Listing Name:'$listingname' <br>Listing address:'$listingaddress' <br>Listing state:'$listingstate' <br>Listing contactno:'$listingcontactno' <br>Listing email:'$listingemail' <br>Listing yearofestablishment:'$listingyearofestablishment' <br>";

                    $this->load->library('email');
                    $this->email->from('avinash@wohlig.com', 'For Any Information To User');
                    $this->email->to($email);
                    $this->email->subject('Listing Details');
                    $this->email->message($usermsg);

                    $this->email->send();
                    
                    $listingmsg="<h3>All Details Of user</h3><br>user Name:'$name' <br>user Email:'$email' <br>user contact:'$phone'<br>user Comment:'$comment'";

                    $this->load->library('email');
                    $this->email->from('avinash@wohlig.com', 'For Any Information Listing');
                    $this->email->to($listingemail);
                    $this->email->subject('User Details');
                    $this->email->message($listingmsg);

                    $this->email->send();
                }
            
            }
            
			if($this->enquiry_model->create($name,$listing,$email,$phone,$category,$typeofenquiry,$comment)==0)
			$data['alerterror']="New enquiry could not be created.";
			else
			$data['alertsuccess']="enquiry created Successfully.";
			
//			$data['table']=$this->enquiry_model->viewenquiry();
			$data['redirect']="site/viewenquiry";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    
	function editenquiry()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
        $data['typeofenquiry']=$this->enquiry_model->gettypeofenquirydropdown();
        $data['category']=$this->category_model->getcategorydropdown();
		$data['before']=$this->enquiry_model->beforeedit($this->input->get('id'));
		$data[ 'user' ] =$this->listing_model->getuserdropdown();
		$data['page']='editenquiry';
		$data['title']='Edit enquiry';
		$this->load->view('template',$data);
	}
       
	function editenquirysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
//		$this->form_validation->set_rules('listing','listing','trim');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('phone','phone','trim');
//		$this->form_validation->set_rules('category','category','trim');
//		$this->form_validation->set_rules('typeofenquiry','typeofenquiry','trim');
//		$this->form_validation->set_rules('comment','comment','trim');
//		$this->form_validation->set_rules('user','user','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
            $data['before']=$this->enquiry_model->beforeedit($this->input->get('id'));
            $data['typeofenquiry']=$this->enquiry_model->gettypeofenquirydropdown();
            $data['category']=$this->category_model->getcategorydropdown();
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data['page']='editenquiry';
            $data['title']='Edit enquiry';
            $this->load->view('template',$data);
		}
		else
		{
            $id=$this->input->post('id');
            
            $name=$this->input->post('name');
			$listing=$this->input->post('listing');
			$email=$this->input->post('email');
			$phone=$this->input->post('phone');
			$category=$this->input->post('category');
			$typeofenquiry=$this->input->post('typeofenquiry');
			$comment=$this->input->post('comment');
			$user=$this->input->post('user');
            
			if($this->enquiry_model->edit($id,$name,$email,$phone)==0)
			$data['alerterror']="enquiry Editing was unsuccesful";
			else
			$data['alertsuccess']="enquiry edited Successfully.";
			
			$data['redirect']="site/viewenquiry";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deleteenquiry()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->enquiry_model->deleteenquiry($this->input->get('id'));
//		$data['table']=$this->enquiry_model->viewenquiry();
//		$data['alertsuccess']="enquiry Deleted Successfully";
//		$data['page']='viewenquiry';
//		$data['title']='View enquiry';
//		$this->load->view('template',$data);
        $data['redirect']="site/viewenquiry";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
	}
	function deleteenquirylistingcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->enquiry_model->deleteenquirylistingcategory($this->input->get('enquirylistingcategoryid'));
		$data['table']=$this->enquiry_model->viewenquirylistingcategory($this->input->get('email'));
		$data['alertsuccess']="Enquiry Listing Deleted Successfully";
		$data['page']='viewenquirylistingcategory';
		$data['title']='View Listing or Category Enquiries';
		$this->load->view('template',$data);
	}
    
    //specialoffers
    
//	function viewspecialoffer()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//		$data['table']=$this->specialoffer_model->viewspecialoffer();
//		$data['page']='viewspecialoffer';
//		$data['title']='View specialoffer';
//		$this->load->view('template',$data);
//	}
    
    function viewspecialoffer()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewspecialoffer';
        
        
        $data['base_url'] = site_url("site/viewspecialofferjson");
        
        
		$data['title']='View specialoffer';
		$this->load->view('template',$data);
	} 
    
    function viewspecialofferjson()
	{
		$access = array("1");
		$this->checkaccess($access);
//        SELECT `billing`.`id`, `billing`.`listing`, `billing`.`user`, `billing`.`timestamp`, `billing`.`paymenttype`, `billing`.`amount`, `billing`.`period`, `billing`.`credits`, `billing`.`payedto`, `billing`.`deletestatus` ,`user`.`firstname`,`user`.`lastname`,`listing`.`name` AS `listingname`,`paymenttype`.`name` AS `paymenttypename`
//FROM `billing` 
//INNER JOIN `user` ON `user`.`id` = `billing`.`user`
//INNER JOIN `listing` ON `listing`.`id` = `billing`.`listing`
//INNER JOIN `paymenttype` ON `paymenttype`.`id` = `billing`.`paymenttype`
        
//        SELECT `specialoffer`.`id`, `specialoffer`.`name`, `specialoffer`.`category`, `specialoffer`.`email`, `specialoffer`.`phone`, `specialoffer`.`timestamp`, `specialoffer`.`deletestatus`,`category`.`name` AS `categoryname` 
//        FROM `specialoffer` 
//        LEFT OUTER JOIN `category` ON `category`.`id`=`specialoffer`.`category`
            
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`specialoffer`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`category`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Category";
        $elements[1]->alias="categoryname";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`specialoffer`.`email`";
        $elements[2]->sort="1";
        $elements[2]->header="Email";
        $elements[2]->alias="email";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`specialoffer`.`phone`";
        $elements[3]->sort="1";
        $elements[3]->header="Phone No";
        $elements[3]->alias="phone";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`specialoffer`.`timestamp`";
        $elements[4]->sort="1";
        $elements[4]->header="Timestamp";
        $elements[4]->alias="timestamp";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`specialoffer`.`name`";
        $elements[5]->sort="1";
        $elements[5]->header="Offer";
        $elements[5]->alias="name";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"  FROM `specialoffer` LEFT OUTER JOIN `category` ON `category`.`id`=`specialoffer`.`category`");
        
		$this->load->view("json",$data);
	} 
    
    
    
	public function createspecialoffer()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'category' ] =$this->category_model->getcategorydropdown();
		$data[ 'listing' ] =$this->listing_model->getlistingforspecialofferdropdown();
		$data[ 'page' ] = 'createspecialoffer';
		$data[ 'title' ] = 'Create specialoffer';
		$this->load->view( 'template', $data );	
	}
    
	function createspecialoffersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('specialoffer','specialoffer','trim');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('phone','phone','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
            $data[ 'category' ] =$this->category_model->getcategorydropdown();
//			$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
            $data[ 'listing' ] =$this->listing_model->getlistingforspecialofferdropdown();
            $data[ 'page' ] = 'createspecialoffer';
            $data[ 'title' ] = 'Create specialoffer';
            $this->load->view( 'template', $data );		
		}
		else
		{
            $name=$this->input->post('name');
			$category=$this->input->post('category');
			$email=$this->input->post('email');
			$phone=$this->input->post('phone');
			$listing=$this->input->post('listing');
            
			if($this->specialoffer_model->create($name,$category,$email,$phone,$listing)==0)
			$data['alerterror']="New specialoffer could not be created.";
			else
			$data['alertsuccess']="specialoffer created Successfully.";
			
			$data['table']=$this->specialoffer_model->viewspecialoffer();
			$data['redirect']="site/viewspecialoffer";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
	function editspecialoffer()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'category' ] =$this->category_model->getcategorydropdown();
		$data[ 'listing' ] =$this->listing_model->getlistingforspecialofferdropdown();
		$data[ 'selectedlisting' ] =$this->specialoffer_model->getselectedlistingforspecialofferdropdown($this->input->get('id'));
		$data['before']=$this->specialoffer_model->beforeedit($this->input->get('id'));
		$data['page']='editspecialoffer';
		$data['title']='Edit specialoffer';
		$this->load->view('template',$data);
	}
       
	function editspecialoffersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('category','category','trim');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('phone','phone','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'category' ] =$this->category_model->getcategorydropdown();
            $data['before']=$this->specialoffer_model->beforeedit($this->input->get('id'));
            $data['page']='editspecialoffer';
            $data['title']='Edit specialoffer';
            $this->load->view('template',$data);
		}
		else
		{
            $id=$this->input->post('id');
            
            $name=$this->input->post('name');
			$category=$this->input->post('category');
			$email=$this->input->post('email');
			$phone=$this->input->post('phone');
			$listing=$this->input->post('listing');
            
			if($this->specialoffer_model->edit($id,$name,$category,$email,$phone,$listing)==0)
			$data['alerterror']="specialoffer Editing was unsuccesful";
			else
			$data['alertsuccess']="specialoffer edited Successfully.";
			
			$data['redirect']="site/viewspecialoffer";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deletespecialoffer()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->specialoffer_model->deletespecialoffer($this->input->get('id'));
		$data['table']=$this->specialoffer_model->viewspecialoffer();
		$data['alertsuccess']="specialoffer Deleted Successfully";
		$data['page']='viewspecialoffer';
		$data['title']='View specialoffer';
		$this->load->view('template',$data);
	}
    
    //paymenttype
    
	function viewpaymenttype()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->paymenttype_model->viewpaymenttype();
		$data['page']='viewpaymenttype';
		$data['title']='View paymenttype';
		$this->load->view('template',$data);
	}
    
	public function createpaymenttype()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'page' ] = 'createpaymenttype';
		$data[ 'title' ] = 'Create paymenttype';
		$this->load->view( 'template', $data );	
	}
    
	function createpaymenttypesubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'page' ] = 'createpaymenttype';
            $data[ 'title' ] = 'Create paymenttype';
            $this->load->view( 'template', $data );
		}
		else
		{
            $name=$this->input->post('name');
			
			if($this->paymenttype_model->create($name)==0)
			$data['alerterror']="New Mode of payment could not be created.";
			else
			$data['alertsuccess']="paymenttype created Successfully.";
			
			$data['table']=$this->paymenttype_model->viewpaymenttype();
			$data['redirect']="site/viewpaymenttype";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    function editpaymenttype()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->paymenttype_model->beforeedit($this->input->get('id'));
		$data['page']='editpaymenttype';
		$data['title']='Edit paymenttype';
		$this->load->view('template',$data);
	}
	function editpaymenttypesubmit()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['before']=$this->paymenttype_model->beforeedit($this->input->get('id'));
            $data['page']='editpaymenttype';
            $data['title']='Edit paymenttype';
            $this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			$name=$this->input->post('name');
			if($this->paymenttype_model->edit($id,$name)==0)
			$data['alerterror']="paymenttype Editing was unsuccesful";
			else
			$data['alertsuccess']="paymenttype edited Successfully.";
			
			$data['redirect']="site/viewpaymenttype";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deletepaymenttype()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->paymenttype_model->deletepaymenttype($this->input->get('id'));
		$data['table']=$this->paymenttype_model->viewpaymenttype();
		$data['alertsuccess']="paymenttype Deleted Successfully";
		$data['page']='viewpaymenttype';
		$data['title']='View paymenttype';
		$this->load->view('template',$data);
	}
    
    //billing
    
//	function viewbilling()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//		$data['table']=$this->billing_model->viewbilling();
//		$data['page']='viewbilling';
//		$data['title']='View billing';
//		$this->load->view('template',$data);
//	}
    
    
    function viewbilling()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewbilling';
        
        
        $data['base_url'] = site_url("site/viewbillingjson");
        
        
		$data['title']='View billing';
		$this->load->view('template',$data);
	} 
    
    function viewbillingjson()
	{
		$access = array("1");
		$this->checkaccess($access);
//        SELECT `billing`.`id`, `billing`.`listing`, `billing`.`user`, `billing`.`timestamp`, `billing`.`paymenttype`, `billing`.`amount`, `billing`.`period`, `billing`.`credits`, `billing`.`payedto`, `billing`.`deletestatus` ,`user`.`firstname`,`user`.`lastname`,`listing`.`name` AS `listingname`,`paymenttype`.`name` AS `paymenttypename`
//FROM `billing` 
//INNER JOIN `user` ON `user`.`id` = `billing`.`user`
//INNER JOIN `listing` ON `listing`.`id` = `billing`.`listing`
//INNER JOIN `paymenttype` ON `paymenttype`.`id` = `billing`.`paymenttype`
        
//        SELECT `id`, `name`, `user`, `lat`, `long`, `address`, `city`, `pincode`, `state`, `country`, `description`, `logo`, `contactno`, `email`, `website`, `facebook`, `twitter`, `googleplus`, `yearofestablishment`, `timeofoperation_start`, `timeofoperation_end`, `type`, `credits`, `isverified`, `video`, `deletestatus` FROM `listing` 
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`billing`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listing`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Listing";
        $elements[1]->alias="listingname";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`firstname`";
        $elements[2]->sort="1";
        $elements[2]->header="User";
        $elements[2]->alias="firstname";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`paymenttype`.`name`";
        $elements[3]->sort="1";
        $elements[3]->header="Payment Type";
        $elements[3]->alias="paymenttype";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`billing`.`amount`";
        $elements[4]->sort="1";
        $elements[4]->header="Amount";
        $elements[4]->alias="amount";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`billing`.`period`";
        $elements[5]->sort="1";
        $elements[5]->header="Period";
        $elements[5]->alias="period";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`billing`.`credits`";
        $elements[6]->sort="1";
        $elements[6]->header="Credits";
        $elements[6]->alias="credits";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `billing` INNER JOIN `user` ON `user`.`id` = `billing`.`user` INNER JOIN `listing` ON `listing`.`id` = `billing`.`listing` INNER JOIN `paymenttype` ON `paymenttype`.`id` = `billing`.`paymenttype`");
        
		$this->load->view("json",$data);
	} 
    
    
    
    
	public function createbilling()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'paymenttype' ] =$this->paymenttype_model->getpaymenttypedropdown();
		$data[ 'period' ] =$this->paymenttype_model->getperioddropdown();
		$data[ 'user' ] =$this->listing_model->getuserdropdown();
		$data[ 'listing' ] =$this->listing_model->getlistingdropdown();
		$data[ 'page' ] = 'createbilling';
		$data[ 'title' ] = 'Create billing';
		$this->load->view( 'template', $data );	
	}
    
	function createbillingsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('listing','Listing','trim');
		$this->form_validation->set_rules('user','User','trim');
		$this->form_validation->set_rules('paymenttype','paymenttype','trim|');
		$this->form_validation->set_rules('amount','amount','trim');
		$this->form_validation->set_rules('period','period','trim');
		$this->form_validation->set_rules('credits','credits','trim');
		$this->form_validation->set_rules('payedto','payedto','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'paymenttype' ] =$this->paymenttype_model->getpaymenttypedropdown();
            $data[ 'period' ] =$this->paymenttype_model->getperioddropdown();
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data[ 'listing' ] =$this->listing_model->getlistingdropdown();
            $data[ 'page' ] = 'createbilling';
            $data[ 'title' ] = 'Create billing';
            $this->load->view( 'template', $data );	
		}
		else
		{
            $listing=$this->input->post('listing');
			$user=$this->input->post('user');
			$paymenttype=$this->input->post('paymenttype');
			$amount=$this->input->post('amount');
            $period=$this->input->post('period');
            $credits=$this->input->post('credits');
            $payedto=$this->input->post('payedto');
            $startdate=$this->input->post('startdate');
            $enddate=$this->input->post('enddate');
            
			if($this->billing_model->create($listing,$user,$paymenttype,$amount,$period,$credits,$payedto,$startdate,$enddate)==0)
			$data['alerterror']="New billing could not be created.";
			else
			$data['alertsuccess']="billing created Successfully.";
			
			$data['table']=$this->billing_model->viewbilling();
			$data['redirect']="site/viewbilling";
			$this->load->view("redirect",$data);
		}
	}
    
	function editbilling()
	{
		$access = array("1");
		$this->checkaccess($access);
        $data[ 'paymenttype' ] =$this->paymenttype_model->getpaymenttypedropdown();
        $data[ 'period' ] =$this->paymenttype_model->getperioddropdown();
        $data[ 'user' ] =$this->listing_model->getuserdropdown();
        $data[ 'listing' ] =$this->listing_model->getlistingdropdown();
		$data['before']=$this->billing_model->beforeedit($this->input->get('id'));
		$data['page']='editbilling';
		$data['title']='Edit billing';
		$this->load->view('template',$data);
	}
       
	function editbillingsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('listing','Listing','trim');
		$this->form_validation->set_rules('user','User','trim');
		$this->form_validation->set_rules('paymenttype','paymenttype','trim|');
		$this->form_validation->set_rules('amount','amount','trim');
		$this->form_validation->set_rules('period','period','trim');
		$this->form_validation->set_rules('credits','credits','trim');
		$this->form_validation->set_rules('payedto','payedto','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'paymenttype' ] =$this->paymenttype_model->getpaymenttypedropdown();
            $data[ 'period' ] =$this->paymenttype_model->getperioddropdown();
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data[ 'listing' ] =$this->listing_model->getlistingdropdown();
            $data['before']=$this->billing_model->beforeedit($this->input->get('id'));
            $data['page']='editbilling';
            $data['title']='Edit billing';
            $this->load->view('template',$data);
		}
		else
		{
            $id=$this->input->post('id');
            $listing=$this->input->post('listing');
			$user=$this->input->post('user');
			$paymenttype=$this->input->post('paymenttype');
			$amount=$this->input->post('amount');
            $period=$this->input->post('period');
            $credits=$this->input->post('credits');
            $payedto=$this->input->post('payedto');
            $startdate=$this->input->post('startdate');
            $enddate=$this->input->post('enddate');
            
			if($this->billing_model->edit($id,$listing,$user,$paymenttype,$amount,$period,$credits,$payedto,$startdate,$enddate)==0)
			$data['alerterror']="billing Editing was unsuccesful";
			else
			$data['alertsuccess']="billing edited Successfully.";
			
			$data['redirect']="site/viewbilling";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deletebilling()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->billing_model->deletebilling($this->input->get('id'));
		$data['table']=$this->billing_model->viewbilling();
		$data['alertsuccess']="billing Deleted Successfully";
		$data['page']='viewbilling';
		$data['title']='View billing';
		$this->load->view('template',$data);
	}
    
    //position
    
	function viewposition()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->position_model->viewposition();
		$data['page']='viewposition';
		$data['title']='View position';
		$this->load->view('template',$data);
	}
    
	public function createposition()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'page' ] = 'createposition';
		$data[ 'title' ] = 'Create position';
		$this->load->view( 'template', $data );	
	}
    
	function createpositionsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('height','height','trim');
		$this->form_validation->set_rules('width','width','trim');
		
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'page' ] = 'createposition';
            $data[ 'title' ] = 'Create position';
            $this->load->view( 'template', $data );
		}
		else
		{
            $name=$this->input->post('name');
            $height=$this->input->post('height');
            $width=$this->input->post('width');
			
			if($this->position_model->create($name,$height,$width)==0)
			$data['alerterror']="New Mode of payment could not be created.";
			else
			$data['alertsuccess']="position created Successfully.";
			
			$data['table']=$this->position_model->viewposition();
			$data['redirect']="site/viewposition";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    function editposition()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->position_model->beforeedit($this->input->get('id'));
		$data['page']='editposition';
		$data['title']='Edit position';
		$this->load->view('template',$data);
	}
	function editpositionsubmit()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('height','height','trim');
		$this->form_validation->set_rules('width','width','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['before']=$this->position_model->beforeedit($this->input->get('id'));
            $data['page']='editposition';
            $data['title']='Edit position';
            $this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			$name=$this->input->post('name');
            $height=$this->input->post('height');
            $width=$this->input->post('width');
			if($this->position_model->edit($id,$name,$height,$width)==0)
			$data['alerterror']="position Editing was unsuccesful";
			else
			$data['alertsuccess']="position edited Successfully.";
			
			$data['redirect']="site/viewposition";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deleteposition()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->position_model->deleteposition($this->input->get('id'));
		$data['table']=$this->position_model->viewposition();
		$data['alertsuccess']="position Deleted Successfully";
		$data['page']='viewposition';
		$data['title']='View position';
		$this->load->view('template',$data);
	}
    
    //add
    
//	function viewadd()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//		$data['table']=$this->add_model->viewadd();
//		$data['page']='viewadd';
//		$data['title']='View add';
//		$this->load->view('template',$data);
//	}
    
    
    function viewadd()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewadd';
        
        
        $data['base_url'] = site_url("site/viewaddjson");
        
        
		$data['title']='View add';
		$this->load->view('template',$data);
	} 
    
    function viewaddjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
//        SELECT `specialoffer`.`id`, `specialoffer`.`name`, `specialoffer`.`category`, `specialoffer`.`email`, `specialoffer`.`phone`, `specialoffer`.`timestamp`, `specialoffer`.`deletestatus`,`category`.`name` AS `categoryname` 
//        FROM `specialoffer` 
//        LEFT OUTER JOIN `category` ON `category`.`id`=`specialoffer`.`category`
            
//        SELECT `add`.`id`, `add`.`name`, `add`.`image`, `add`.`position`, `add`.`timestamp`, `add`.`fromtimestamp`, `add`.`totimestamp`, `add`.`details`, `add`.`deletestatus`,`position`.`name`as `positionname` 
//        FROM `add`
//        INNER JOIN `position` ON `position`.`id`=`add`.`position`
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`add`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`add`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Add Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`position`.`name`";
        $elements[2]->sort="1";
        $elements[2]->header="Position";
        $elements[2]->alias="positionname";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`add`.`fromtimestamp`";
        $elements[3]->sort="1";
        $elements[3]->header="fromtimestamp";
        $elements[3]->alias="fromtimestamp";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`add`.`totimestamp`";
        $elements[4]->sort="1";
        $elements[4]->header="ToTimestamp";
        $elements[4]->alias="totimestamp";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`add`.`details`";
        $elements[5]->sort="1";
        $elements[5]->header="Details";
        $elements[5]->alias="details";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"  FROM `add` INNER JOIN `position` ON `position`.`id`=`add`.`position`");
        
		$this->load->view("json",$data);
	} 
    
    
	public function createadd()
	{
		$access = array("1");
		$this->checkaccess($access);
        $data[ 'position' ] =$this->position_model->getpositiondropdown();
		$data[ 'page' ] = 'createadd';
		$data[ 'title' ] = 'Create add';
		$this->load->view( 'template', $data );	
	}
    
	function createaddsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('position','position','trim');
		$this->form_validation->set_rules('fromtimestamp','fromtimestamp','trim');
		$this->form_validation->set_rules('totimestamp','totimestamp','trim');
		$this->form_validation->set_rules('details','details','trim');
		
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'position' ] =$this->position_model->getpositiondropdown();
            $data[ 'page' ] = 'createadd';
            $data[ 'title' ] = 'Create add';
            $this->load->view( 'template', $data );	
		}
		else
		{
            $name=$this->input->post('name');
            $position=$this->input->post('position');
            $fromtimestamp=$this->input->post('fromtimestamp');
            $totimestamp=$this->input->post('totimestamp');
            $details=$this->input->post('details');
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
			if($this->add_model->create($name,$position,$fromtimestamp,$totimestamp,$details,$image)==0)
			$data['alerterror']="New Add could not be created.";
			else
			$data['alertsuccess']="add created Successfully.";
			
			$data['table']=$this->add_model->viewadd();
			$data['redirect']="site/viewadd";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    function editadd()
	{
		$access = array("1");
		$this->checkaccess($access);
        $data[ 'position' ] =$this->position_model->getpositiondropdown();
		$data['before']=$this->add_model->beforeedit($this->input->get('id'));
		$data['page']='editadd';
		$data['title']='Edit add';
		$this->load->view('template',$data);
	}
	function editaddsubmit()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('position','position','trim');
		$this->form_validation->set_rules('fromtimestamp','fromtimestamp','trim');
		$this->form_validation->set_rules('totimestamp','totimestamp','trim');
		$this->form_validation->set_rules('details','details','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'position' ] =$this->position_model->getpositiondropdown();
            $data['before']=$this->add_model->beforeedit($this->input->get('id'));
            $data['page']='editadd';
            $data['title']='Edit add';
            $this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
            $name=$this->input->post('name');
            $position=$this->input->post('position');
            $fromtimestamp=$this->input->post('fromtimestamp');
            $totimestamp=$this->input->post('totimestamp');
            $details=$this->input->post('details');
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
            if($image=="")
            {
            $image=$this->add_model->getaddimagebyid($id);
               // print_r($image);
                $image=$image->image;
            }
			if($this->add_model->edit($id,$name,$position,$fromtimestamp,$totimestamp,$details,$image)==0)
			$data['alerterror']="add Editing was unsuccesful";
			else
			$data['alertsuccess']="add edited Successfully.";
			
			$data['redirect']="site/viewadd";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
    
	function deleteadd()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->add_model->deleteadd($this->input->get('id'));
		$data['table']=$this->add_model->viewadd();
		$data['alertsuccess']="add Deleted Successfully";
		$data['page']='viewadd';
		$data['title']='View add';
		$this->load->view('template',$data);
	}
    
    //listingimages
    
	function viewlistingimages()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->listing_model->viewlistingimages($this->input->get('id'));
		$data['before']=$this->listing_model->beforeedit($this->input->get('id'));
		$data['page']='viewlistingimages';
		$data['page2']='block/listingblock';
		$data['title']='View listingimages';
		$this->load->view('templatewith2',$data);
	}
    
	public function createlistingimages()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['listing']=$this->input->get('id');
        $data['before']=$this->listing_model->beforeedit($this->input->get('id'));
//		$data['page']='viewlistingimages';
		$data[ 'page' ] = 'createlistingimages';
		$data['page2']='block/listingblock';
		$data[ 'title' ] = 'Create listingimage';
		$this->load->view( 'templatewith2', $data );	
	}
    
	function createlistingimagessubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('order','order','trim|required');
		
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$access = array("1");
            $this->checkaccess($access);
            $data['listing']=$this->input->get('id');
            $data[ 'page' ] = 'createlistingimages';
            $data[ 'title' ] = 'Create listingimage';
            $this->load->view( 'template', $data );		
		}
		else
		{
            $order=$this->input->post('order');
            $listing=$this->input->post('listing');
            
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
			}
            //echo $image;
			if($this->listing_model->createlistingimages($listing,$order,$image)==0)
			$data['alerterror']="New Image could not be created.";
			else
			$data['alertsuccess']="Image created Successfully.";
			
			$data['table']=$this->listing_model->viewlistingimages($listing);
            $data['redirect']="site/viewlistingimages?id=".$listing;
			$this->load->view("redirect2",$data);
//            $data['page']='viewlistingimages';
//            $data['title']='View listingimages';
//            $this->load->view('template',$data);
		}
	}
    
	public function editlistingimages()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['listing']=$this->input->get('listingid');
        $data['before']=$this->listing_model->beforeedit($this->input->get('listingid'));
        $data['beforelistingimages']=$this->listing_model->beforeeditlistingimages($this->input->get('id'));
//		$data['page']='viewlistingimages';
		$data[ 'page' ] = 'editlistingimages';
		$data['page2']='block/listingblock';
		$data[ 'title' ] = 'Create listingimage';
		$this->load->view( 'templatewith2', $data );	
	}
    
	function editlistingimagessubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('order','order','trim|required');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['listing']=$this->input->get('listingid');
            $data['before']=$this->listing_model->beforeedit($this->input->get('listingid'));
            $data['beforelistingimages']=$this->listing_model->beforeeditlistingimages($this->input->get('id'));
    //		$data['page']='viewlistingimages';
            $data[ 'page' ] = 'editlistingimages';
            $data['page2']='block/listingblock';
            $data[ 'title' ] = 'Create listingimage';
            $this->load->view( 'templatewith2', $data );
		}
		else
		{
			$id=$this->input->post('id');
//            echo $id;
            $order=$this->input->post('order');
            $listing=$this->input->post('listing');
//            echo $listing;
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
			}
            
            if($image=="")
            {
            $image=$this->listing_model->getlistingimagesbyid($id);
            $image=$image->image;
            }
//            echo $image;
			if($this->listing_model->editlistingimages($id,$order,$image,$listing)==0)
			$data['alerterror']="Image Editing was unsuccesful";
			else
			$data['alertsuccess']="Image edited Successfully.";
			
			$data['table']=$this->listing_model->viewlistingimages($listing);
            $data['redirect']="site/viewlistingimages?id=".$listing;
			$this->load->view("redirect2",$data);
			
		}
	}
    
	function deletelistingimages()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->listing_model->deletelistingimages($this->input->get('id'));
        $listing=$this->input->get('listingid');
        $data['alerterror']="Image Deleted Successfully.";
		$data['table']=$this->listing_model->viewlistingimages($listing);
        $data['redirect']="site/viewlistingimages?id=".$listing;
        $this->load->view("redirect2",$data);
	}
    
    
     //email
    
    public function sendmailtoavi()
    {
        $email='avinash@wohlig.com';
        $this->load->library('email');
        //$email='patiljagruti181@gmail.com,jagruti@wohlig.com';
        $this->email->from('avinash@wohlig.com', 'For Any Information');
        $this->email->to($email);
        $this->email->subject('Email Test');
        $data['link']='google.com';
        $this->email->message($this->load->view('emailmsg',$data,true));

        $this->email->send();

        echo $this->email->print_debugger();
    }
    
    public function sendemail()
    {
        $userid=$this->input->get('userid');
        $listingid=$this->input->get('listingid');
        $user=$this->user_model->getallinfoofuser($userid);
//        print_r($user);
        $touser=$user->email;
        $listing=$this->listing_model->getallinfooflisting($listingid);
//        print_r($user);
        $tolisting= $listing->email;
        $listingname= $listing->name;
        $listingaddress= $listing->address;
        $listingstate= $listing->state;
        $listingcontactno= $listing->contactno;
        $listingemail= $listing->email;
        $listingyearofestablishment= $listing->yearofestablishment;
        $usermsg="<h3>All Details Of Listing</h3><br>Listing Name:'$listingname' <br>Listing address:'$listingaddress' <br>Listing state:'$listingstate' <br>Listing contactno:'$listingcontactno' <br>Listing email:'$listingemail' <br>Listing yearofestablishment:'$listingyearofestablishment' <br>";
//        echo $msg;
        //to user
        $this->load->library('email');
        $this->email->from('avinash@wohlig.com', 'For Any Information To User');
        $this->email->to($touser);
        $this->email->subject('Listing Details');
        $this->email->message($usermsg);

        $this->email->send();
        
        //to listing
        $firstname=$user->firstname;
        $lastname=$user->lastname;
        $email=$user->email;
        $contact=$user->contact;
        $listingmsg="<h3>All Details Of user</h3><br>user Name:'$firstname' <br>user Last Name:'$lastname' <br>user Email:'$email' <br>user contact:'$contact'";
        
        $this->load->library('email');
        $this->email->from('avinash@wohlig.com', 'For Any Information Listing');
        $this->email->to($tolisting);
        $this->email->subject('User Details');
        $this->email->message($listingmsg);

        $this->email->send();

        echo $this->email->print_debugger();
    }
    public function submitnumber()
    {
        $number=$this->input->get_post("number");
        $data1=$this->enquiry_model->getdetailsorcreate($number);
        $data["message"]=$data1;
//        print_r($data);
        $this->load->view("json",$data);
    }
    public function submitcategoryenquiry()
    {
        $category=$this->input->get_post("categoryvalue");
        $enquiryid=$this->input->get_post("userid");
//        echo $category;
//        echo $userid;
        $data1=$this->enquiry_model->addcategorytoenquiry($enquiryid,$category);
        $data["message"]=$data1;
//        print_r($data);
        $this->load->view("json",$data);
    }
    public function submitlistingenquiry()
    {
        $listing=$this->input->get_post("listingvalue");
        $category=$this->input->get_post("categoryvalue");
        $enquiryid=$this->input->get_post("userid");
//        echo $listing;
//        echo $userid;
        $data1=$this->enquiry_model->addlistingtoenquiry($enquiryid,$listing,$category);
        $data["message"]=$data1;
//        print_r($data);
        $this->load->view("json",$data);
    }
    
    public function submituserdetails()
    {
        $enquiryid=$this->input->get_post("userid");
        $name=$this->input->get_post("username");
        $phone=$this->input->get_post("userphone");
        $email=$this->input->get_post("useremail");
//        echo $listing;
//        echo $userid;
        $data1=$this->enquiry_model->adduserdetails($enquiryid,$name,$phone,$email);
        $data["message"]=$data1;
//        print_r($data);
        $this->load->view("json",$data);
    }
    
	function viewtree()
	{
		$access = array("1");
		$this->checkaccess($access);
//		$data['table']=$this->add_model->viewadd();
		$data['page']='viewtree';
		$data['title']='View Tree';
		$this->load->view('template',$data);
	}
	function viewcategory()
	{
		$access = array("1");
		$this->checkaccess($access);
//		$data['table']=$this->add_model->viewadd();
		$data['table']=$this->category_model->viewparentcategory();
		$data['page']='viewcategorytree';
		$data['title']='View Tree';
		$this->load->view('template',$data);
	}
    
    public function getsubcategorybyparent()
    {
        $categoryid=$this->input->get_post("categoryid");
        $data1=$this->category_model->getsubcategorybyparent($categoryid);
        $data["message"]=$data1;
//        print_r($data);
        $this->load->view("json",$data);
    }
    
    
    public function getlistingbycategory($id)
    {
   $data1=$this->listing_model->getlistingbycategorydropdown($id);
        
        
            $data["message"]=$data1;
//        print_r($data);
            $this->load->view("json",$data);
  
            
        
    }
    
    function uploadlistingcsv()
	{
		$access = array("1");
		$this->checkaccess($access);
        $cat=$this->category_model->getcategorytreeforlisting(0);
        $cat1=$this->getarray($cat,"");
        
        $data['category']=$cat1;
//        $data[ 'category' ] =$this->category_model->getcategoryforlistingdropdown();
		$data[ 'page' ] = 'uploadlistingcsv';
		$data[ 'title' ] = 'Upload Listing';
		$this->load->view( 'template', $data );
	} 
    
    function uploadlistingcsvsubmit()
	{
        $access = array("1");
		$this->checkaccess($access);
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '1000';
        $this->load->library('upload', $config);
        $filename="file";
        $file="";
        if (  $this->upload->do_upload($filename))
        {
            $uploaddata = $this->upload->data();
            $file=$uploaddata['file_name'];
            $filepath=$uploaddata['file_path'];
        }
        $fullfilepath=$filepath."".$file;
        $file = $this->csvreader->parse_file($fullfilepath);
        $category=$this->input->get_post('category');
        $id1=$this->listing_model->createbycsv($file,$category);
//        echo $id1;
        
        if($id1==0)
        $data['alerterror']="New listings could not be Uploaded.";
		else
		$data['alertsuccess']="listings Uploaded Successfully.";
        
        $data['redirect']="site/viewlisting";
        $this->load->view("redirect",$data);
    }
    
    
    function viewnormalusers()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewnormalusers';
        
        
        $data['base_url'] = site_url("site/viewnormaluserjson");
        
        
		$data['title']='View user';
		$this->load->view('template',$data);
	} 
    
    function viewnormaluserjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
//        SELECT DISTINCT `user`.`id` as `id`,`user`.`firstname` as `firstname`,`user`.`lastname` as `lastname`,`accesslevel`.`name` as `accesslevel`	,`user`.`email` as `email`,`user`.`contact` as `contact`,`user`.`status` as `status`,`user`.`accesslevel` as `access`
//		FROM `user`
//	   INNER JOIN `accesslevel` ON `user`.`accesslevel`=`accesslevel`.`id` 
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`user`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`user`.`firstname`";
        $elements[1]->sort="1";
        $elements[1]->header="First Name";
        $elements[1]->alias="firstname";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`lastname`";
        $elements[2]->sort="1";
        $elements[2]->header="Last Name";
        $elements[2]->alias="lastname";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`accesslevel`.`name`";
        $elements[3]->sort="1";
        $elements[3]->header="Accesslevel";
        $elements[3]->alias="accesslevel";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`user`.`email`";
        $elements[4]->sort="1";
        $elements[4]->header="Email";
        $elements[4]->alias="email";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`user`.`contact`";
        $elements[5]->sort="1";
        $elements[5]->header="Contact";
        $elements[5]->alias="contact";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `user` INNER JOIN `accesslevel` ON `user`.`accesslevel`=`accesslevel`.`id`","WHERE `user`.`accesslevel`=3");
        
		$this->load->view("json",$data);
	} 
    public function createnormaluser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data[ 'page' ] = 'createnormaluser';
		$data[ 'title' ] = 'Create Frontend User';
		$this->load->view( 'template', $data );	
	}
	function createnormalusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required|matches[password]');
//		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('contact','contactno','trim');
		$this->form_validation->set_rules('phoneno','phoneno','trim');
//		$this->form_validation->set_rules('website','Website','trim|max_length[50]');
//		$this->form_validation->set_rules('description','Description','trim|');
		$this->form_validation->set_rules('address','Address','trim|');
		$this->form_validation->set_rules('city','City','trim|max_length[30]');
		$this->form_validation->set_rules('pincode','Pincode','trim|max_length[20]');
		$this->form_validation->set_rules('state','state','trim|max_length[20]');
		$this->form_validation->set_rules('country','country','trim|max_length[20]');
		$this->form_validation->set_rules('facebookuserid','facebookuserid','trim|max_length[20]');
		$this->form_validation->set_rules('google','google','trim|max_length[20]');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('status','Status','trim');
		$this->form_validation->set_rules('dob','DOB','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data[ 'page' ] = 'createnormaluser';
            $data[ 'title' ] = 'Create Frontend User';
            $this->load->view( 'template', $data );		
		}
		else
		{
            $website=$this->input->post('website');
            $dob=$this->input->post('dob');
            $description=$this->input->post('description');
            $address=$this->input->post('address');
            $city=$this->input->post('city');
            $pincode=$this->input->post('pincode');
			$password=$this->input->post('password');
			if($dob != "")
			{
				$dob = date("Y-m-d",strtotime($dob));
			}
//			$accesslevel=$this->input->post('accesslevel');
			$accesslevel=3;
			$email=$this->input->post('email');
			$contact=$this->input->post('contact');
			$phoneno=$this->input->post('phoneno');
			$google=$this->input->post('google');
			$state=$this->input->post('state');
			$country=$this->input->post('country');
			$status=$this->input->post('status');
			$facebookuserid=$this->input->post('facebookuserid');
			$firstname=$this->input->post('firstname');
			$lastname=$this->input->post('lastname');
			if($this->user_model->create($firstname,$lastname,$dob,$password,$accesslevel,$email,$contact,$status,$facebookuserid,$website,$description,$address,$city,$pincode,$phoneno,$google,$state,$country)==0)
			$data['alerterror']="Normal user could not be created.";
			else
			$data['alertsuccess']="User created Successfully.";
			
//			$data['table']=$this->user_model->viewusers();
			$data['redirect']="site/viewnormalusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
   
	function editnormaluser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
		$data['page']='editnormaluser';
		$data['page2']='block/userblock';
		$data['title']='Edit User';
		$this->load->view('template',$data);
	}
	function editnormalusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('password','Password','trim|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|matches[password]');
//		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('contact','contactno','trim');
		$this->form_validation->set_rules('phoneno','phoneno','trim');
		$this->form_validation->set_rules('google','google','trim');
		$this->form_validation->set_rules('state','state','trim');
		$this->form_validation->set_rules('country','country','trim');
		$this->form_validation->set_rules('website','Website','trim|max_length[50]');
//		$this->form_validation->set_rules('description','Description','trim|');
		$this->form_validation->set_rules('address','Address','trim|');
		$this->form_validation->set_rules('city','City','trim|max_length[30]');
		$this->form_validation->set_rules('pincode','Pincode','trim|max_length[20]');
        
		$this->form_validation->set_rules('fname','First Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('lname','Last Name','trim|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('status','Status','trim');
		$this->form_validation->set_rules('dob','DOB','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data['accesslevel']=$this->user_model->getaccesslevels();
            $data['before']=$this->user_model->beforeedit($this->input->get_post('id'));
            $data['page']='editnormaluser';
            $data['page2']='block/userblock';
            $data['title']='Edit User';
            $this->load->view('template',$data);
		}
		else
		{
            $website=$this->input->post('website');
            $description=$this->input->post('description');
            $address=$this->input->post('address');
            $city=$this->input->post('city');
            $pincode=$this->input->post('pincode');
			$id=$this->input->post('id');
			$password=$this->input->post('password');
			$dob=$this->input->post('dob');
			if($dob != "")
			{
				$dob = date("Y-m-d",strtotime($dob));
			}
			$accesslevel=3;
			$contact=$this->input->post('contact');
			$phoneno=$this->input->post('phoneno');
			$google=$this->input->post('google');
			$state=$this->input->post('state');
			$country=$this->input->post('country');
			$status=$this->input->post('status');
			$facebookuserid=$this->input->post('facebookuserid');
			$fname=$this->input->post('fname');
			$lname=$this->input->post('lname');
			if($this->user_model->edit($id,$fname,$lname,$dob,$password,$accesslevel,$contact,$status,$facebookuserid,$website,$description,$address,$city,$pincode,$phoneno,$google,$state,$country)==0)
			$data['alerterror']="Frontend User Editing was unsuccesful";
			else
			$data['alertsuccess']="Frontend User edited Successfully.";
			
			$data['redirect']="site/viewnormalusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	
	function deletenormaluser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->deleteuser($this->input->get('id'));
		$data['alertsuccess']="Frontend User Deleted Successfully";
		$data['redirect']="site/viewnormalusers";
		$this->load->view("redirect",$data);
	}
     
	function viewnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['monthbefore']=$this->category_model->getmonthbeforenotifications();
		$data['fivedaysbefore']=$this->category_model->getfivedaysbeforenotifications();
		$data['page']='viewnotification';
		$data['title']='View Notifications';
		$this->load->view('template',$data);
	}
    
	function editnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->category_model->beforeeditcategory($this->input->get('id'));
		$data['category']=$this->category_model->getcategorydropdown();
		$data['listing']=$this->listing_model->getlistingdropdown();
		$data[ 'status' ] =$this->category_model->getstatusdropdown();
		$data['typeofimage']=$this->category_model->gettypeofimagedropdown();
		$data['page']='editnotification';
		$data['title']='Edit Notification';
		$this->load->view('template',$data);
	}
	function editnotificationsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('startdateofbanner','startdateofbanner','trim|');
		$this->form_validation->set_rules('enddateofbanner','enddateofbanner','trim|');
		$this->form_validation->set_rules('listing','listing','trim|');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->category_model->getstatusdropdown();
			$data['category']=$this->category_model->getcategorydropdown();
            $data['listing']=$this->listing_model->getlistingdropdown();
            $data['typeofimage']=$this->category_model->gettypeofimagedropdown();
			$data['before']=$this->category_model->beforeeditcategory($this->input->post('id'));
			$data['page']='editcategory';
			$data['title']='Edit category';
			$this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			
			$startdateofbanner=$this->input->post('startdateofbanner');
			$enddateofbanner=$this->input->post('enddateofbanner');
			$listing=$this->input->post('listing');
			
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="banner";
			$banner="";
			if (  $this->upload->do_upload($filename))
			{
                
				$uploaddata = $this->upload->data();
				$banner=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $banner=$this->image_lib->dest_image;
                    //return false;
                }
                
                
			}
            if($banner=="")
            {
                
                $banner=$this->category_model->getcategorybannerbyid($id);
                $banner=$banner->banner;
                
            }
			if($this->category_model->editnotification($id,$banner,$startdateofbanner,$enddateofbanner,$listing)==0)
			$data['alerterror']="Notification Dates Editing was unsuccesful";
			else
			$data['alertsuccess']="Notification Dates edited Successfully.";
			$data['redirect']="site/viewnotification";
			$this->load->view("redirect",$data);
		}
	}
    
//    function viewnotification()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//		$data['page']='viewnotification';
//        
//        
//        $data['base_url'] = site_url("site/viewnotificationjson");
//        
//        
//		$data['title']='View Notifications';
//		$this->load->view('template',$data);
//	} 
//    
//    function viewnotificationjson()
//	{
//		$access = array("1");
//		$this->checkaccess($access);
//         
////        SELECT `id`, `name`, `parent`, `status`, `typeofimage`, `logo`, `image`, `banner`, `startdateofbanner`, `enddateofbanner` ,NOW() AS `today`,DATE(DATE_SUB(NOW(), INTERVAL 1 day)) AS `monthbefore`
////FROM `category` 
////HAVING `enddateofbanner`=`monthbefore`
//        
//        
//        $elements=array();
//        $elements[0]=new stdClass();
//        $elements[0]->field="`category`.`id`";
//        $elements[0]->sort="1";
//        $elements[0]->header="ID";
//        $elements[0]->alias="id";
//        
//        $elements[1]=new stdClass();
//        $elements[1]->field="`category`.`banner`";
//        $elements[1]->sort="1";
//        $elements[1]->header="Banner";
//        $elements[1]->alias="banner";
//        
//        $elements[2]=new stdClass();
//        $elements[2]->field="`category`.`startdateofbanner`";
//        $elements[2]->sort="1";
//        $elements[2]->header="Start Date";
//        $elements[2]->alias="startdateofbanner";
//        
//        $elements[3]=new stdClass();
//        $elements[3]->field="`category`.`enddateofbanner`";
//        $elements[3]->sort="1";
//        $elements[3]->header="End Date";
//        $elements[3]->alias="enddateofbanner";
//        
//        $elements[4]=new stdClass();
//        $elements[4]->field="DATE(DATE_SUB(NOW(), INTERVAL 1 day))";
//        $elements[4]->sort="1";
//        $elements[4]->header="monthbefore";
//        $elements[4]->alias="monthbefore";
//        
//        $search=$this->input->get_post("search");
//        $pageno=$this->input->get_post("pageno");
//        $orderby=$this->input->get_post("orderby");
//        $orderorder=$this->input->get_post("orderorder");
//        $maxrow=$this->input->get_post("maxrow");
//        if($maxrow=="")
//        {
//            $maxrow=20;
//        }
//        
//        if($orderby=="")
//        {
//            $orderby="id";
//            $orderorder="ASC";
//        }
//       
//        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `category`","","","HAVING `enddateofbanner`=`monthbefore`");
//        
//		$this->load->view("json",$data);
//	} 
    
	function deletealllistings()
	{
		$access = array("1");
		$this->checkaccess($access);
        //print_r($this->input->post('ids'));
		$data['table']=$this->listing_model->deletealllistings($this->input->post('ids'));
		$data['alertsuccess']="Listing Deleted Successfully";
		$data['page']='viewlisting';
		$data['title']='View Listing';
		//$this->load->view('template',$data);
	}
	function tejasdelete()
	{
		$access = array("1");
		$this->checkaccess($access);
        //print_r($this->input->post('ids'));
        $userid=$this->session->userdata('id');
        $accesslevel=$this->session->userdata('accesslevel');
        if($userid==74 && $accesslevel==1)
        {
            $data['table']=$this->listing_model->tejasdelete();
            $data['alertsuccess']="Listing Deleted Successfully";
            $data['redirect']="site/viewlisting";
			$this->load->view("redirect",$data);
        }
        else
        {
            $data['alertsuccess']="You Do Not Have Access To Delete Listings";
            $data['redirect']="site/viewlisting";
			$this->load->view("redirect",$data);
        }
		//$this->load->view('template',$data);
	}
    
    
    public function getareadropdown($id) {
        $data1 = $this->listing_model->getareadropdown($id);
        $data["message"] = $data1;
        $this->load->view("json", $data);
    }
    
    public function getareabycity()
    {
        $city=$this->input->get_post('city');
        $data['message']=$this->city_model->getareabycity($city);
        $this->load->view('json',$data);
    }
    
    
    function uploadareacsv()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'page' ] = 'uploadareacsv';
		$data[ 'title' ] = 'Upload Area';
		$this->load->view( 'template', $data );
	} 
    
    function uploadareacsvsubmit()
	{
        $access = array("1");
		$this->checkaccess($access);
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $filename="file";
        $file="";
        if (  $this->upload->do_upload($filename))
        {
            $uploaddata = $this->upload->data();
            $file=$uploaddata['file_name'];
            $filepath=$uploaddata['file_path'];
        }
        $fullfilepath=$filepath."".$file;
        $file = $this->csvreader->parse_file($fullfilepath);
        $id1=$this->city_model->createbycsv($file);
//        echo $id1;
        
        if($id1==0)
        $data['alerterror']="New areas could not be Uploaded.";
		else
		$data['alertsuccess']="areas Uploaded Successfully.";
        
        $data['redirect']="site/viewcity";
        $this->load->view("redirect",$data);
    }
    
     
	function viewexpirednotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->category_model->getexpirednotification();
		$data['page']='viewexpirednotification';
		$data['title']='View Expired Notifications';
		$this->load->view('template',$data);
	}
     
	function viewupcommingnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->category_model->getupcommingnotification();
		$data['page']='viewupcommingnotification';
		$data['title']='View UpComming Notifications';
		$this->load->view('template',$data);
	}
    
    
    //listingimages
    
	function viewuserlistingrating()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->listing_model->viewuserlistingrating($this->input->get('id'));
		$data['before']=$this->listing_model->beforeedit($this->input->get('id'));
		$data['page']='viewuserlistingrating';
		$data['page2']='block/listingblock';
		$data['title']='View userlistingrating';
		$this->load->view('templatewith2',$data);
	}
    
	public function createuserlistingrating()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['listing']=$this->input->get('id');
		$data[ 'user' ] =$this->listing_model->getuserdropdown();
        $data['before']=$this->listing_model->beforeedit($this->input->get('id'));
//		$data['page']='viewuserlistingrating';
		$data[ 'page' ] = 'createuserlistingrating';
		$data['page2']='block/listingblock';
		$data[ 'title' ] = 'Create listingimage';
		$this->load->view( 'templatewith2', $data );	
	}
    
	function createuserlistingratingsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('user','user','trim|required');
		$this->form_validation->set_rules('listing','listing','trim');
		$this->form_validation->set_rules('rating','rating','trim');
		
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$access = array("1");
            $this->checkaccess($access);
            $data['listing']=$this->input->get_post('listing');
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data['before']=$this->listing_model->beforeedit($this->input->get_post('listing'));
    //		$data['page']='viewuserlistingrating';
            $data[ 'page' ] = 'createuserlistingrating';
            $data['page2']='block/listingblock';
            $data[ 'title' ] = 'Create listingimage';
            $this->load->view( 'templatewith2', $data );	
		}
		else
		{
            $user=$this->input->post('user');
            $listing=$this->input->post('listing');
            $rating=$this->input->post('rating');
            
			if($this->listing_model->createuserlistingrating($user,$listing,$rating)==0)
			$data['alerterror']="Rating Already Created.";
			else
			$data['alertsuccess']="Rating created Successfully.";
			
			$data['table']=$this->listing_model->viewuserlistingrating($listing);
            $data['redirect']="site/viewuserlistingrating?id=".$listing;
			$this->load->view("redirect2",$data);
//            $data['page']='viewuserlistingrating';
//            $data['title']='View userlistingrating';
//            $this->load->view('template',$data);
		}
	}
    
	public function edituserlistingrating()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['listing']=$this->input->get('listingid');
        $data['before']=$this->listing_model->beforeedit($this->input->get('listingid'));
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
        $data['beforeuserlistingrating']=$this->listing_model->beforeedituserlistingrating($this->input->get('id'));
//		$data['page']='viewuserlistingrating';
		$data[ 'page' ] = 'edituserlistingrating';
		$data['page2']='block/listingblock';
		$data[ 'title' ] = 'Create listingimage';
		$this->load->view( 'templatewith2', $data );	
	}
    
	function edituserlistingratingsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('user','user','trim|required');
		$this->form_validation->set_rules('listing','listing','trim');
		$this->form_validation->set_rules('rating','rating','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['listing']=$this->input->get('listingid');
            $data['before']=$this->listing_model->beforeedit($this->input->get_post('listing'));
            $data[ 'user' ] =$this->listing_model->getuserdropdown();
            $data['beforeuserlistingrating']=$this->listing_model->beforeedituserlistingrating($this->input->get_post('id'));
    //		$data['page']='viewuserlistingrating';
            $data[ 'page' ] = 'edituserlistingrating';
            $data['page2']='block/listingblock';
            $data[ 'title' ] = 'Create listingimage';
            $this->load->view( 'templatewith2', $data );	
		}
		else
		{
			$id=$this->input->post('id');
//            echo $id;
            
            $user=$this->input->post('user');
            $listing=$this->input->post('listing');
            $rating=$this->input->post('rating');
			if($this->listing_model->edituserlistingrating($id,$user,$listing,$rating)==0)
			$data['alerterror']="Rating Editing was unsuccesful";
			else
			$data['alertsuccess']="Rating edited Successfully.";
			
			$data['table']=$this->listing_model->viewuserlistingrating($listing);
            $data['redirect']="site/viewuserlistingrating?id=".$listing;
			$this->load->view("redirect2",$data);
			
		}
	}
    
	function deleteuserlistingrating()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->listing_model->deleteuserlistingrating($this->input->get('id'));
        $listing=$this->input->get('listingid');
        $data['alerterror']="User Rating Deleted Successfully.";
		$data['table']=$this->listing_model->viewuserlistingrating($listing);
        $data['redirect']="site/viewuserlistingrating?id=".$listing;
        $this->load->view("redirect2",$data);
	}
    
    //listingnotification
     
	function viewlistingnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['monthbefore']=$this->listing_model->getmonthbeforelistingnotifications();
		$data['fivedaysbefore']=$this->listing_model->getfivedaysbeforelistingnotifications();
		$data['page']='viewlistingnotification';
		$data['title']='View listingNotifications';
		$this->load->view('template',$data);
	}
    
	function editlistingnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['before']=$this->listing_model->beforeedit($this->input->get('id'));
		$data['listing']=$this->listing_model->getlistingdropdown();
		$data[ 'status' ] =$this->listing_model->getstatusdropdown();
//		$data['typeofimage']=$this->category_model->gettypeofimagedropdown();
		$data['page']='editlistingnotification';
		$data['title']='Edit Listing Notification';
		$this->load->view('template',$data);
	}
	function editlistingnotificationsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('pointerstartdate','pointerstartdate','trim|');
		$this->form_validation->set_rules('pointerenddate','pointerenddate','trim|');
		$this->form_validation->set_rules('pointer','pointer','trim|');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->category_model->getstatusdropdown();
			$data['category']=$this->category_model->getcategorydropdown();
            $data['listing']=$this->listing_model->getlistingdropdown();
            $data['typeofimage']=$this->category_model->gettypeofimagedropdown();
			$data['before']=$this->category_model->beforeeditcategory($this->input->post('id'));
			$data['page']='editcategory';
			$data['title']='Edit category';
			$this->load->view('template',$data);
		}
		else
		{
			$id=$this->input->post('id');
			
			$pointerstartdate=$this->input->post('pointerstartdate');
			$pointerenddate=$this->input->post('pointerenddate');
			$pointer=$this->input->post('pointer');
			
            
			if($this->listing_model->editlistingnotification($id,$pointerstartdate,$pointerenddate,$pointer)==0)
			$data['alerterror']="Listing Notification Dates Editing was unsuccesful";
			else
			$data['alertsuccess']="Listing Notification Dates edited Successfully.";
			$data['redirect']="site/viewlistingnotification";
			$this->load->view("redirect",$data);
		}
	}
    
     
	function viewexpiredlistingnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->listing_model->getexpiredlistingnotification();
		$data['page']='viewexpiredlistingnotification';
		$data['title']='View Expired Listing Notifications';
		$this->load->view('template',$data);
	}
     
	function viewupcomminglistingnotification()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->listing_model->getupcomminglistingnotification();
		$data['page']='viewupcomminglistingnotification';
		$data['title']='View UpComming Listing Notifications';
		$this->load->view('template',$data);
	}
    
    
	function printpaymentreceipt()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['table']=$this->billing_model->viewbillingforreceipt($this->input->get('id'));
		$data['page']='receipt';
		$this->load->view('receipt',$data);
	}
    
    
    public function getlistingbycategorydashboarddropdown($id) {
        $data1 = $this->listing_model->getlistingbycategorydashboarddropdown($id);
        $data["message"] = $data1;
        $this->load->view("json", $data);
    }
    
    
	public function movelisting()
	{
		$access = array("1");
		$this->checkaccess($access);
        $cat=$this->category_model->getcategorytreeforlisting(0);
        $cat1=$this->getarray($cat,"");
        
        $data['category']=$cat1;
//        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'page' ] = 'movelisting';
		$data[ 'title' ] = 'Move Listing';
		$this->load->view( 'template', $data );	
	}
    
	function movelistingsubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('fromcategory','fromcategory','trim');
		$this->form_validation->set_rules('tocategory','tocategory','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
            $cat=$this->category_model->getcategorytreeforlisting(0);
            $cat1=$this->getarray($cat,"");

            $data['category']=$cat1;
    //        $data['category']=$this->category_model->getcategorydropdown();
            $data[ 'page' ] = 'movelisting';
            $data[ 'title' ] = 'Move Listing';
            $this->load->view( 'template', $data );		
		}
		else
		{
            $fromcategory=$this->input->post('fromcategory');
            $tocategory=$this->input->post('tocategory');
            
			if($this->listing_model->movelisting($fromcategory,$tocategory)==0)
			$data['alerterror']="Listings Cannot Move";
			else
			$data['alertsuccess']="Listings Move Successfully";
			
			$data['redirect']="site/movelisting";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
    function viewfreelisting()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewlisting';
        
        
        $data['base_url'] = site_url("site/viewfreelistingjson");
        
        
		$data['title']='View listing';
		$this->load->view('template',$data);
	} 
    
    function viewfreelistingjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
//        SELECT DISTINCT `user`.`id` as `id`,`user`.`firstname` as `firstname`,`user`.`lastname` as `lastname`,`accesslevel`.`name` as `accesslevel`	,`user`.`email` as `email`,`user`.`contact` as `contact`,`user`.`status` as `status`,`user`.`accesslevel` as `access`
//		FROM `user`
//	   INNER JOIN `accesslevel` ON `user`.`accesslevel`=`accesslevel`.`id` 
        
//        SELECT `id`, `name`, `user`, `lat`, `long`, `address`, `city`, `pincode`, `state`, `country`, `description`, `logo`, `contactno`, `email`, `website`, `facebook`, `twitter`, `googleplus`, `yearofestablishment`, `timeofoperation_start`, `timeofoperation_end`, `type`, `credits`, `isverified`, `video`, `deletestatus` FROM `listing` 
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`listing`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listing`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`listing`.`address`";
        $elements[2]->sort="1";
        $elements[2]->header="Address";
        $elements[2]->alias="address";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`listing`.`email`";
        $elements[3]->sort="1";
        $elements[3]->header="Email";
        $elements[3]->alias="email";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`listing`.`contactno`";
        $elements[4]->sort="1";
        $elements[4]->header="Contact No";
        $elements[4]->alias="contactno";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`listing`.`pointer`";
        $elements[5]->sort="1";
        $elements[5]->header="Pointer";
        $elements[5]->alias="pointer";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`listing`.`area`";
        $elements[6]->sort="1";
        $elements[6]->header="Area";
        $elements[6]->alias="area";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `listing`","WHERE `listing`.`deletestatus`=1 AND `listing`.`type`=0");
        
		$this->load->view("json",$data);
	} 
    
    
    //export csv
    
    	public function exportlistingcsv()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->listing_model->exportlistingcsv();
        $data['redirect']="site/viewlisting";
        $this->load->view("redirect",$data);
	}
    
    
	public function deletelistingbycategory()
	{
		$access = array("1");
		$this->checkaccess($access);
        $cat=$this->category_model->getcategorytreeforlisting(0);
        $cat1=$this->getarray($cat,"");
        
        $data['category']=$cat1;
//        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'page' ] = 'deletelistingbycategory';
		$data[ 'title' ] = 'Delete Listing';
		$this->load->view( 'template', $data );	
	}
    
	function deletelistingbycategorysubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('fromcategory','fromcategory','trim');
//		$this->form_validation->set_rules('tocategory','tocategory','trim');
        
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
            $cat=$this->category_model->getcategorytreeforlisting(0);
            $cat1=$this->getarray($cat,"");

            $data['category']=$cat1;
    //        $data['category']=$this->category_model->getcategorydropdown();
            $data[ 'page' ] = 'deletelistingbycategory';
            $data[ 'title' ] = 'Delete Listing';
            $this->load->view( 'template', $data );		
		}
		else
		{
            $fromcategory=$this->input->post('fromcategory');
//            $tocategory=$this->input->post('tocategory');
            
			if($this->listing_model->deletelistingbycategory($fromcategory)==0)
			$data['alerterror']="Listings Cannot Deleted";
			else
			$data['alertsuccess']="Listings Deleted Successfully";
			
			$data['redirect']="site/deletelistingbycategory";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
		}
	}
    
}
?>