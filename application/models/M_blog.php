<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_blog extends CI_Model
{
    private $table = 'berita'; //This is table name
    private $pk = 'id_berita'; //this is primary key

    public function __construct()
    {
        parent::__construct();
        //Codeigniter : Write Less Do More
    }
    public function GetAll($id = null)
    {
        $this->db->order_by($this->pk, 'desc');
        if ($id != null) {
            $this->db->where('id_berita', $id);
        }
        return $this->db->get($this->table); //u can use library cidbget
    }
    public function delete($id)
    {
        $data = $this->M_blog->GetAll($id)->row();
        $this->db->where($this->pk, $id);
        $this->db->delete($this->table);
    }
    public function save($data)
    {
        if (!empty($_FILES['image']['name'])) {
            $upload = $this->_do_uploadimg();
            $data['image'] = $upload;
        }
        return $this->db->insert($this->table, $data);
    }

    private function _do_uploadimg()
    {
        unset($config);
        $config['upload_path']    = './assets/images/upload/';
        $config['allowed_types']  = '.gif|jpg|png|jpeg|jfif';
        $config['max_size']       = 2048;
        $config['file_name']      = 'Foto User' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('image')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('blog/tambah', 'refresh');
        }
        return $this->upload->data('file_name');
    }
    function cek_nama($code, $id = null)
    {
        $this->db->from('berita');
        $this->db->where('id_berita', $code);
        if ($id != null) {
            $this->db->where('id_berita !=', $id);
        }
        $query = $this->db->get();
        return $query;
    }
    public function update($id, $data)
    {
        if (!empty($_FILES['image']['name'])) {
            $upload = $this->_do_uploadimg_update();
            $data['image'] = $upload;
            $file_img = './assets/images/upload/' . $this->input->post('old_img');
            unlink($file_img);
        }
        $this->db->where($this->pk, $id);
        return $this->db->update($this->table, $data);
    }

    private function _do_uploadimg_update()
    {
        unset($config);
        $config['upload_path']    = './assets/images/upload/';
        $config['allowed_types']  = '.gif|jpg|png|jpeg|jfif';
        $config['max_size']       = 2048;
        $config['file_name']      = 'Foto User' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('image')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('blog/edit/' . $this->input->post('id_berita'), 'refresh');
        }
        return $this->upload->data('file_name');
    }

    public function cariDataBlog()
    {
        $keyword = $this->input->post('keyword', true);
        $this->db->or_like('judul', $keyword);
        return $this->db->get('berita')->result_array();
    }
    public function GetById($id)
    {
        $this->db->where($this->pk, $id); //u can use cidbwhere
        return $this->db->get($this->table)->row_array();
    }

    public function get_latest_blogs($id = null)
    { {
            $this->db->order_by($this->pk, 'desc');
            if ($id != null) {
                $this->db->where('id_berita', $id);
            }
            return $this->db->get($this->table, 3); //u can use library cidbget
        }
    }
}
