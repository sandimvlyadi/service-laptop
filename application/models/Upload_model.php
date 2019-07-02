<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_model extends CI_Model {

	function display_picture($data = array())
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $config['upload_path']      = './uploads/img/';
        $config['allowed_types']    = 'jpg|jpeg|png|gif';
        $config['max_size']         = 2048;
        $config['encrypt_name']     = TRUE;
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('file')){
            $result['result'] = false;
            $result['msg']    = $this->upload->display_errors();
        } else{
            $result['result'] = true;
            $result['data']   = $this->upload->data();
            $result['msg']    = 'File berhasil diunggah.';

            $session = $data['userData']['session'];
            $q =    "UPDATE 
                        `pengguna` 
                    SET 
                        `display_picture` = '". $result['data']['file_name'] ."' 
                    WHERE 
                        `id` = '". $this->db->escape_str($session['id']) ."'
                    ;";
            if (!$this->db->simple_query($q)) {
                $result['result']   = false;
                $result['msg']      = 'Terjadi kesalahan saat menyimpan data.';
            }
        }

        return $result;
    }

}