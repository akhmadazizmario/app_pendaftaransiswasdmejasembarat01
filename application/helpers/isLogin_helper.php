<?php

	function IsAdmin(){

	    $ci =& get_instance();

	    if($ci->session->userdata('IsAdmin')<>1){

       		$ci->session->set_flashdata($ci->config->item('flash_name'), $ci->config->item('flash_login'));

			redirect('auth','refresh');

	    }

	}

	function IsJurnalis(){

	    $ci =& get_instance();

	    if($ci->session->userdata('IsJurnalis')<>1){

       		$ci->session->set_flashdata($ci->config->item('flash_name'), $ci->config->item('flash_login'));

			redirect('jurnalis','refresh');

	    }

	}

	function IsPimred(){

	    $ci =& get_instance();

	    if($ci->session->userdata('IsPimred')<>1){

       		$ci->session->set_flashdata($ci->config->item('flash_name'), $ci->config->item('flash_login'));

			redirect('auth','refresh');

	    }

	}

	function Paging($per_page,$GetCount){
		$ci =& get_instance();

		$ci->load->library('pagination');

		$config['base_url'] 		= site_url($ci->uri->segment(1).'/index');
		$config['total_rows'] 		= $GetCount;
		$config['per_page'] 		= $per_page;
		$config['uri_segment'] 		= 3;
		$config['num_links'] 		= 3;

		//Membuat Style
		$config['first_link']      	= 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';

        $ci->pagination->initialize($config);

        return $ci->pagination->create_links();
	}

	function UploadFoto($file,$dst){

		$date = date("Y-m-m_H-i-s");

		//Penjabaran File
		$filename 	= $file['name'];
		$filetype 	= $file['type'];
		$filetmp 	= $file['tmp_name'];

		$fileupload = $dst.$filename;
		//Upload ukuran sebenarnya
		move_uploaded_file($filetmp, $fileupload);

		//Identifikasi Gambar
		if ($filetype == 'image/jpeg' || $filetype == 'image/jpg') {
			$src 	= imagecreatefromjpeg($fileupload);
		}elseif ($filetype == 'image/png') {
			$src 	= imagecreatefrompng($fileupload);
		}

			$wsrc = imageSX($src);
			$hsrc = imageSY($src);

			//Set Ukuran Gambar
			$wdst = 360;
			$hdst = ($wdst / $wsrc) * $hsrc;

			//Proses Perubahan Ukuran
			$filecreate = imagecreatetruecolor($wdst, $hdst);
			imagecopyresampled($filecreate, $src, 0, 0, 0, 0, $wdst, $hdst, $wsrc, $hsrc);

			//Nama Acak
			$x 			= explode(".", $filename);
			$name 		= $x[0];
			$extension 	= $x[1];
			$filename 	= $date.'.'.$extension;

		//Reupload
		if ($filetype == 'image/jpeg' || $filetype == 'image/jpg') {
			imagejpeg($filecreate,$dst.$filename);
		}elseif ($filetype == 'image/png') {
			imagepng($filecreate,$dst.$filename);
		}

			//Hapus Foto Lama
			unlink($fileupload);

		return $filename;
	}
