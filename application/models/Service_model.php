<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model {

	function _get($data = array())
    {
    	$q = "SELECT a.*, b.`status`, c.`kode_client`, c.`nama_client`, c.`kontak` FROM `service` a LEFT JOIN `status` b ON a.`id_status` = b.`id` LEFT JOIN `client` c ON a.`id_client` = c.`id` ";

        if ($data['search']['value'] && !isset($data['all'])) {
        	$s = $this->db->escape_str($data['search']['value']);
            $q .= "WHERE (b.`status` LIKE '%". $s ."%' OR c.`kode_client` LIKE '%". $s ."%' OR c.`nama_client` LIKE '%". $s ."%' OR c.`kontak` LIKE '%". $s ."%' OR a.`no_reg` LIKE '%". $s ."%' OR a.`merk_serie` LIKE '%". $s ."%' OR a.`kelengkapan` LIKE '%". $s ."%' OR a.`keluhan` LIKE '%". $s ."%' OR a.`keterangan` LIKE '%". $s ."%') AND a.`deleted_at` IS NULL ";
        } else{
        	$q .= "WHERE a.`deleted_at` IS NULL ";
        }

        if (isset($data['order'])) {
        	$dir = $this->db->escape_str($data['order'][0]['dir']);
        	$col = $this->db->escape_str($data['columns'][$data['order'][0]['column']]['data']);
        	if ($data['order'][0]['column'] != 0) {
                if ($col == 'status') {
                    $q .= "ORDER BY b.`". $col ."` ". $dir ." ";
                } elseif ($col == 'kode_client' || $col == 'nama_client' || $col == 'kontak') {
                    $q .= "ORDER BY c.`". $col ."` ". $dir ." ";
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
            'msg'       => 'Data client tidak ditemukan.'
        );

        $q =    "SELECT
                    a.*,
                    b.`status`,
                    c.`kode_client`,
                    c.`nama_client`,
                    c.`kontak`
                FROM
                    `service` a
                LEFT JOIN
                    `status` b
                        ON
                    a.`id_status` = b.`id`
                LEFT JOIN
                    `client` c
                        ON
                    a.`id_client` = c.`id`
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
                        `service`
                        (
                            `created_at`,
                            `id_client`,
                            `no_reg`,
                            `merk_serie`,
                            `kelengkapan`,
                            `keluhan`,
                            `id_status`,
                            `keterangan`
                        )
                    VALUES
                        (
                            NOW(),
                            '". $this->db->escape_str($f['id_client']) ."',
                            '". $this->db->escape_str($f['no_reg']) ."',
                            '". $this->db->escape_str($f['merk_serie']) ."',
                            '". $this->db->escape_str($f['kelengkapan']) ."',
                            '". $this->db->escape_str($f['keluhan']) ."',
                            '". $this->db->escape_str($f['id_status']) ."',
                            '". $this->db->escape_str($f['keterangan']) ."'
                        )
                    ;";
		} else{
            $q =    "UPDATE
                        `service`
                    SET
                        `modified_at` = NOW(),
                        `id_client` = '". $this->db->escape_str($f['id_client']) ."',
                        `no_reg` = '". $this->db->escape_str($f['no_reg']) ."',
                        `merk_serie` = '". $this->db->escape_str($f['merk_serie']) ."',
                        `kelengkapan` = '". $this->db->escape_str($f['kelengkapan']) ."',
                        `keluhan` = '". $this->db->escape_str($f['keluhan']) ."',
                        `id_status` = '". $this->db->escape_str($f['id_status']) ."',
                        `keterangan` = '". $this->db->escape_str($f['keterangan']) ."'
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
		$q = "UPDATE `service` SET `deleted_at` = NOW() WHERE `id` = '". $this->db->escape_str($id) ."';";
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
            $q = "SELECT * FROM `service` WHERE `deleted_at` IS NULL;";
        } else{
            $q = "SELECT * FROM `service` WHERE `id` = '". $this->db->escape_str($id) ."' AND `deleted_at` IS NULL;";
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

    function no_registrasi()
    {
        $result = array(
            'result'    => true,
            'msg'       => ''
        );

        $period = date('Y-m');
        $q = "SELECT * FROM `service` WHERE `created_at` LIKE '". $period ."%';";
        $r = $this->db->query($q, false)->result_array();
        $n = count($r) + 1;
        $kode = 'S' . date('Ymd') . str_pad($n, 3, '0', STR_PAD_LEFT);
        $result['kode'] = $kode;

        return $result;
    }

}