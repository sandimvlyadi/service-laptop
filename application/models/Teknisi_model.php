<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi_model extends CI_Model {

	function _get($data = array())
    {
    	$q = "SELECT a.* FROM `pengguna` a ";

        if ($data['search']['value'] && !isset($data['all'])) {
        	$s = $this->db->escape_str($data['search']['value']);
            $q .= "WHERE (a.`username` LIKE '%". $s ."%' OR a.`display_name` LIKE '%". $s ."%' OR a.`email` LIKE '%". $s ."%' OR a.`kontak` LIKE '%". $s ."%' OR a.`alamat` LIKE '%". $s ."%') AND a.`deleted_at` IS NULL AND a.`id_level_pengguna` = 2 ";
        } else{
        	$q .= "WHERE a.`deleted_at` IS NULL AND a.`id_level_pengguna` = 2 ";
        }

        if (isset($data['order'])) {
        	$dir = $this->db->escape_str($data['order'][0]['dir']);
        	$col = $this->db->escape_str($data['columns'][$data['order'][0]['column']]['data']);
        	if ($data['order'][0]['column'] != 0) {
                if ($col == 'nama_level') {
                    $q .= "ORDER BY b.`". $col ."` ". $dir ." ";
                } else{
                    $q .= "ORDER BY a.`". $col ."` ". $dir ." ";
                }
        	} else{
        		$q .= "ORDER BY a.`id` ". $dir ." ";
        	}
        } else{
        	$q .= "ORDER BY a.`id` DESC ";
        }

        return $q;
    }

    function _list($data = array())
    {
        $q = $this->_get($data);
        $q .= "LIMIT ". $this->db->escape_str($data['start']) .", ". $this->db->escape_str($data['length']);
        $r = $this->db->query($q, false)->result_array();

        return $r;
    }

    function _filtered($data = array())
    {
        $q = $this->_get($data);
        $r = $this->db->query($q, false)->result_array();

        return count($r);
    }

    function _all($data = array())
    {
        $data['all'] = true;
        $q = $this->_get($data);
        $r = $this->db->query($q)->result_array();

        return count($r);
    }

	function datatable($data = array())
	{
		$result = array(
            'draw'              => 1,
            'recordsTotal'      => 0,
            'recordsFiltered'   => 0,
            'data'              => array(),
            'result'            => false,
            'msg'               => ''
        );

        $list = $this->_list($data);
        if (count($list) > 0) {
            $result = array(
                'draw'              => $data['draw'],
                'recordsTotal'      => $this->_all($data),
                'recordsFiltered'   => $this->_filtered($data),
                'data'              => $list,
                'result'            => true,
                'msg'               => 'Loaded.',
                'start'             => (int) $data['start'] + 1
            );
        } else{
            $result['msg'] = 'No data left.';
        }

        return $result;
	}

    function edit($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => 'Data teknisi tidak ditemukan.'
        );

        $q =    "SELECT
                    a.*
                FROM
                    `pengguna` a
                WHERE
                    a.`id` = '". $this->db->escape_str($id) ."'
                ;";
        $r = $this->db->query($q)->result_array();
        if (count($r) > 0) {
            $result['result'] = true;
            $result['data'] = $r[0];
        }

        return $result;
    }

	function save($data = array())
	{
		$result = array(
			'result'    => false,
			'msg'		=> ''
		);

		$u = $data['userData'];
		$d = $data['postData'];
		$id = $d['id'];
		parse_str($d['form'], $f);

		$q = '';
		if ($id == 0) {
			$q =    "INSERT INTO
                        `pengguna`
                        (
                            `created_at`,
                            `username`,
                            `password`,
                            `display_name`,
                            `email`,
                            `kontak`,
                            `alamat`,
                            `id_level_pengguna`
                        )
                    VALUES
                        (
                            NOW(),
                            '". $this->db->escape_str($f['username']) ."',
                            '". md5('12345678') ."',
                            '". $this->db->escape_str($f['display_name']) ."',
                            '". $this->db->escape_str($f['email']) ."',
                            '". $this->db->escape_str($f['kontak']) ."',
                            '". $this->db->escape_str($f['alamat']) ."',
                            2
                        )
                    ;";
		} else{
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
		}

		if ($this->db->simple_query($q)) {
			$result['result'] = true;
			$result['msg'] = 'Data berhasil disimpan.';
		} else{
			$result['msg'] = 'Terjadi kesalahan saat menyimpan data.';
		}

		return $result;
	}

	function delete($data = array())
	{
		$result = array(
			'result'    => false,
			'msg'		=> ''
		);

		$u = $data['userData'];
		$d = $data['postData'];
		$id = $d['id'];
		$q = "UPDATE `pengguna` SET `deleted_at` = NOW() WHERE `id` = '". $this->db->escape_str($id) ."';";
		if ($this->db->simple_query($q)) {
			$result['result'] = true;
			$result['msg'] = 'Data berhasil dihapus.';
		} else{
			$result['msg'] = 'Terjadi kesalahan saat menghapus data.';
		}

		return $result;
	}

    function select($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "";
        if ($id == 0) {
            $q = "SELECT * FROM `pengguna` WHERE `id_level_pengguna` = 2 AND `deleted_at` IS NULL;";
        } else{
            $q = "SELECT * FROM `pengguna` WHERE `id_level_pengguna` = 2 AND `id` = '". $this->db->escape_str($id) ."' AND `deleted_at` IS NULL;";
        }
        $r = $this->db->query($q)->result_array();
        if (count($r) > 0) {
            $result['result'] = true;
            $result['data'] = $r;

            if (count($r) == 1 && $id != 0) {
                $result['data'] = $r[0];
            }
        }

        return $result;
    }

}