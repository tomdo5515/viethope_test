<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Banners extends CI_Model
{
	function getBanners($position_id=NULL, $page_id=NULL)
	{
		$banners = array();
		// $this->db->cache_on();
		$this->db->select('`banner_id`, `position_id`, `name`, `click_url`, `content`, `sort`');
		$this->db->where('status', 1);
		$this->db->order_by("sort","desc");

		if(isset($position_id))
			$this->db->where('position_id', $position_id);

		$this->db->from('banners');
		
		$query = $this->db->get();

		if($query -> num_rows() > 0)
		{
			$temp = $query->result_array();

			foreach ($temp as $key => $val) {

		      	$parent = array();
		      	foreach ($temp as $pkey => $pvalue) {
		          	if($pvalue['position_id'] === $val['position_id'])
		          	{
		              	$parent[] = array(
		              		'content' => $pvalue['content'],
		              		'click_url' => $pvalue['click_url'],
		              		'sort' => $pvalue['sort']
		              		);
		          	}
	      		}
	      		$banners[$val['position_id']] = $parent;
	      	}
		}
		// var_dump($banners); die();
		// $this->db->cache_off();
		return $banners;
	}
}
