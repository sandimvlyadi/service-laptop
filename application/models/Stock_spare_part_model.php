<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_spare_part_model extends CI_Model {

	function _get($data = array())
    {
    	$q = "SELECT a.*, IFNULL(SUM(b.`jml_stock`), 0) AS `total` FROM `spare_part` a LEFT JOIN `stock` b ON a.`id` = b.`id_spare_part` AND b.`deleted_at` IS NULL ";

        if ($data['search']['value'] && !isset($data['all'])) {
        	$s = $this->db->escape_str($data['search']['value']);
            $q .= "WHERE (a.`nama_spare_part` LIKE '%". $s ."%') AND a.`deleted_at` IS NULL ";
        } else{
        	$q .= "WHERE a.`deleted_at` IS NULL ";
        }

        $q .= "GROUP BY a.`id` ";

        if (isset($data['order'])) {
        	$dir = $this->db->escape_str($data['order'][0]['dir']);
        	$col = $this->db->escape_str($data['columns'][$data['order'][0]['column']]['data']);
        	if ($data['order'][0]['column'] != 0) {
                if ($col == 'total') {
                    $q .= "ORDER BY `". $col ."` ". $dir ." ";
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

    function _get_detail($data = array())
    {
        $q = "SELECT a.*, b.`nama_spare_part`, c.`nama_kondisi` FROM `stock` a LEFT JOIN `spare_part` b ON a.`id_spare_part` = b.`id` LEFT JOIN `kondisi` c ON a.`id_kondisi` = c.`id` ";

        if ($data['search']['value'] && !isset($data['all'])) {
            $s = $this->db->escape_str($data['search']['value']);
            $q .= "WHERE (b.`nama_spare_part` LIKE '%". $s ."%' OR c.`nama_kondisi` LIKE '%". $s ."%' OR a.`merk_serie` LIKE '%". $s ."%' OR a.`spesifikasi` LIKE '%". $s ."%' OR a.`harga_jual` LIKE '%". $s ."%' OR a.`harga_beli` LIKE '%". $s ."%') AND a.`deleted_at` IS NULL AND a.`id_spare_part` = '". $this->db->escape_str($data['id_spare_part']) ."' ";
        } else{
            $q .= "WHERE a.`deleted_at` IS NULL AND a.`id_spare_part` = '". $this->db->escape_str($data['id_spare_part']) ."' ";
        }

        $q .= "GROUP BY a.`id` ";

        if (isset($data['order'])) {
            $dir = $this->db->escape_str($data['order'][0]['dir']);
            $col = $this->db->escape_str($data['columns'][$data['order'][0]['column']]['data']);
            if ($data['order'][0]['column'] != 0) {
                if ($col == 'nama_spare_part') {
                    $q .= "ORDER BY b.`". $col ."` ". $dir ." ";
                } elseif ($col == 'nama_kondisi') {
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

    function _list_detail($data = array())
    {
        $q = $this->_get_detail($data);
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

    function _filtered_detail($data = array())
    {
        $q = $this->_get_detail($data);
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

    function _all_detail($data = array())
    {
        $data['all'] = true;
        $q = $this->_get_detail($data);
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

        if ($data['id_spare_part'] == 0) {
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
        } else{
            $list = $this->_list_detail($data);
            if (count($list) > 0) {
                for ($i=0; $i < count($list); $i++) { 
                    $list[$i]['harga_beli'] = 'Rp. ' . number_format($list[$i]['harga_beli'], 0, ',', '.');
                    $list[$i]['harga_jual'] = 'Rp. ' . number_format($list[$i]['harga_jual'], 0, ',', '.');
                }

                $result = array(
                    'draw'              => $data['draw'],
                    'recordsTotal'      => $this->_all_detail($data),
                    'recordsFiltered'   => $this->_filtered_detail($data),
                    'data'              => $list,
                    'result'            => true,
                    'msg'               => 'Loaded.',
                    'start'             => (int) $data['start'] + 1
                );
            } else{
                $result['msg'] = 'No data left.';
            }
        }

        return $result;
	}

    function edit($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => 'Data stock tidak ditemukan.'
        );

        $q =    "SELECT
                    a.*,
                    b.`nama_kondisi`,
                    c.`status`,
                    d.`nama_spare_part`,
                    e.`nama_satuan`,
                    f.`nama_type`
                FROM
                    `stock` a
                LEFT JOIN
                    `kondisi` b
                        ON
                    a.`id_kondisi` = b.`id`
                LEFT JOIN
                    `status` c
                        ON
                    a.`id_status` = c.`id`
                LEFT JOIN
                    `spare_part` d
                        ON
                    a.`id_spare_part` = d.`id`
                LEFT JOIN
                    `satuan` e
                        ON
                    a.`id_satuan` = e.`id`
                LEFT JOIN
                    `type_monitor` f
                        ON
                    a.`id_type_monitor` = f.`id`
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
                        `stock`
                        (
                            `created_at`,
                            `id_spare_part`,
                            `merk_serie`,
                            `spesifikasi`,
                            `id_kondisi`,
                            `harga_beli`,
                            `harga_jual`,
                            `kapasitas`,
                            `id_satuan`,
                            `jml_stock`,
                            `id_type_monitor`,
                            `batre_for`,
                            `adaptor_for`,
                            `chassing_for`,
                            `keyboard_for`,
                            `id_status`
                        )
                    VALUES
                        (
                            NOW(),
                            '". $this->db->escape_str($f['id_spare_part']) ."',
                            '". $this->db->escape_str($f['merk_serie']) ."',
                            '". $this->db->escape_str($f['spesifikasi']) ."',
                            '". $this->db->escape_str($f['id_kondisi']) ."',
                            '". $this->db->escape_str($f['harga_beli']) ."',
                            '". $this->db->escape_str($f['harga_jual']) ."',
                            '". $this->db->escape_str($f['kapasitas']) ."',
                            '". $this->db->escape_str($f['id_satuan']) ."',
                            '". $this->db->escape_str($f['jml_stock']) ."',
                            '". $this->db->escape_str($f['id_type_monitor']) ."',
                            '". $this->db->escape_str($f['batre_for']) ."',
                            '". $this->db->escape_str($f['adaptor_for']) ."',
                            '". $this->db->escape_str($f['chassing_for']) ."',
                            '". $this->db->escape_str($f['keyboard_for']) ."',
                            '". $this->db->escape_str($f['id_status']) ."'
                        )
                    ;";
		} else{
            $q =    "UPDATE
                        `stock`
                    SET
                        `modified_at` = NOW(),
                        `id_spare_part` = '". $this->db->escape_str($f['id_spare_part']) ."',
                        `merk_serie` = '". $this->db->escape_str($f['merk_serie']) ."',
                        `spesifikasi` = '". $this->db->escape_str($f['spesifikasi']) ."',
                        `id_kondisi` = '". $this->db->escape_str($f['id_kondisi']) ."',
                        `harga_beli` = '". $this->db->escape_str($f['harga_beli']) ."',
                        `harga_jual` = '". $this->db->escape_str($f['harga_jual']) ."',
                        `kapasitas` = '". $this->db->escape_str($f['kapasitas']) ."',
                        `id_satuan` = '". $this->db->escape_str($f['id_satuan']) ."',
                        `jml_stock` = '". $this->db->escape_str($f['jml_stock']) ."',
                        `id_type_monitor` = '". $this->db->escape_str($f['id_type_monitor']) ."',
                        `batre_for` = '". $this->db->escape_str($f['batre_for']) ."',
                        `adaptor_for` = '". $this->db->escape_str($f['adaptor_for']) ."',
                        `chassing_for` = '". $this->db->escape_str($f['chassing_for']) ."',
                        `keyboard_for` = '". $this->db->escape_str($f['keyboard_for']) ."',
                        `id_status` = '". $this->db->escape_str($f['id_status']) ."'
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
		$q = "UPDATE `stock` SET `deleted_at` = NOW() WHERE `id` = '". $this->db->escape_str($id) ."';";
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
            $q = "SELECT * FROM `stock` WHERE `deleted_at` IS NULL;";
        } else{
            $q = "SELECT * FROM `stock` WHERE `id` = '". $this->db->escape_str($id) ."' AND `deleted_at` IS NULL;";
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