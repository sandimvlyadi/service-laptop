<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {

	function data($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "SELECT * FROM `pengguna` WHERE `id` = '". $id ."' AND `deleted_at` IS NULL;";
        $r = $this->db->query($q, false)->result_array();
        if (count($r) > 0) {
            $result['result'] = true;
            $result['data'] = $r[0];
        }

        return $result;
    }

    function save_data($data = array())
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $u = $data['userData'];
        $d = $data['postData'];
        $id = $u['session']['id'];
        parse_str($d['form'], $f);

        $q =    "UPDATE
                    `pengguna`
                SET
                    `modified_at` = NOW(),
                    `username` = '". $this->db->escape_str($f['username']) ."',
                    `display_name` = '". $this->db->escape_str($f['display_name']) ."',
                    `email` = '". $this->db->escape_str($f['email']) ."',
                    `kontak` = '". $this->db->escape_str($f['kontak']) ."',
                    `alamat` = '". $this->db->escape_str($f['alamat']) ."'
                WHERE
                    `id` = '". $this->db->escape_str($id) ."'
                ;";

        if ($this->db->simple_query($q)) {
            $result['result'] = true;
            $result['msg'] = 'Data berhasil disimpan.';
        } else{
            $result['msg'] = 'Terjadi kesalahan saat menyimpan data.';
        }

        return $result;
    }

    function save_password($data = array())
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $u = $data['userData'];
        $d = $data['postData'];
        $id = $u['session']['id'];
        parse_str($d['form'], $f);

        $q =    "SELECT 
                    * 
                FROM 
                    `pengguna` 
                WHERE 
                    `id` = '". $this->db->escape_str($id) ."' 
                        AND 
                    `password` = '". $this->db->escape_str(md5($f['password_lama'])) ."'
                ;";
        $r = $this->db->query($q, false)->result_array();
        if (count($r) < 1) {
            $result['msg'] = 'Password lama yang anda masukan salah.';
            return $result;
        }

        $q =    "UPDATE
                    `pengguna`
                SET
                    `modified_at` = NOW(),
                    `password` = '". $this->db->escape_str(md5($f['password_baru'])) ."'
                WHERE
                    `id` = '". $this->db->escape_str($id) ."'
                ;";

        if ($this->db->simple_query($q)) {
            $result['result'] = true;
            $result['msg'] = 'Data berhasil disimpan.';
        } else{
            $result['msg'] = 'Terjadi kesalahan saat menyimpan data.';
        }

        return $result;
    }

}