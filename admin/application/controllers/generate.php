<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Generate extends CI_Controller
{
    
    function Generate()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    function tables()
    {
        $this->load->library('cezpdf');

        $db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
        $db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
        $db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');

        $col_names = array(
            'name' => 'Name',
            'phone' => 'Phone Number',
            'email' => 'E-mail Address'
        );

        $this->cezpdf->ezTable($db_data, $col_names, 'Contact List', array('width'=>550));
        $this->cezpdf->ezStream();
    }

	function hello_world()
	{
		$this->load->library('cezpdf');

		$this->cezpdf->ezText('Hello World', 12, array('justification' => 'center'));
		$this->cezpdf->ezSetDy(-10);

		$content = 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog.
					Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz, vex nymphs.';

		$this->cezpdf->ezText($content, 10);

		$this->cezpdf->ezStream();
	}
	
    function createuserpdf()
    {

                $this->load->library('cezpdf');

        $this->cezpdf->ezText('PDF REPORT OF USER TABLE', 12, array('justification' => 'center'));
        $this->cezpdf->ezSetDy(-10);
                $i=0;
                $content="";

                $fname="";
                $id=$this->input->get('id');
                $query = $this->db->query("SELECT * FROM `user` WHERE `id`='$id'");
                $num = $query->num_fields();
                $farr=array();
                $allvalue=$query->result();
                foreach($allvalue as $value)
                {
                    $pushvalue=((array) $value);
                    array_push($farr,$pushvalue);
                    $col_names = array(
                    'id' => 'ID',
                    'firstname' => 'First Name',
                    'lastname' => 'Last Name'
                        );
                    $content = "\nHiii Sohan The Talented Guy in Wohlig!!! ";

                    $this->cezpdf->ezText($content, 10);
                    $this->cezpdf->ezTable($farr, $col_names, 'User List', array('width'=>550));

                    $this->cezpdf->ezText($content, 10);
                    $this->cezpdf->ezStream();
                }
	}
}
	?>