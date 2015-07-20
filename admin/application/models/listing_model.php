<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Listing_model extends CI_Model
{
	
	public function create($name,$user,$lat,$long,$address,$city,$pincode,$state,$country,$description,$contact,$email,$website,$facebookuserid,$googleplus,$twitter,$yearofestablishment,$timeofoperation_start,$timeofoperation_end,$type,$credits,$isverified,$video,$logo,$category,$modeofpayment,$daysofoperation,$pointer,$area,$mobile,$status,$pointerstartdate,$pointerenddate)
	{
		$data  = array(
			'name' => $name,
			'user' => $user,
			'lat' => $lat,
			'long' => $long,
            'address'=>$address,
            'city'=>$city,
            'pincode'=>$pincode,
            'state' => $state,
            'country' => $country,
            'description' => $description,
			'contactno' => $contact,
			'email' => $email,
            'website'=> $website,
			'facebook' => $facebookuserid,
            'googleplus' => $googleplus,
            'twitter' => $twitter,
            'yearofestablishment' => $yearofestablishment,
            'timeofoperation_start' => $timeofoperation_start,
            'timeofoperation_end' => $timeofoperation_end,
            'type' => $type,
            'credits' => $credits,
            'isverified' => $isverified,
            'video' => $video,
            'pointer' => $pointer,
            'area' => $area,
            'mobile' => $mobile,
            'status' => $status,
            'pointerstartdate' => $pointerstartdate,
            'pointerenddate' => $pointerenddate,
            'logo' => $logo
		);
		$query=$this->db->insert( 'listing', $data );
		$listingid=$this->db->insert_id();
        foreach($category AS $key=>$value)
        {
           $this->listing_model->createcategorybylisting($value,$listingid);
        }
        foreach($modeofpayment AS $key=>$value)
        {
           $this->listing_model->createmodeofpaymentbylisting($value,$listingid);
        }
        foreach($daysofoperation AS $key=>$value)
        {
           $this->listing_model->createdaysofoperationbylisting($value,$listingid);
        }
//		print_r($category);
//        print_r($modeofpayment);
//        print_r($daysofoperation);
		if(!$query)
			return  0;
		else
			return  1;
	}
    public function getlistingarray($ids)
    {
        $query=$this->db->query("SELECT * from `listing` WHERE `id` IN ($ids)")->result();
        return $query;
    }
    public function createcategorybylisting($value,$listingid)
	{
		$data  = array(
			'category' => $value,
			'listing' => $listingid
		);
		$query=$this->db->insert( 'listingcategory', $data );
		return  1;
	}
    public function createmodeofpaymentbylisting($value,$listingid)
	{
		$data  = array(
			'modeofpayment' => $value,
			'listing' => $listingid
		);
		$query=$this->db->insert( 'listingmodeofpayment', $data );
		return  1;
	}
    public function createdaysofoperationbylisting($value,$listingid)
	{
		$data  = array(
			'daysofoperation' => $value,
			'listing' => $listingid
		);
		$query=$this->db->insert( 'listingdaysofoperation', $data );
		return  1;
	}
	function viewlisting()
	{
		$query="SELECT `id`, `name`, `user`, `lat`, `long`, `address`, `city`, `pincode`, `state`, `country`, `description`, `logo`, `contactno`, `email`, `website`, `facebook`, `twitter`, `googleplus`, `yearofestablishment`, `timeofoperation_start`, `timeofoperation_end`, `type`, `credits`, `isverified`, `video`, `deletestatus` FROM `listing` WHERE `deletestatus`=1 ";
	   
		$query=$this->db->query($query)->result();
		return $query;
	}
    
	public function getisverifieddropdown()
	{
		$isverified= array(
			 "1" => "Yes",
			 "0" => "No",
			);
		return $isverified;
	}
	public function gettypedropdown()
	{
		$type= array(
			 "1" => "Free",
			 "0" => "Paid",
			);
		return $type;
	}
    
	public function getstatusdropdown()
	{
		$status= array(
			 "1" => "Enabled",
			 "0" => "Disabled",
			);
		return $status;
	}
    
    public function getuserdropdown()
	{
		$query=$this->db->query("SELECT * FROM `user`  ORDER BY `id` ASC")->result();
		$return=array(
		"" => ""
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->firstname." ".$row->lastname;
		}
		
		return $return;
	}
    public function getlistingdropdown()
	{
		$query=$this->db->query("SELECT * FROM `listing`  ORDER BY `id` ASC")->result();
		$return=array(
		"" => ""
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->name;
		}
		
		return $return;
	}
    public function getlistingforspecialofferdropdown()
	{
		$query=$this->db->query("SELECT * FROM `listing`  ORDER BY `id` ASC")->result();
		$return=array(
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->name;
		}
		
		return $return;
	}
	public function beforeedit( $id )
	{
		$this->db->where( 'id', $id );
		$query=$this->db->get( 'listing' )->row();
		return $query;
	}
	public function beforeeditlistingimages( $id )
	{
		$this->db->where( 'id', $id );
		$query=$this->db->get( 'listingimages' )->row();
		return $query;
	}
    
	public function beforeedituserlistingrating( $id )
	{
		$this->db->where( 'id', $id );
		$query=$this->db->get( 'userlistingrating' )->row();
		return $query;
	}
    
	public function getlogobylistingid($id)
	{
		$query=$this->db->query("SELECT `logo` FROM `listing` WHERE `id`='$id'")->row();
		return $query;
	}
	
	public function edit($id,$name,$user,$lat,$long,$address,$city,$pincode,$state,$country,$description,$contact,$email,$website,$facebookuserid,$googleplus,$twitter,$yearofestablishment,$timeofoperation_start,$timeofoperation_end,$type,$credits,$isverified,$video,$logo,$category,$modeofpayment,$daysofoperation,$pointer,$area,$mobile,$status,$pointerstartdate,$pointerenddate)
	{
		$data  = array(
			'name' => $name,
			'user' => $user,
			'lat' => $lat,
			'long' => $long,
            'address'=>$address,
            'city'=>$city,
            'pincode'=>$pincode,
            'state' => $state,
            'country' => $country,
            'description' => $description,
			'contactno' => $contact,
			'email' => $email,
            'website'=> $website,
			'facebook' => $facebookuserid,
            'googleplus' => $googleplus,
            'twitter' => $twitter,
            'yearofestablishment' => $yearofestablishment,
            'timeofoperation_start' => $timeofoperation_start,
            'timeofoperation_end' => $timeofoperation_end,
            'type' => $type,
            'credits' => $credits,
            'isverified' => $isverified,
            'video' => $video,
            'pointer' => $pointer,
            'area' => $area,
            'mobile' => $mobile,
            'status' => $status,
            'pointerstartdate' => $pointerstartdate,
            'pointerenddate' => $pointerenddate,
            'logo' => $logo
		);
		
		$this->db->where( 'id', $id );
		$query=$this->db->update( 'listing', $data );
        $querydeletecategory=$this->db->query("DELETE FROM `listingcategory` WHERE `listing`='$id'");
        $querydeletemodeofpayment=$this->db->query("DELETE FROM `listingmodeofpayment` WHERE `listing`='$id'");
        $querydeletedaysofoperation=$this->db->query("DELETE FROM `listingdaysofoperation` WHERE `listing`='$id'");
        foreach($category AS $key=>$value)
        {
           $this->listing_model->createcategorybylisting($value,$id);
        }
        foreach($modeofpayment AS $key=>$value)
        {
           $this->listing_model->createmodeofpaymentbylisting($value,$id);
        }
        foreach($daysofoperation AS $key=>$value)
        {
           $this->listing_model->createdaysofoperationbylisting($value,$id);
        }
		return 1;
	}
	function deletelisting($id)
	{
		$query=$this->db->query("UPDATE `listing` SET `deletestatus`=0 WHERE `id`='$id'");
	}
	function changepassword($id,$password)
	{
		$data  = array(
			'password' =>md5($password),
		);
		$this->db->where('id',$id);
		$query=$this->db->update( 'user', $data );
		if(!$query)
			return  0;
		else
			return  1;
	}
	public function getaccesslevels()
	{
		$return=array();
		$query=$this->db->query("SELECT * FROM `accesslevel` ORDER BY `id` ASC")->result();
		$accesslevel=$this->session->userdata('accesslevel');
			foreach($query as $row)
			{
				if($accesslevel==1)
				{
					$return[$row->id]=$row->name;
				}
				else if($accesslevel==2)
				{
					if($row->id > $accesslevel)
					{
						$return[$row->id]=$row->name;
					}
				}
				else if($accesslevel==3)
				{
					if($row->id > $accesslevel)
					{
						$return[$row->id]=$row->name;
					}
				}
				else if($accesslevel==4)
				{
					if($row->id == $accesslevel)
					{
						$return[$row->id]=$row->name;
					}
				}
			}
	
		return $return;
	}
	function changestatus($id)
	{
		$query=$this->db->query("SELECT `status` FROM `user` WHERE `id`='$id'")->row();
		$status=$query->status;
		if($status==1)
		{
			$status=0;
		}
		else if($status==0)
		{
			$status=1;
		}
		$data  = array(
			'status' =>$status,
		);
		$this->db->where('id',$id);
		$query=$this->db->update( 'user', $data );
		if(!$query)
			return  0;
		else
			return  1;
	}
	
	function saveuserlog($id,$action)
	{
		$fromuser = $this->session->userdata('id');
		$data2  = array(
			'onuser' => $id,
			'fromuser' => $fromuser,
			'description' => $action,
		);
		$query2=$this->db->insert( 'userlog', $data2 );
	}
    
	function viewlistingimages($id)
	{
		$query="SELECT `id`, `listing`, `image`, `order`, `timestamp` 
        FROM `listingimages` 
        WHERE `listing`='$id'";
	   
		$query=$this->db->query($query)->result();
		return $query;
	}
	function viewuserlistingrating($id)
	{
		$query="SELECT `userlistingrating`.`id`, `userlistingrating`.`user`, `userlistingrating`.`rating`, `userlistingrating`.`listing`, `userlistingrating`.`timestamp` ,`user`.`firstname` AS `firstname`,`user`.`lastname` AS `lastname`,`listing`.`name` AS `listingname`
        FROM `userlistingrating` 
        LEFT OUTER JOIN `user` ON `userlistingrating`.`user`=`user`.`id`
        LEFT OUTER JOIN `listing` ON `userlistingrating`.`listing`=`listing`.`id`
        WHERE `listing`='$id'";
	   
		$query=$this->db->query($query)->result();
		return $query;
	}
    public function createlistingimages($listing,$order,$image)
	{
		$data  = array(
			'listing' => $listing,
			'image' => $image,
			'order' => $order
		);
		$query=$this->db->insert( 'listingimages', $data );
		if(!$query)
			return  0;
		else
			return  1;
	}
    
    public function createuserlistingrating($user,$listing,$rating)
	{
		$data  = array(
			'listing' => $listing,
			'user' => $user,
			'rating' => $rating
		);
        $queryselect=$this->db->query("SELECT * FROM `userlistingrating` WHERE `user`='$user' AND `listing`='$listing'")->row();
        if(empty($queryselect))
        {
            $query=$this->db->insert( 'userlistingrating', $data );  
            return 1;
        }
        else
        {
            return  0;
        }
	}
    
	public function editlistingimages($id,$order,$image,$listing)
	{
		$data  = array(
			'listing' => $listing,
			'image' => $image,
			'order' => $order
		);
		
		$this->db->where( 'id', $id );
		$query=$this->db->update( 'listingimages', $data );
        
		return 1;
	}
	public function edituserlistingrating($id,$user,$listing,$rating)
	{
		$data  = array(
			'listing' => $listing,
			'user' => $user,
			'rating' => $rating
		);
		
		$this->db->where( 'id', $id );
		$query=$this->db->update( 'userlistingrating', $data );
        
		return 1;
	}
	function deletelistingimages($id)
	{
		$query=$this->db->query("DELETE FROM `listingimages` WHERE `id`='$id'");
	}
    
	function deleteuserlistingrating($id)
	{
		$query=$this->db->query("DELETE FROM `userlistingrating` WHERE `id`='$id'");
	}
    
	public function getlistingimagesbyid($id)
	{
		$query=$this->db->query("SELECT `image` FROM `listingimages` WHERE `id`='$id'")->row();
		return $query;
	}
    
    //frontend apis
    
	public function getlistingbycategory($id)
	{
        $q="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
FROM `listingcategory`
LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC";
//        echo $q;
        
//        SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
//FROM `listingcategory`
//LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
//LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
//INNER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
//WHERE `listingcategory`.`category`='29' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC
        
        
		$query=$this->db->query("SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`area`, `listing`.`video`,`category`.`banner`,`category`.`name` AS `categoryname`,`listing`.`deletestatus` ,`listings`.`totalratings`,`listings`.`rating`
FROM `listingcategory`
LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
LEFT OUTER JOIN `category` ON `listingcategory`.`category`=`category`.`id`
LEFT OUTER JOIN (SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating`,`listing` FROM `userlistingrating` GROUP BY `listing`) as `listings` ON `listings`.`listing`=`listing`.`id`
WHERE `listingcategory`.`category`='$id' AND `listing`.`deletestatus`=1 AND `listing`.`status`=1 ORDER BY `listing`.`pointer` DESC")->result();
        
//		foreach($query as $p_row)
//		{
//			$listing = $p_row->listing;
//			$rating=$this->db->query("SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating` FROM `userlistingrating` WHERE `listing`='$listing'")->row();
//            $p_row->rating=$rating->rating;
//            $p_row->totalratings=$rating->totalratings;
//		}
		return $query;
	}
	public function getonelistingbyid($id)
	{
		$query['listing']=$this->db->query("SELECT `listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`video` , `listing`.`area`
FROM `listing`
WHERE `listing`.`id`='$id'")->row();
        
		$query['categories']=$this->db->query("SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`category`.`name` AS `categoryname` ,`category`.`banner` AS `banner`
FROM `listingcategory`
LEFT OUTER JOIN `category` ON `category`.`id`=`listingcategory`.`category`
WHERE `listingcategory`.`listing`='$id'")->result();
        
		$query['images']=$this->db->query("SELECT `listingimages`.`listing`, `listingimages`.`image` , `listingimages`.`order` 
FROM `listingimages`
WHERE `listingimages`.`listing`='$id' ORDER BY `order` ASC")->result();
        
		$query['modeofpayment']=$this->db->query("SELECT `listingmodeofpayment`.`listing`, `listingmodeofpayment`.`modeofpayment` ,`modeofpayment`.`name` AS `modeofpaymentname`
FROM `listingmodeofpayment`
LEFT OUTER JOIN `modeofpayment` ON `modeofpayment`.`id`=`listingmodeofpayment`.`modeofpayment`
WHERE `listingmodeofpayment`.`listing`='$id'")->result();
        
		$query['daysofoperation']=$this->db->query("SELECT `listingdaysofoperation`.`listing`, `listingdaysofoperation`.`daysofoperation` ,`daysofoperation`.`name` AS `daysofoperationname`
FROM `listingdaysofoperation`
LEFT OUTER JOIN `daysofoperation` ON `daysofoperation`.`id`=`listingdaysofoperation`.`daysofoperation`
WHERE `listingdaysofoperation`.`listing`='$id'")->result();
        
        $ratingquery=$this->db->query("SELECT COUNT(id) AS `totalratings`,ROUND(AVG(`rating`)) AS `rating` FROM `userlistingrating` WHERE `listing`='$id'")->row();
        $query['rating']=$ratingquery->rating;
        $query['totalratings']=$ratingquery->totalratings;
		return $query;
	}
    
	public function getlistingbycity($cityid)
	{
		$query=$this->db->query("SELECT `listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`video` , `listing`.`area`
FROM `listing`
WHERE `listing`.`city`='$cityid' AND `listing`.`status`=1")->result();
		return $query;
	}
    
	function getallinfooflisting($listingid)
	{
		$query="SELECT `id`, `name`, `user`, `lat`, `long`, `address`, `city`, `pincode`, `state`, `country`, `description`, `logo`, `contactno`, `email`, `website`, `facebook`, `twitter`, `googleplus`, `yearofestablishment`, `timeofoperation_start`, `timeofoperation_end`, `type`, `credits`, `isverified`, `video`, `deletestatus` , `listing`.`area`
        FROM `listing`
        WHERE `id`='$listingid'";
	   
		$query=$this->db->query($query)->row();
		return $query;
	}
    
	function getallinfooflistingbycategory($categoryid)
	{
		$query="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`video` ,`category`.`name` AS `categoryname`,`category`.`banner` AS `banner`, `listing`.`area`
FROM `listingcategory`
LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
LEFT OUTER JOIN `category` ON `category`.`id`=`listingcategory`.`category`
WHERE `listingcategory`.`category`='$listingid'
ORDER BY `listing`.`pointer` DESC
LIMIT 0 , 5";
	   
		$query=$this->db->query($query)->result();
		return $query;
	}
    
    public function getlistingbycategorydropdown($id)
	{
		$query="SELECT `listingcategory`.`listing`, `listingcategory`.`category`,`listing`.`name`,`listing`.`id` AS `listingid`, `listing`. `user`, `listing`.`lat`, `listing`.`long`, `listing`.`address`, `listing`.`city`, `listing`.`pincode`, `listing`.`state`, `listing`.`country`, `listing`.`description`, `listing`.`logo`, `listing`.`contactno`, `listing`.`email`, `listing`.`website`, `listing`.`facebook`, `listing`.`twitter`, `listing`.`googleplus`, `listing`.`yearofestablishment`, `listing`.`timeofoperation_start`, `listing`.`timeofoperation_end`, `listing`.`type`, `listing`.`credits`, `listing`.`isverified`, `listing`.`video` ,`category`.`name` AS `categoryname`,`category`.`banner` AS `banner`, `listing`.`area`
FROM `listingcategory`
LEFT OUTER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing`
LEFT OUTER JOIN `category` ON `category`.`id`=`listingcategory`.`category`
WHERE `listingcategory`.`category`='$id' ";
		$listing=$this->db->query($query)->result();
         if ($listing== NULL) {
                return "No Listing";
            }
        else
        return $listing;
	}
    
	public function createbycsv($file,$category)
	{
        foreach ($file as $row)
        {
//            echo $row['name'];
            $address=$row['address1']."".$row['address2']."".$row['address3'];
            
//            if($row['tel2']=="" && $row['mobile']=="")
//            {
//            $contact=$row['tel1'];
//            }
//            elseif($row['mobile']=="")
//            {
//            $contact=$row['tel1']."/".$row['tel2'];
//            }
//            else
//            {
//            $contact=$row['tel1']."/".$row['tel2']."/".$row['mobile'];
//            }
            
            if($row['tel1']=="" && $row['tel2']=="")
            {
                $contact="";
            }
            else if($row['tel1']=="")
            {
                $contact=$row['tel2'];
            }
            else if($row['tel2']=="")
            {
                $contact=$row['tel1'];
            }
            else
            {
                $contact=$row['tel1']."/".$row['tel2'];
            }
            
            
            $city=$row['city'];
            $cityquery=$this->db->query("SELECT * FROM `city` where `name`LIKE '$city'")->row();
            if(empty($cityquery))
            {
                $this->db->query("INSERT INTO `city`(`name`) VALUES ('$city')");
                $cityid=$this->db->insert_id();
            }
            else
            {
                $cityid=$cityquery->id;
            }
            $area=$row['area'];
            $areaquery=$this->db->query("SELECT * FROM `location` WHERE `cityid`='$cityid' AND `name` LIKE '$area'")->row();
            if(empty($areaquery))
            {
                $this->db->query("INSERT INTO `location`(`name`,`cityid`) VALUES ('$area','$cityid')");
                $areaid=$this->db->insert_id();
            }
            else
            {
                $areaid=$areaquery->id;
            }
            $data  = array(
                'name' => $row['name'],
                'user' => 1,
                'address' => $address,
                'city' => $cityid,
                'area' => $areaid,
                'pincode' => $row['pincode'],
//                'state' => 'Maharashtra',
//                'country' => 'India',
                'mobile' => $row['mobile'],
                'state' => $row['state'],
                'country' => $row['country'],
                'description' => $row['description'],
                'email' => $row['email'],
                'website' => $row['website'],
                'facebook' => $row['facebook'],
                'googleplus' => $row['googleplus'],
                'twitter' => $row['twitter'],
                'timeofoperation_start' => $row['timeofoperation_start'],
                'timeofoperation_end' => $row['timeofoperation_end'],
                'video' => $row['video'],
                'status' => 1,
                'pointer' => 0,
                'type' => 1,
                'isverified' => 1,
                'contactno' => $contact
            );

            $query=$this->db->insert( 'listing', $data );
            $listingid=$this->db->insert_id();
            foreach($category AS $key=>$value)
            {
               $this->listing_model->createcategorybylisting($value,$listingid);
            }
        }
			return  1;
	}
    
    function deletealllistings($id)
    {
        
        foreach($id as $idu)
        {
            $query=$this->db->query("UPDATE `listing` SET `deletestatus`=0 WHERE `id`='$idu'");
        }
        if($query){
            return 1;
        }else{
            return 0;
        }
    }
    
    function tejasdelete($id)
    {
//            $query=$this->db->query("UPDATE `listing` SET `deletestatus`=0 ");
        $query=$this->db->query("DELETE FROM `listing`");
        return 1;
        
    }
    
    public function getareadropdown($city) {
        $query = "SELECT * FROM `location` WHERE `cityid`='$city' ORDER BY `id` ASC";
        $area = $this->db->query($query)->result();
        return $area;
    }
    
    
    public function addrating($user,$listing,$rating)
	{
		$data  = array(
			'user' => $user,
			'listing' => $listing,
			'rating' => $rating
		);
        $selectquery=$this->db->query("SELECT * FROM `userlistingrating` WHERE `user`='$user' AND `listing`='$listing'")->row();
        if(empty($selectquery))
        {
            $query=$this->db->insert( 'userlistingrating', $data );
            return  1;
        }
        else
        {
            $presentid=$selectquery->id;
            $updatequery=$this->db->query("UPDATE `userlistingrating` SET `user`='$user',`listing`='$listing',`rating`='$rating',`timestamp`=NULL WHERE `id`='$presentid'");
            
		return  1;
//            $deletequery=$this->db->query("DELETE FROM `userlistingrating` WHERE `user`='$user' AND `listing`='$listing'");
//            $query=$this->db->insert( 'userlistingrating', $data );
        }
        
	}
    
    
        function getmonthbeforelistingnotifications()
        { 
        	$query=$this->db->query("SELECT `id`, `name`, `pointerstartdate`, `pointerenddate`, `pointer` ,NOW() AS `today`,DATE(NOW()+INTERVAL 30 DAY) AS `monthbefore`
FROM `listing` 
HAVING `pointerenddate`=`monthbefore`")->result();
		return $query;
        }
    
        function getfivedaysbeforelistingnotifications()
        { 
        	$query=$this->db->query("SELECT `id`, `name`, `pointerstartdate`, `pointerenddate`, `pointer` ,NOW() AS `today`,DATE(NOW()+INTERVAL 5 DAY) AS `fivedaysbefore`
FROM `listing` 
HAVING `pointerenddate`=`fivedaysbefore`")->result();
		return $query;
        }
        
	public function editlistingnotification($id,$pointerstartdate,$pointerenddate,$pointer)
	{
		$data = array(
			'pointerstartdate' => $pointerstartdate,
			'pointerenddate' => $pointerenddate,
			'pointer' => $pointer
		
		);
		$this->db->where( 'id', $id );
		$query=$this->db->update( 'listing', $data );
		
		return 1;
	}
    
        function getexpiredlistingnotification()
        { 
        	$query=$this->db->query("SELECT `id`, `name`, `pointerstartdate`, `pointerenddate`, `pointer`,NOW() AS `today` FROM `listing` HAVING `pointerenddate`<`today`  ORDER BY `pointerenddate`")->result();
		return $query;
        }
        function getupcomminglistingnotification()
        { 
        	$query=$this->db->query("SELECT `id`, `name`, `pointerstartdate`, `pointerenddate`, `pointer` ,NOW() AS `today`
FROM `listing` 
HAVING `pointerenddate`>`today` ORDER BY `id` DESC")->result();
		return $query;
        }
    
    
    public function getlistingbycategorydashboarddropdown($category) {
//        $query = "SELECT * FROM `location` WHERE `cityid`='$city' ORDER BY `id` ASC";
        $query = "SELECT `listingcategory`.`listing` AS `id`,`listing`.`name`,`location`.`name` AS `areaname`,`city`.`name` AS `cityname`, `listingcategory`.`category` FROM `listingcategory` INNER JOIN `listing` ON `listing`.`id`=`listingcategory`.`listing` INNER JOIN `city` ON `listing`.`city`=`city`.`id` INNER JOIN `location` ON `listing`.`area`=`location`.`id` WHERE `listingcategory`.`category`='$category' ORDER BY `listing`.`pointer` ";
        $listing = $this->db->query($query)->result();
        return $listing;
    }
    
    
        function movelisting($fromcategory,$tocategory)
        { 
//            $query="UPDATE `listingcategory` SET `category`='$tocategory' WHERE `category`='$fromcategory'";
//            echo $query;
            $query=$this->db->query("UPDATE `listingcategory` SET `category`='$tocategory' WHERE `category`='$fromcategory'");
            if($query)
                return 1;
            else
                return 0;
        }
    
        function deletelistingbycategory($fromcategory)
        { 
            $query=$this->db->query("DELETE FROM `listingcategory` WHERE `category`='$fromcategory'");
            if($query)
                return 1;
            else
                return 0;
        }
    
        function getlistingdetailsforlatlong()
        { 
            $query=$this->db->query("SELECT `listing`.`id`,`listing`. `name`,`listing`. `user`,`listing`. `address`,`listing`. `city`,`listing`. `pincode`,`listing`. `state`,`listing`. `country`,`listing`. `description`,`listing`. `logo`,`listing`. `contactno`,`listing`. `mobile`,`listing`. `email`,`listing`. `website`,`listing`. `facebook`,`listing`. `twitter`,`listing`. `googleplus`,`listing`. `yearofestablishment`,`listing`. `timeofoperation_start`,`listing`. `timeofoperation_end`,`listing`. `type`,`listing`. `credits`,`listing`. `isverified`,`listing`. `video`,`listing`. `deletestatus`,`listing`. `pointer`,`listing`. `area`,`listing`. `status`,`listing`. `pointerstartdate`,`listing`. `pointerenddate` ,`location`.`name` AS `areaname`,`city`.`name` AS `cityname`
FROM `listing` 
LEFT OUTER JOIN `location` ON `location`.`id`=`listing`.`area`
LEFT OUTER JOIN `city` ON `city`.`id`=`listing`.`city`
WHERE ISNULL(`listing`. `lat`) OR ISNULL(`listing`.`long`) LIMIT 0,100")->result(); 
//            print_r($query);
            if($query)
                return $query;
            else
                return 0;
        }
    
    
    
    function exportlistingcsv()
	{
		$this->load->dbutil();
		$query=$this->db->query("SELECT `listing`.`id`,`listing`. `name` AS `Name`,`listing`. `lat` AS `Latitude`,`listing`. `long` AS `Longitude`,`listing`. `address` AS `Address`,`city`.`name` AS `City`,`listing`. `pincode` AS `Pincode`,`listing`. `state` AS `State`,`listing`. `country` AS `Country`,`listing`. `description` AS `Description`,`listing`. `logo` AS `Logo`,`listing`. `contactno` AS `Contact Number`,`listing`. `mobile` AS `Mobile Number`,`listing`. `email` AS `Email`,`listing`. `yearofestablishment` AS `Year Of Establishment`,`listing`. `timeofoperation_start`,`listing`. `timeofoperation_end`,`listing`. `type`,`listing`. `video`,`listing`. `pointer`,`location`.`name` AS `Area`,`listing`. `pointerstartdate`,`listing`. `pointerenddate` , GROUP_CONCAT(`category`.`name`) AS `category`
FROM `listing` 
LEFT OUTER JOIN `location` ON `listing`.`area`=`location`.`id`
LEFT OUTER JOIN `city` ON `location`.`cityid`=`city`.`id`
INNER JOIN `listingcategory` ON `listingcategory`.`listing`=`listing`.`id`
INNER JOIN `category` ON `listingcategory`.`category`=`category`.`id`");

       $content= $this->dbutil->csv_from_result($query);
        //$data = 'Some file data';
        $timestamp=new DateTime();
        $timestamp=$timestamp->format('Y-m-d_H.i.s');
        if ( ! write_file("./csvgenerated/listing_$timestamp.csv", $content))
        {
             echo "Unable to write the file";
        }
        else
        {
            redirect(base_url("csvgenerated/listing_$timestamp.csv"), "refresh");
             echo "File written!";
        }
	}
}
?>