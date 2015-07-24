<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Json extends CI_Controller 
{
	public function __construct( )
	{
		parent::__construct();
		$this->db->query("SET time_zone = '+05:30'");

	}
	function savequantity()
	{
		$product=$this->input->get_post('product');
		$quantity=$this->input->get_post('quantity');
		$data["message"]=$this->product_model->savequantity($product,$quantity);
		$this->load->view("json",$data);
	}
    function type ()
    {
        $data["message"]=$this->frontend_model->type();
		$this->load->view("json",$data);
    }
    public function getallparentcategories()
    {
        $data['message']=$this->category_model->getallparentcategories();
		$this->load->view('json',$data);
    }
    
    
    function saveprofile()
	{
        $data = json_decode(file_get_contents('php://input'), true);
//        print_r($data);
            $firstname=$data['firstname'];
			$lastname=$data['lastname'];
			$email=$data['email'];
			$contact=$data['contact'];
			$phoneno=$data['phoneno'];
            $dob=$data['dob'];
            $website=$data['website'];
            $address=$data['address'];
            $city=$data['city'];
            $pincode=$data['pincode'];
			$state=$data['state'];
            $country=$data['country'];
			$google=$data['google'];
			$facebookuserid=$data['facebookuserid'];
            $id=$data['id'];
            
			if($this->frontend_model->saveprofile($id,$firstname,$lastname,$email,$contact,$phoneno,$dob,$website,$address,$city,$pincode,$state,$country,$google,$facebookuserid)==0)
			$data['message']=0;
			else
			$data['message']=1;
        
        $this->load->view('json',$data);
		
	}
    
    
    function createlisting()
	{
        $data = json_decode(file_get_contents('php://input'), true);
//        print_r($data);
            $name=$data['name'];
			$user=$data['user'];
			$lat=$data['latitude'];
			$long=$data['longitude'];
            $address=$data['address'];
            $area=$data['area'];
            $city=$data['city'];
            $pincode=$data['pincode'];
            $state=$data['state'];
			$country=$data['country'];
            $description=$data['description'];
			$contact=$data['contact'];
			$email=$data['email'];
            $website=$data['website'];
			$facebookuserid=$data['facebook'];
			$googleplus=$data['googleplus'];
			$twitter=$data['twitter'];
			$yearofestablishment=$data['yearofestablishment'];
			$timeofoperation_start=$data['timeofoperationstart'];
			$timeofoperation_end=$data['timeofoperationend'];
			$type=$data['type'];
			$credits=$data['credits'];
			$video=$data['video'];
            
            $category=$data['category'];
            $modeofpayment=$data['modeofpayment'];
            $daysofoperation=$data['daysofoperation'];
            $logo=$data['logo'];
            
        $message="<h3>All Details Of Listing</h3><br>Listing Name:'$name' <br>Listing address:'$address' <br>Listing state:'$state' <br>Listing contactno:'$contact' <br>Listing email:'$email' <br>Listing yearofestablishment:'$yearofestablishment' <br>";
//        echo $msg;
        //to user
        $this->load->library('email');
        $this->email->from('avinash@wohlig.com', 'For Any Information');
        $this->email->to($email);
        $this->email->subject('Thank You For Creating A Listing');
        $this->email->message($message);

        $this->email->send();
        
        
			if($this->frontend_model->createlisting($name,$user,$lat,$long,$address,$area,$city,$pincode,$state,$country,$description,$contact,$email,$website,$facebookuserid,$googleplus,$twitter,$yearofestablishment,$timeofoperation_start,$timeofoperation_end,$type,$credits,$video,$logo,$category,$modeofpayment,$daysofoperation)==0)
			$data['message']="0";
			else
			$data['message']="1";
        
        $this->load->view('json',$data);
		
	}
    public function enquiryuser()
    {
        $name=$this->input->get_post("name");
        $listingid=$this->input->get_post("listing");
        $email=$this->input->get_post("email");
        $phone=$this->input->get_post("phone");
        $type=$this->input->get_post("type");
        $comment=$this->input->get_post("comment");
        
        
            $listing=$this->listing_model->getallinfooflisting($listingid);
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
//            echo $usermsg."<br>";
            //to listing
//            $firstname=$user->firstname;
//            $lastname=$user->lastname;
//            $email=$user->email;
//            $contact=$user->contact;
            $listingmsg="<h3>All Details Of user</h3><br>user Name:'$name' <br>user Email:'$email' <br>user contact:'$phone'<br>user Comment:'$comment'";
//echo $listingmsg;
            $this->load->library('email');
            $this->email->from('avinash@wohlig.com', 'For Any Information Listing');
            $this->email->to($listingemail);
            $this->email->subject('User Details');
            $this->email->message($listingmsg);

            $this->email->send();

//            echo $this->email->print_debugger();
        
        $data['message']=$this->enquiry_model->enquiryuser($name,$listingid,$email,$phone,$type,$comment);
        $this->load->view('json',$data);
    }
    public function login()
    {
        $email=$this->input->get("email");
        $password=$this->input->get("password");
        $data['message']=$this->user_model->login($email,$password);
        $this->load->view('json',$data);
    }
    public function loginfromback()
    {
    //$email=$this->input->get('email');
    //$password=$this->input->get('password');
        $adminuser=$this->db->query("SELECT * FROM `user` WHERE `accesslevel`=1")->row();
        $email=$adminuser->email;
        $id=$adminuser->id;
        $name=$adminuser->name;
        $accesslevel=$adminuser->accesslevel;
        $newdata        = array(
        'id' => $id,
        'email' => $email,
        'name' => $name ,
        'accesslevel' => $accesslevel,
        'logged_in' => 'true',
        );
        $this->session->set_userdata( $newdata );
        redirect( base_url() . 'index.php/site', 'refresh' );
    }
    public function logout()
    {
        $this->session->sess_destroy();
        
		$this->load->view('json',true);
    }
    public function authenticate()
    {
        $data['message']=$this->user_model->authenticate();
		$this->load->view('json',$data);
    }
    public function signup()
    {
        $firstname=$this->input->get_post("firstname");
        $lastname=$this->input->get_post("lastname");
        $phoneno=$this->input->get_post("phoneno");
        $email=$this->input->get_post("email");
        $password=$this->input->get_post("password");
        $data['message']=$this->user_model->frontendsignup($firstname, $lastname, $phoneno, $email, $password);
        $this->load->view('json',$data);
        
    }
    
    public function getalllocationcityvise()
    {
        $id=$this->input->get_post('id');
        $data['message']=$this->city_model->getalllocationcityvise($id);
		$this->load->view('json',$data);
    }
    
    public function viewonecitylocations()
    {
        $id=$this->input->get_post('id');
        $data['message']=$this->city_model->viewonecitylocations($id);
		$this->load->view('json',$data);
    }

    public function getsubcategory()
    {
        $id=$this->input->get_post('id');
        $data['message']=$this->category_model->getsubcategory($id);
		$this->load->view('json',$data);
    }
    
    public function getcategory()
    {
        $data['message']=$this->category_model->getcategory();
		$this->load->view('json',$data);
    }
    
    public function getcategoryfront()
    {
        $data['message']=$this->category_model->getcategoryfront();
		$this->load->view('json',$data);
    }
    
    public function getfilter()
    {
        $id=$this->input->get_post('id');
        $data['message']=$this->category_model->getfilter($id);
		$this->load->view('json',$data);
    }
    public function getlistingbycategorywithoutpagination()
    {
        $categoryid=$this->input->get_post('id');
        $data['message']=$this->listing_model->getlistingbycategory($categoryid);
		$this->load->view('json',$data);
    }
    public function getonelistingbyid()
    {
        $listingid=$this->input->get_post('id');
        $data['message']=$this->listing_model->getonelistingbyid($listingid);
		$this->load->view('json',$data);
    }
    public function getlistingbycity()
    {
        $cityid=$this->input->get_post('id');
        $data['message']=$this->listing_model->getlistingbycity($cityid);
		$this->load->view('json',$data);
    }
    //search
    
    public function searchcategory()
    {
        $category=$this->input->get_post('categoryname');
        $city=$this->input->get_post('cityname');
        $area=$this->input->get_post('area');
        $lat=$this->input->get_post('lat');
        $long=$this->input->get_post('long');
        $data['message']=$this->category_model->searchcategory($category,$city,$area,$lat,$long);
		$this->load->view('json',$data);
    }

    public function searchcity()
    {
        $city=$this->input->get_post('city');
        $data['message']=$this->city_model->searchcity($city);
		$this->load->view('json',$data);
    }

    public function searcharea()
    {
        $city=$this->input->get_post('cityid');
        $area=$this->input->get_post('area');
//        $lat=$this->input->get_post('lat');
//        $long=$this->input->get_post('long');
        $data['message']=$this->category_model->searcharea($city,$area);
		$this->load->view('json',$data);
    }

    public function getcategoryinfo()
    {
        $id=$this->input->get_post('id');
        $data['message']=$this->category_model->getcategoryinfo($id);
        $this->load->view('json',$data);
    }
    
    public function getlistingarray()
    {
        
//        $eid=explode(",", $ids);
//        foreach($eid as $id)
//        {
//        $email= $this->db->query("SELECT `id`,`uid`, `eid`, `email` FROM `email` WHERE `id`='$id'")->row();
//        $query=$this->db->query("DELETE FROM `email` WHERE `id`='$id'");
//        }
//        return $email;
        
        $ids=$this->input->get_post('ids');
        $data['message']=$this->listing_model->getlistingarray($ids);
        $this->load->view('json',$data);
    }
    
    public function getallcity()
    {
        $data['message']=$this->city_model->viewcity();
		$this->load->view('json',$data);
    }
    public function alladd()
    {
        $position=$this->input->get_post('position');
        $data['message']=$this->add_model->alladd($position);
		$this->load->view('json',$data);
    }
     public function sendemail()
    {
        $userid=$this->input->get_post('userid');
        $listingid=$this->input->get_post('listingid');
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
    
    function getcategorytree() {
        $data["message"]=$this->category_model->getcategorytree(0);
		$this->load->view("json",$data);
    }
    
    function getcategorydemotree() {
        $data=$this->category_model->getcategorytreeforlisting(0);
        $this->getarray($data);
        
//        print_r($data);
    }
    public function getarray($data)
    {
        $ret = array();
        for($i=0;$i<sizeof($data->children);$i++)
        {
           
//            print_r($data->children[$i]->children);
            if(empty($data->children[$i]->children))
            {
//                print_r($data->children[$i]);
                array_push($ret,$data->children[$i]);
            }else{
                $this->getarray($data->children[$i]);
            }
        }
        return $ret;
    }
    
    public function getspecialoffersbycategory()
    {
        $id=$this->input->get_post('categoryid');
        $data['message']=$this->specialoffer_model->getspecialoffersbycategory($id);
        $this->load->view('json',$data);
    }
    public function getuser()
    {
        $id=$this->input->get_post('id');
        $data['message']=$this->user_model->getuser($id);
        $this->load->view('json',$data);
    }
    
    public function addenquiryoflistingfromfrontend()
    {
        $listingid=$this->input->get_post('listingid');
        $name=$this->input->get_post('name');
        $email=$this->input->get_post('email');
        $phone=$this->input->get_post('phone');
        $comment=$this->input->get_post('comment');
        $data['message']=$this->enquiry_model->addenquiryoflistingfromfrontend($listingid,$name,$email,$phone,$comment);
        $this->load->view('json',$data);
    }
    
    public function changepassword()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id=$data['id'];
        $oldpassword=$data['oldpassword'];
        $newpassword=$data['newpassword'];
        $data['message']=$this->user_model->changefrontendpassword($id,$oldpassword,$newpassword);
        $this->load->view('json',$data);
    
    }
    
    public function addrating()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $listing=$data['listing'];
        $rating=$data['rating'];
//        $user=1;
        $user=$this->session->userdata("id");
        $data['message']=$this->listing_model->addrating($user,$listing,$rating);
        $this->load->view('json',$data);
    }
   
    public function addlatlong()
    {
        $getdetailsoflisting=$this->listing_model->getlistingdetailsforlatlong();
//        print_r($getdetailsoflisting);
        if(empty($getdetailsoflisting))
        {
//            echo "inif";
//            $data['message']=0;
//            $this->load->view('json',$data);
        }
        else
        {
//            print_r($getdetailsoflisting);
            foreach($getdetailsoflisting as $value)
            {
                $id=$value->id;
                $address=$value->address;
                $cityname=$value->cityname;
//                $areaname=$value->areaname;
//                if($areaname==null || $areaname=="")
//                {
//                    $areaname=$value->area;
//                }
                $pincode=$value->pincode;
                $state=$value->state;
                $country=$value->country;
                $region="IND";
                $lastaddress=$address." ".$cityname." ".$state;
    //            echo $lastaddress;
                $lastaddress=urlencode($lastaddress);
                $url = "https://maps.google.com/maps/api/geocode/json?address=$lastaddress&sensor=false&region=$region";
         //       echo $url;
                $response = file_get_contents($url);
                $response = json_decode($response);
              //  print_r($response);
//                echo $lat;
//                echo "<br>".$long;
                if($response)
                {
                  //  echo "in result";
                    $lat = $response->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                    $long = $response->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                   // echo $lat;
                    if($lat>0 && $long>0)
                    {

                        echo "in if".$id;
                        $updatequery=$this->db->query("UPDATE `listing` SET `lat`='$lat',`long`='$long' WHERE `id`='$id'");
                    }
                    else
                    {
                    echo "in else".$id;
                    $updatequery=$this->db->query("UPDATE `listing` SET `lat`='18.9750',`long`='72.8258' WHERE `id`='$id'");
                    }
                }
                else
                {
                    $updatequery=$this->db->query("UPDATE `listing` SET `lat`='18.9750',`long`='72.8258' WHERE `id`='$id'");
                }
            }
        }
        
    }
    
    
    
    
    
    
    
    
    function getlistingbycategory()
	{
        
        $id=$this->input->get_post('id');
        $lat=$this->input->get_post('lat');
        $long=$this->input->get_post('long');
        
//        $q="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
//FROM `listingcategory`
//LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
//LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
//LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
//WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC";
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`listingcategory`.`listing`";
        $elements[0]->sort="1";
        $elements[0]->header="listing";
        $elements[0]->alias="listing";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listingcategory`.`category`";
        $elements[1]->sort="1";
        $elements[1]->header="category";
        $elements[1]->alias="category";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`listing`.`name`";
        $elements[2]->sort="1";
        $elements[2]->header="name";
        $elements[2]->alias="name";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`listing`.`id`";
        $elements[3]->sort="1";
        $elements[3]->header="listingid";
        $elements[3]->alias="listingid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`listing`. `user`";
        $elements[4]->sort="1";
        $elements[4]->header="user";
        $elements[4]->alias="user";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`listing`.`lat`";
        $elements[5]->sort="1";
        $elements[5]->header="lat";
        $elements[5]->alias="lat";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`listing`.`long`";
        $elements[6]->sort="1";
        $elements[6]->header="long";
        $elements[6]->alias="long";
        
        $elements[7]=new stdClass();
        $elements[7]->field="`listing`.`address`";
        $elements[7]->sort="1";
        $elements[7]->header="address";
        $elements[7]->alias="address";
        
        $elements[8]=new stdClass();
        $elements[8]->field="`listing`.`city`";
        $elements[8]->sort="1";
        $elements[8]->header="city";
        $elements[8]->alias="city";
        
        $elements[9]=new stdClass();
        $elements[9]->field="`listing`.`pincode`";
        $elements[9]->sort="1";
        $elements[9]->header="pincode";
        $elements[9]->alias="pincode";
        
        $elements[10]=new stdClass();
        $elements[10]->field="`listing`.`state`";
        $elements[10]->sort="1";
        $elements[10]->header="state";
        $elements[10]->alias="state";
        
        $elements[11]=new stdClass();
        $elements[11]->field="`listing`.`country`";
        $elements[11]->sort="1";
        $elements[11]->header="country";
        $elements[11]->alias="country";
        
        $elements[12]=new stdClass();
        $elements[12]->field="`listing`.`description`";
        $elements[12]->sort="1";
        $elements[12]->header="description";
        $elements[12]->alias="description";
        
        $elements[13]=new stdClass();
        $elements[13]->field="`listing`.`logo`";
        $elements[13]->sort="1";
        $elements[13]->header="logo";
        $elements[13]->alias="logo";
        
        $elements[14]=new stdClass();
        $elements[14]->field="`listing`.`contactno`";
        $elements[14]->sort="1";
        $elements[14]->header="contactno";
        $elements[14]->alias="contactno";
        
        $elements[15]=new stdClass();
        $elements[15]->field="`listing`.`email`";
        $elements[15]->sort="1";
        $elements[15]->header="email";
        $elements[15]->alias="email";
        
        $elements[16]=new stdClass();
        $elements[16]->field="`listing`.`website`";
        $elements[16]->sort="1";
        $elements[16]->header="website";
        $elements[16]->alias="website";
        
        $elements[17]=new stdClass();
        $elements[17]->field="`listing`.`facebook`";
        $elements[17]->sort="1";
        $elements[17]->header="facebook";
        $elements[17]->alias="facebook";
        
        $elements[18]=new stdClass();
        $elements[18]->field="`listing`.`twitter`";
        $elements[18]->sort="1";
        $elements[18]->header="twitter";
        $elements[18]->alias="twitter";
        
        $elements[19]=new stdClass();
        $elements[19]->field="`listing`.`googleplus`";
        $elements[19]->sort="1";
        $elements[19]->header="googleplus";
        $elements[19]->alias="googleplus";
        
        $elements[20]=new stdClass();
        $elements[20]->field="`listing`.`yearofestablishment`";
        $elements[20]->sort="1";
        $elements[20]->header="yearofestablishment";
        $elements[20]->alias="yearofestablishment";
        
        $elements[21]=new stdClass();
        $elements[21]->field="`listing`.`timeofoperation_start`";
        $elements[21]->sort="1";
        $elements[21]->header="timeofoperation_start";
        $elements[21]->alias="timeofoperation_start";
        
        $elements[22]=new stdClass();
        $elements[22]->field="`listing`.`timeofoperation_end`";
        $elements[22]->sort="1";
        $elements[22]->header="timeofoperation_end";
        $elements[22]->alias="timeofoperation_end";
        
        $elements[23]=new stdClass();
        $elements[23]->field="`listing`.`type`";
        $elements[23]->sort="1";
        $elements[23]->header="type";
        $elements[23]->alias="type";
        
        $elements[24]=new stdClass();
        $elements[24]->field="`listing`.`credits`";
        $elements[24]->sort="1";
        $elements[24]->header="credits";
        $elements[24]->alias="credits";
        
        $elements[25]=new stdClass();
        $elements[25]->field="`listing`.`isverified`";
        $elements[25]->sort="1";
        $elements[25]->header="isverified";
        $elements[25]->alias="isverified";
        
        $elements[26]=new stdClass();
        $elements[26]->field="`listing`.`isverified`";
        $elements[26]->sort="1";
        $elements[26]->header="isverified";
        $elements[26]->alias="isverified";
        
        $elements[27]=new stdClass();
        $elements[27]->field="`listing`.`area`";
        $elements[27]->sort="1";
        $elements[27]->header="area";
        $elements[27]->alias="area";
        
        $elements[28]=new stdClass();
        $elements[28]->field="`listing`.`video`";
        $elements[28]->sort="1";
        $elements[28]->header="video";
        $elements[28]->alias="video";
        
        $elements[29]=new stdClass();
        $elements[29]->field="`category`.`banner`";
        $elements[29]->sort="1";
        $elements[29]->header="banner";
        $elements[29]->alias="banner";
        
        $elements[30]=new stdClass();
        $elements[30]->field="`category`.`name`";
        $elements[30]->sort="1";
        $elements[30]->header="categoryname";
        $elements[30]->alias="categoryname";
        
        $elements[31]=new stdClass();
        $elements[31]->field="`listing`.`deletestatus`";
        $elements[31]->sort="1";
        $elements[31]->header="deletestatus";
        $elements[31]->alias="deletestatus";
        
        $elements[32]=new stdClass();
        $elements[32]->field="`listings`.`totalratings`";
        $elements[32]->sort="1";
        $elements[32]->header="totalratings";
        $elements[32]->alias="totalratings";
        
        $elements[33]=new stdClass();
        $elements[33]->field="`listings`.`rating`";
        $elements[33]->sort="1";
        $elements[33]->header="rating";
        $elements[33]->alias="rating";
        
        $elements[33]=new stdClass();
        $elements[33]->field="ROUND(( 3959 * acos( cos( radians($lat) ) * cos( radians(`listing`. `lat` ) ) * cos( radians(`listing`.`long`) - radians($long)) + sin(radians($lat)) * sin( radians(`listing`. `lat`)))),2)";
        $elements[33]->sort="1";
        $elements[33]->header="dist";
        $elements[33]->alias="dist";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=10;
        }
        
        if($orderby=="")
        {
            $orderby="dist";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `listingcategory` LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing` LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id` LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`","WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1");
        
		$this->load->view("json",$data);
	} 
    
    
    
    
    
    
    
    function getlistingbycategorysearch()
	{
        
        $text=$this->input->get_post('text');
        $orwhere="";
//        $city=$this->input->get_post('cityname');
//        $area=$this->input->get_post('area');
        $lat=$this->input->get_post('lat');
        $long=$this->input->get_post('long');
        
        $areawhere="";
        
//        $q="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
//FROM `listingcategory`
//LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
//LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
//LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
//WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC";
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`listingcategory`.`listing`";
        $elements[0]->sort="1";
        $elements[0]->header="listing";
        $elements[0]->alias="listing";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listingcategory`.`category`";
        $elements[1]->sort="1";
        $elements[1]->header="category";
        $elements[1]->alias="category";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`listing`.`name`";
        $elements[2]->sort="1";
        $elements[2]->header="name";
        $elements[2]->alias="name";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`listing`.`id`";
        $elements[3]->sort="1";
        $elements[3]->header="listingid";
        $elements[3]->alias="listingid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`listing`. `user`";
        $elements[4]->sort="1";
        $elements[4]->header="user";
        $elements[4]->alias="user";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`listing`.`lat`";
        $elements[5]->sort="1";
        $elements[5]->header="lat";
        $elements[5]->alias="lat";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`listing`.`long`";
        $elements[6]->sort="1";
        $elements[6]->header="long";
        $elements[6]->alias="long";
        
        $elements[7]=new stdClass();
        $elements[7]->field="`listing`.`address`";
        $elements[7]->sort="1";
        $elements[7]->header="address";
        $elements[7]->alias="address";
        
        $elements[8]=new stdClass();
        $elements[8]->field="`listing`.`city`";
        $elements[8]->sort="1";
        $elements[8]->header="city";
        $elements[8]->alias="city";
        
        $elements[9]=new stdClass();
        $elements[9]->field="`listing`.`pincode`";
        $elements[9]->sort="1";
        $elements[9]->header="pincode";
        $elements[9]->alias="pincode";
        
        $elements[10]=new stdClass();
        $elements[10]->field="`listing`.`state`";
        $elements[10]->sort="1";
        $elements[10]->header="state";
        $elements[10]->alias="state";
        
        $elements[11]=new stdClass();
        $elements[11]->field="`listing`.`country`";
        $elements[11]->sort="1";
        $elements[11]->header="country";
        $elements[11]->alias="country";
        
        $elements[12]=new stdClass();
        $elements[12]->field="`listing`.`description`";
        $elements[12]->sort="1";
        $elements[12]->header="description";
        $elements[12]->alias="description";
        
        $elements[13]=new stdClass();
        $elements[13]->field="`listing`.`logo`";
        $elements[13]->sort="1";
        $elements[13]->header="logo";
        $elements[13]->alias="logo";
        
        $elements[14]=new stdClass();
        $elements[14]->field="`listing`.`contactno`";
        $elements[14]->sort="1";
        $elements[14]->header="contactno";
        $elements[14]->alias="contactno";
        
        $elements[15]=new stdClass();
        $elements[15]->field="`listing`.`email`";
        $elements[15]->sort="1";
        $elements[15]->header="email";
        $elements[15]->alias="email";
        
        $elements[16]=new stdClass();
        $elements[16]->field="`listing`.`website`";
        $elements[16]->sort="1";
        $elements[16]->header="website";
        $elements[16]->alias="website";
        
        $elements[17]=new stdClass();
        $elements[17]->field="`listing`.`facebook`";
        $elements[17]->sort="1";
        $elements[17]->header="facebook";
        $elements[17]->alias="facebook";
        
        $elements[18]=new stdClass();
        $elements[18]->field="`listing`.`twitter`";
        $elements[18]->sort="1";
        $elements[18]->header="twitter";
        $elements[18]->alias="twitter";
        
        $elements[19]=new stdClass();
        $elements[19]->field="`listing`.`googleplus`";
        $elements[19]->sort="1";
        $elements[19]->header="googleplus";
        $elements[19]->alias="googleplus";
        
        $elements[20]=new stdClass();
        $elements[20]->field="`listing`.`yearofestablishment`";
        $elements[20]->sort="1";
        $elements[20]->header="yearofestablishment";
        $elements[20]->alias="yearofestablishment";
        
        $elements[21]=new stdClass();
        $elements[21]->field="`listing`.`timeofoperation_start`";
        $elements[21]->sort="1";
        $elements[21]->header="timeofoperation_start";
        $elements[21]->alias="timeofoperation_start";
        
        $elements[22]=new stdClass();
        $elements[22]->field="`listing`.`timeofoperation_end`";
        $elements[22]->sort="1";
        $elements[22]->header="timeofoperation_end";
        $elements[22]->alias="timeofoperation_end";
        
        $elements[23]=new stdClass();
        $elements[23]->field="`listing`.`type`";
        $elements[23]->sort="1";
        $elements[23]->header="type";
        $elements[23]->alias="type";
        
        $elements[24]=new stdClass();
        $elements[24]->field="`listing`.`credits`";
        $elements[24]->sort="1";
        $elements[24]->header="credits";
        $elements[24]->alias="credits";
        
        $elements[25]=new stdClass();
        $elements[25]->field="`listing`.`isverified`";
        $elements[25]->sort="1";
        $elements[25]->header="isverified";
        $elements[25]->alias="isverified";
        
        $elements[26]=new stdClass();
        $elements[26]->field="`listing`.`isverified`";
        $elements[26]->sort="1";
        $elements[26]->header="isverified";
        $elements[26]->alias="isverified";
        
        $elements[27]=new stdClass();
        $elements[27]->field="`listing`.`area`";
        $elements[27]->sort="1";
        $elements[27]->header="area";
        $elements[27]->alias="area";
        
        $elements[28]=new stdClass();
        $elements[28]->field="`listing`.`video`";
        $elements[28]->sort="1";
        $elements[28]->header="video";
        $elements[28]->alias="video";
        
        $elements[29]=new stdClass();
        $elements[29]->field="`category`.`banner`";
        $elements[29]->sort="1";
        $elements[29]->header="banner";
        $elements[29]->alias="banner";
        
        $elements[30]=new stdClass();
        $elements[30]->field="`category`.`name`";
        $elements[30]->sort="1";
        $elements[30]->header="categoryname";
        $elements[30]->alias="categoryname";
        
        $elements[31]=new stdClass();
        $elements[31]->field="`listing`.`deletestatus`";
        $elements[31]->sort="1";
        $elements[31]->header="deletestatus";
        $elements[31]->alias="deletestatus";
        
        $elements[32]=new stdClass();
        $elements[32]->field="`listings`.`totalratings`";
        $elements[32]->sort="1";
        $elements[32]->header="totalratings";
        $elements[32]->alias="totalratings";
        
        $elements[33]=new stdClass();
        $elements[33]->field="`listings`.`rating`";
        $elements[33]->sort="1";
        $elements[33]->header="rating";
        $elements[33]->alias="rating";
        
        $elements[33]=new stdClass();
        $elements[33]->field="ROUND(( 3959 * acos( cos( radians($lat) ) * cos( radians(`listing`. `lat` ) ) * cos( radians(`listing`.`long`) - radians($long)) + sin(radians($lat)) * sin( radians(`listing`. `lat`)))),2)";
        $elements[33]->sort="1";
        $elements[33]->header="dist";
        $elements[33]->alias="dist";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=10;
        }
        
        if($orderby=="")
        {
            $orderby="dist";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `listingcategory` LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing` LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id` LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`","WHERE (`category`.`name` LIKE '%$text%' OR `listing`.`name` LIKE '%$text%') AND `listing`.`deletestatus`=1 AND `listing`.`status`=1");
        
		$this->load->view("json",$data);
	} 
    
    
    
    function getlistingaftersearch()
	{
        
        $category=$this->input->get_post('categoryname');
        $city=$this->input->get_post('cityname');
        $area=$this->input->get_post('area');
        $lat=$this->input->get_post('lat');
        $long=$this->input->get_post('long');
        
        $areawhere="";
        if($area=="")
        {
            $areawhere="";
        }
        else
        {
            $areawhere=" AND `location`.`id`= '$area' ";
        }
        
//        $id=$this->input->get_post('id');
        
//        $q="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
//FROM `listingcategory`
//LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
//LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
//LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
//WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC";
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`listingcategory`.`listing`";
        $elements[0]->sort="1";
        $elements[0]->header="listing";
        $elements[0]->alias="listing";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listingcategory`.`category`";
        $elements[1]->sort="1";
        $elements[1]->header="category";
        $elements[1]->alias="category";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`listing`.`name`";
        $elements[2]->sort="1";
        $elements[2]->header="name";
        $elements[2]->alias="name";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`listing`.`id`";
        $elements[3]->sort="1";
        $elements[3]->header="listingid";
        $elements[3]->alias="listingid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`listing`. `user`";
        $elements[4]->sort="1";
        $elements[4]->header="user";
        $elements[4]->alias="user";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`listing`.`lat`";
        $elements[5]->sort="1";
        $elements[5]->header="lat";
        $elements[5]->alias="lat";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`listing`.`long`";
        $elements[6]->sort="1";
        $elements[6]->header="long";
        $elements[6]->alias="long";
        
        $elements[7]=new stdClass();
        $elements[7]->field="`listing`.`address`";
        $elements[7]->sort="1";
        $elements[7]->header="address";
        $elements[7]->alias="address";
        
        $elements[8]=new stdClass();
        $elements[8]->field="`listing`.`city`";
        $elements[8]->sort="1";
        $elements[8]->header="city";
        $elements[8]->alias="city";
        
        $elements[9]=new stdClass();
        $elements[9]->field="`listing`.`pincode`";
        $elements[9]->sort="1";
        $elements[9]->header="pincode";
        $elements[9]->alias="pincode";
        
        $elements[10]=new stdClass();
        $elements[10]->field="`listing`.`state`";
        $elements[10]->sort="1";
        $elements[10]->header="state";
        $elements[10]->alias="state";
        
        $elements[11]=new stdClass();
        $elements[11]->field="`listing`.`country`";
        $elements[11]->sort="1";
        $elements[11]->header="country";
        $elements[11]->alias="country";
        
        $elements[12]=new stdClass();
        $elements[12]->field="`listing`.`description`";
        $elements[12]->sort="1";
        $elements[12]->header="description";
        $elements[12]->alias="description";
        
        $elements[13]=new stdClass();
        $elements[13]->field="`listing`.`logo`";
        $elements[13]->sort="1";
        $elements[13]->header="logo";
        $elements[13]->alias="logo";
        
        $elements[14]=new stdClass();
        $elements[14]->field="`listing`.`contactno`";
        $elements[14]->sort="1";
        $elements[14]->header="contactno";
        $elements[14]->alias="contactno";
        
        $elements[15]=new stdClass();
        $elements[15]->field="`listing`.`email`";
        $elements[15]->sort="1";
        $elements[15]->header="email";
        $elements[15]->alias="email";
        
        $elements[16]=new stdClass();
        $elements[16]->field="`listing`.`website`";
        $elements[16]->sort="1";
        $elements[16]->header="website";
        $elements[16]->alias="website";
        
        $elements[17]=new stdClass();
        $elements[17]->field="`listing`.`facebook`";
        $elements[17]->sort="1";
        $elements[17]->header="facebook";
        $elements[17]->alias="facebook";
        
        $elements[18]=new stdClass();
        $elements[18]->field="`listing`.`twitter`";
        $elements[18]->sort="1";
        $elements[18]->header="twitter";
        $elements[18]->alias="twitter";
        
        $elements[19]=new stdClass();
        $elements[19]->field="`listing`.`googleplus`";
        $elements[19]->sort="1";
        $elements[19]->header="googleplus";
        $elements[19]->alias="googleplus";
        
        $elements[20]=new stdClass();
        $elements[20]->field="`listing`.`yearofestablishment`";
        $elements[20]->sort="1";
        $elements[20]->header="yearofestablishment";
        $elements[20]->alias="yearofestablishment";
        
        $elements[21]=new stdClass();
        $elements[21]->field="`listing`.`timeofoperation_start`";
        $elements[21]->sort="1";
        $elements[21]->header="timeofoperation_start";
        $elements[21]->alias="timeofoperation_start";
        
        $elements[22]=new stdClass();
        $elements[22]->field="`listing`.`timeofoperation_end`";
        $elements[22]->sort="1";
        $elements[22]->header="timeofoperation_end";
        $elements[22]->alias="timeofoperation_end";
        
        $elements[23]=new stdClass();
        $elements[23]->field="`listing`.`type`";
        $elements[23]->sort="1";
        $elements[23]->header="type";
        $elements[23]->alias="type";
        
        $elements[24]=new stdClass();
        $elements[24]->field="`listing`.`credits`";
        $elements[24]->sort="1";
        $elements[24]->header="credits";
        $elements[24]->alias="credits";
        
        $elements[25]=new stdClass();
        $elements[25]->field="`listing`.`isverified`";
        $elements[25]->sort="1";
        $elements[25]->header="isverified";
        $elements[25]->alias="isverified";
        
        $elements[26]=new stdClass();
        $elements[26]->field="`listing`.`isverified`";
        $elements[26]->sort="1";
        $elements[26]->header="isverified";
        $elements[26]->alias="isverified";
        
        $elements[27]=new stdClass();
        $elements[27]->field="`listing`.`area`";
        $elements[27]->sort="1";
        $elements[27]->header="area";
        $elements[27]->alias="area";
        
        $elements[28]=new stdClass();
        $elements[28]->field="`listing`.`video`";
        $elements[28]->sort="1";
        $elements[28]->header="video";
        $elements[28]->alias="video";
        
        $elements[29]=new stdClass();
        $elements[29]->field="`category`.`banner`";
        $elements[29]->sort="1";
        $elements[29]->header="banner";
        $elements[29]->alias="banner";
        
        $elements[30]=new stdClass();
        $elements[30]->field="`category`.`name`";
        $elements[30]->sort="1";
        $elements[30]->header="categoryname";
        $elements[30]->alias="categoryname";
        
        $elements[31]=new stdClass();
        $elements[31]->field="`listing`.`deletestatus`";
        $elements[31]->sort="1";
        $elements[31]->header="deletestatus";
        $elements[31]->alias="deletestatus";
        
        $elements[32]=new stdClass();
        $elements[32]->field="`listings`.`totalratings`";
        $elements[32]->sort="1";
        $elements[32]->header="totalratings";
        $elements[32]->alias="totalratings";
        
        $elements[33]=new stdClass();
        $elements[33]->field="`listings`.`rating`";
        $elements[33]->sort="1";
        $elements[33]->header="rating";
        $elements[33]->alias="rating";
        
        $elements[34]=new stdClass();
        $elements[34]->field="CONCAT(`category`.`name`,`listing`.`name`)";
        $elements[34]->sort="1";
        $elements[34]->header="fullname";
        $elements[34]->alias="fullname";
        
        $elements[35]=new stdClass();
        $elements[35]->field="ROUND(( 3959 * acos( cos( radians($lat) ) * cos( radians(`listing`. `lat` ) ) * cos( radians(`listing`.`long`) - radians($long)) + sin(radians($lat)) * sin( radians(`listing`. `lat`)))),2)";
        $elements[35]->sort="1";
        $elements[35]->header="dist";
        $elements[35]->alias="dist";
        
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
            $orderby="dist";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `listingcategory` LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing` LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id` LEFT OUTER JOIN `city` ON `city`.`id`=`listing`.`city` LEFT OUTER JOIN `location` ON `location`.`id`=`listing`.`area` LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`","WHERE `city`.`id` = '$city' AND `listing`.`deletestatus`='1' AND `listing`.`status`=1 $areawhere","","HAVING `fullname` LIKE '%$category%'");
        
		$this->load->view("json",$data);
	} 
    
    function getlistingaftersearchnew()
	{
        $category=$this->input->get_post('categoryname');
        $orwhere="";
        $categoryarray=explode(" ",$category);
//        print_r($categoryarray);
        if(count($categoryarray)>1)
        {
            $orwhere.="";
            foreach($categoryarray as $key=>$val)
            {
                if($key==0)
                {
                    $orwhere.=" OR `fullname` LIKE '%$val%'";
                }
                else
                {
                    $orwhere.=" OR `fullname` LIKE '%$val%'";
                }
            }
            $orwhere.=" ";
        }
        else
        {
            $orwhere.="";
        }
//        echo $orwhere;
        $city=$this->input->get_post('cityname');
        $area=$this->input->get_post('area');
        $lat=$this->input->get_post('lat');
        $long=$this->input->get_post('long');
        
        $areawhere="";
        if($area=="")
        {
            $areawhere="";
        }
        else
        {
            $areawhere=" AND `location`.`id`= '$area' ";
        }
        
//        $id=$this->input->get_post('id');
        
//        $q="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
//FROM `listingcategory`
//LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
//LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
//LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
//WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC";
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`listingcategory`.`listing`";
        $elements[0]->sort="1";
        $elements[0]->header="listing";
        $elements[0]->alias="listing";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`listingcategory`.`category`";
        $elements[1]->sort="1";
        $elements[1]->header="category";
        $elements[1]->alias="category";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`listing`.`name`";
        $elements[2]->sort="1";
        $elements[2]->header="name";
        $elements[2]->alias="name";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`listing`.`id`";
        $elements[3]->sort="1";
        $elements[3]->header="listingid";
        $elements[3]->alias="listingid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`listing`. `user`";
        $elements[4]->sort="1";
        $elements[4]->header="user";
        $elements[4]->alias="user";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`listing`.`lat`";
        $elements[5]->sort="1";
        $elements[5]->header="lat";
        $elements[5]->alias="lat";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`listing`.`long`";
        $elements[6]->sort="1";
        $elements[6]->header="long";
        $elements[6]->alias="long";
        
        $elements[7]=new stdClass();
        $elements[7]->field="`listing`.`address`";
        $elements[7]->sort="1";
        $elements[7]->header="address";
        $elements[7]->alias="address";
        
        $elements[8]=new stdClass();
        $elements[8]->field="`listing`.`city`";
        $elements[8]->sort="1";
        $elements[8]->header="city";
        $elements[8]->alias="city";
        
        $elements[9]=new stdClass();
        $elements[9]->field="`listing`.`pincode`";
        $elements[9]->sort="1";
        $elements[9]->header="pincode";
        $elements[9]->alias="pincode";
        
        $elements[10]=new stdClass();
        $elements[10]->field="`listing`.`state`";
        $elements[10]->sort="1";
        $elements[10]->header="state";
        $elements[10]->alias="state";
        
        $elements[11]=new stdClass();
        $elements[11]->field="`listing`.`country`";
        $elements[11]->sort="1";
        $elements[11]->header="country";
        $elements[11]->alias="country";
        
        $elements[12]=new stdClass();
        $elements[12]->field="`listing`.`description`";
        $elements[12]->sort="1";
        $elements[12]->header="description";
        $elements[12]->alias="description";
        
        $elements[13]=new stdClass();
        $elements[13]->field="`listing`.`logo`";
        $elements[13]->sort="1";
        $elements[13]->header="logo";
        $elements[13]->alias="logo";
        
        $elements[14]=new stdClass();
        $elements[14]->field="`listing`.`contactno`";
        $elements[14]->sort="1";
        $elements[14]->header="contactno";
        $elements[14]->alias="contactno";
        
        $elements[15]=new stdClass();
        $elements[15]->field="`listing`.`email`";
        $elements[15]->sort="1";
        $elements[15]->header="email";
        $elements[15]->alias="email";
        
        $elements[16]=new stdClass();
        $elements[16]->field="`listing`.`website`";
        $elements[16]->sort="1";
        $elements[16]->header="website";
        $elements[16]->alias="website";
        
        $elements[17]=new stdClass();
        $elements[17]->field="`listing`.`facebook`";
        $elements[17]->sort="1";
        $elements[17]->header="facebook";
        $elements[17]->alias="facebook";
        
        $elements[18]=new stdClass();
        $elements[18]->field="`listing`.`twitter`";
        $elements[18]->sort="1";
        $elements[18]->header="twitter";
        $elements[18]->alias="twitter";
        
        $elements[19]=new stdClass();
        $elements[19]->field="`listing`.`googleplus`";
        $elements[19]->sort="1";
        $elements[19]->header="googleplus";
        $elements[19]->alias="googleplus";
        
        $elements[20]=new stdClass();
        $elements[20]->field="`listing`.`yearofestablishment`";
        $elements[20]->sort="1";
        $elements[20]->header="yearofestablishment";
        $elements[20]->alias="yearofestablishment";
        
        $elements[21]=new stdClass();
        $elements[21]->field="`listing`.`timeofoperation_start`";
        $elements[21]->sort="1";
        $elements[21]->header="timeofoperation_start";
        $elements[21]->alias="timeofoperation_start";
        
        $elements[22]=new stdClass();
        $elements[22]->field="`listing`.`timeofoperation_end`";
        $elements[22]->sort="1";
        $elements[22]->header="timeofoperation_end";
        $elements[22]->alias="timeofoperation_end";
        
        $elements[23]=new stdClass();
        $elements[23]->field="`listing`.`type`";
        $elements[23]->sort="1";
        $elements[23]->header="type";
        $elements[23]->alias="type";
        
        $elements[24]=new stdClass();
        $elements[24]->field="`listing`.`credits`";
        $elements[24]->sort="1";
        $elements[24]->header="credits";
        $elements[24]->alias="credits";
        
        $elements[25]=new stdClass();
        $elements[25]->field="`listing`.`isverified`";
        $elements[25]->sort="1";
        $elements[25]->header="isverified";
        $elements[25]->alias="isverified";
        
        $elements[26]=new stdClass();
        $elements[26]->field="`listing`.`isverified`";
        $elements[26]->sort="1";
        $elements[26]->header="isverified";
        $elements[26]->alias="isverified";
        
        $elements[27]=new stdClass();
        $elements[27]->field="`listing`.`area`";
        $elements[27]->sort="1";
        $elements[27]->header="area";
        $elements[27]->alias="area";
        
        $elements[28]=new stdClass();
        $elements[28]->field="`listing`.`video`";
        $elements[28]->sort="1";
        $elements[28]->header="video";
        $elements[28]->alias="video";
        
        $elements[29]=new stdClass();
        $elements[29]->field="`category`.`banner`";
        $elements[29]->sort="1";
        $elements[29]->header="banner";
        $elements[29]->alias="banner";
        
        $elements[30]=new stdClass();
        $elements[30]->field="`category`.`name`";
        $elements[30]->sort="1";
        $elements[30]->header="categoryname";
        $elements[30]->alias="categoryname";
        
        $elements[31]=new stdClass();
        $elements[31]->field="`listing`.`deletestatus`";
        $elements[31]->sort="1";
        $elements[31]->header="deletestatus";
        $elements[31]->alias="deletestatus";
        
        $elements[32]=new stdClass();
        $elements[32]->field="`listings`.`totalratings`";
        $elements[32]->sort="1";
        $elements[32]->header="totalratings";
        $elements[32]->alias="totalratings";
        
        $elements[33]=new stdClass();
        $elements[33]->field="`listings`.`rating`";
        $elements[33]->sort="1";
        $elements[33]->header="rating";
        $elements[33]->alias="rating";
        
        $elements[34]=new stdClass();
        $elements[34]->field="CONCAT(`category`.`name`,`listing`.`name`)";
        $elements[34]->sort="1";
        $elements[34]->header="fullname";
        $elements[34]->alias="fullname";
        
        $elements[35]=new stdClass();
        $elements[35]->field="ROUND(( 3959 * acos( cos( radians($lat) ) * cos( radians(`listing`. `lat` ) ) * cos( radians(`listing`.`long`) - radians($long)) + sin(radians($lat)) * sin( radians(`listing`. `lat`)))),2)";
        $elements[35]->sort="1";
        $elements[35]->header="dist";
        $elements[35]->alias="dist";
        
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
            $orderby="dist";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements," FROM `listingcategory` LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing` LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id` LEFT OUTER JOIN `city` ON `city`.`id`=`listing`.`city` LEFT OUTER JOIN `location` ON `location`.`id`=`listing`.`area` LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`","WHERE `city`.`id` = '$city' AND `listing`.`deletestatus`='1' AND `listing`.`status`=1 $areawhere  ","","HAVING `fullname` LIKE '%$category%' $orwhere");
        
		$this->load->view("json",$data);
	} 
    
    
    public function addlatlongforlocation()
    {
        $getdetailsoflocation=$this->city_model->getlocationforlatlong();
//        print_r($getdetailsoflisting);
        if(empty($getdetailsoflocation))
        {
            echo "inif";
//            $data['message']=0;
//            $this->load->view('json',$data);
        }
        else
        {
//            print_r($getdetailsoflisting);
            foreach($getdetailsoflocation as $value)
            {
                $id=$value->id;
                $pincode=$value->pincode;
                $cityname=$value->cityname;
                $name=$value->name;
                $lat=$value->lat;
                $long=$value->long;
//                if($areaname==null || $areaname=="")
//                {
//                    $areaname=$value->area;
//                }
//                $pincode=$value->pincode;
//                $state=$value->state;
                $country="Maharashtra";
                $region="IND";
                $lastaddress=$name." ".$cityname." ".$country." ".$pincode;
    //            echo $lastaddress;
                $lastaddress=urlencode($lastaddress);
                
                $url = "https://maps.google.com/maps/api/geocode/json?address=$lastaddress&sensor=false&region=$region";
         //       echo $url;
                $response = file_get_contents($url);
                $response = json_decode($response);
              //  print_r($response);
//                echo $lat;
//                echo "<br>".$long;
                if($response)
                {
                    echo "in result ".$id;
                    $lat = $response->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                    $long = $response->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                   // echo $lat;
                    if($lat>0 && $long>0)
                    {

                        $updatequery=$this->db->query("UPDATE `location` SET `lat`='$lat',`long`='$long' WHERE `id`='$id'");
                    }
                    else
                    {
                    echo "in else".$id;
                    $updatequery=$this->db->query("UPDATE `location` SET `lat`='18.9750',`long`='72.8258' WHERE `id`='$id'");
                    }
                }
                else
                {
                    $updatequery=$this->db->query("UPDATE `location` SET `lat`='18.9750',`long`='72.8258' WHERE `id`='$id'");
                }
            }
        }
        
    }
    
    
}
?>