<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/***************************************************************************
 *                                 page.php
 *                            -------------------
 *   begin                : Thursday, Jun 15, 2006
 *   copyright            : (C) 2006 Pablo Alejo (toe)
 *   email                : toe@hotsaucestudio.com
 *
 *   $Id: page.php,v 1.00.0.01 2007/14/06 19:23:07 toe Exp $
 *
 *
 ***************************************************************************/
 /***************************************************************************
 *
 *   page.php will be the page object that manages pages in the cms.
 *
 ***************************************************************************/
class Page extends Model 
{
	function Page()
	{
		parent::Model();
	} // end function Page()
	
	
	/**
	 * Selects the page information.
	 * @param string $id is the page id.
	 * @return array $aryPage is the page data.
	 */
	function get($id)
	{
		$data = array();
		
		$qa = $this->db->get_where('page', array('id' => $id));

		$i = 0;
		foreach($qa->result() as $qa_row)
        {
            // Dynamically returns only NOT NULL fields
            foreach($qa_row as $field => $value):
                $data[$i]->$field = (!$value || is_null($value)) ? FALSE : $value;
            endforeach;
            $i++;
        }
		return $data;
	} // function getPage($id)

	/**
	 * Selects all of the pages.
	 * @param string $offset is the record offset.
	 * @param string $offset is the limit of records to return.
	 * @return array $aryPage is the page data.
	 */
	function getAll()
	{
		$data = array();
		$i = 0;
		$this->db->where("pid", "0");
		$this->db->orderby("sort", "asc");
		$qa = $this->db->get('page');
		
		foreach ($qa->result() as $qa_row)
	   	{
			// Dynamically returns only NOT NULL fields
            foreach($qa_row as $field => $value):
                $data[$i]->$field = (!$value || is_null($value)) ? FALSE : $value;
            endforeach;
			
			$aryChildren = array();
			$aryChildren = $this->getChildren($qa_row->id);
			$data = array_merge($data, $aryChildren);
		  	$i = count($data);
	   	}
		
		return $data;
	} // end function getAllPages($offset, $limit)
	
	/**
	 * Adds a Page.
	 * @param array $data is the array of fields. 
	 * @return void
	 */
	function insert($data)
	{
		$this->db->insert("page", $data);
	} // end function addPage($data)

	/**
	 * Delete a Page.
	 * @param array $data is the array of fields. 
	 * @return void
	 */
	function delete($data)
	{
		$this->db->delete('page', $data);
	} // end function deletePage($data)

	/**
	 * Updates a Page.
	 * @param string $id is the page id. 
	 * @param array $data is the array of fields. 
	 * @return void
	 */
	function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update("page", $data); 
	} // end function updatePage($id, $data)
	
	function getAllSections()
	{
		$this->db->select('id, name');
		$this->db->orderby("name", "asc"); 
		$qa = $this->db->get_where('page', array('type' => 'section'));	
		$i = 1;
		$arySection[0]->id = '0';
		$arySection[0]->name = 'No Parent';
		
		foreach ($qa->result() as $qa_row)
	   	{
		  $arySection[$i]->id = $qa_row->id;
		  $arySection[$i]->name = $qa_row->name;
		  $i++;
	   	}
		
		return $arySection;
	}
	
	function getChildren($id)
	{
		$data = array();
		$this->db->where("pid", $id);
		$this->db->orderby("sort", "asc");
		$qa = $this->db->get('page');
		$i = 0;
		foreach ($qa->result() as $qa_row)
	   	{
			// Dynamically returns only NOT NULL fields
            foreach($qa_row as $field => $value):
                $data[$i]->$field = (!$value || is_null($value)) ? FALSE : $value;
            endforeach;
			
			$aryChildren = array();
			$aryChildren = $this->getChildren($qa_row->id);
			$data = array_merge($data, $aryChildren);
			$i = count($data);
	   	}
		return $data;
	}
	
	function getNewNavLevel($pid)
	{
		$result = 0;
		$n = 0;
		while($pid != 0){
			$n++;
			$this->db->where("id", $pid);
			$qa = $this->db->get('page');
			$qa_row = $qa->row();
			$pid = $qa_row->pid;
		}
		return $n;
	}
	
	/*
	 * This will take a table name and return the next id that will be 
	 * created. This is usful in creating file names, directory names, 
	 * and anything that may need to be associated to a specific record.
	 * @param String table - is the name of the table you want to check.
	 * @return String id - the next id that will be created.
	 */
	function getNewId($table){
		$sql = "SHOW TABLE STATUS LIKE '$table'";
		//echo $sql."<br>";
		$qa = $this->db->query($sql);
		foreach ($qa->result() as $row)
		{
		   $auto_increment = $row->Auto_increment;
		}
		
		return $auto_increment;

	}
	
	function getWidgetData()
	{
		$data = array();
		$i = 0;
		
		$this->db->orderby("date", "DESC");
		$qa = $this->db->get('page', 5, 0);
		
		foreach ($qa->result() as $qa_row)
	   	{
			// Dynamically returns only NOT NULL fields
            foreach($qa_row as $field => $value):
                $data[$i]->$field = (!$value || is_null($value)) ? FALSE : $value;
            endforeach;
            $i++;
	   	}
		
		return $data;
	}
	
	function getFields(){
		$qa = $this->db->get('page', 1, 0);
		$fields = $qa->field_data();
		
		return $fields;
	}
	
	function search($term){
		$data = array();
		$fields = $this->getFields();

		$i = 0;
		foreach($fields as $field_name){
			if($i == 0){
				$this->db->like($field_name->name, $term);
			}else{
				$this->db->orlike($field_name->name, $term);
			}
			$i++;
		}
		
		$qa = $this->db->get('page');
		
		foreach ($qa->result() as $qa_row)
	   	{
			// Dynamically returns only NOT NULL fields
            foreach($qa_row as $field => $value):
                $data[$i]->$field = (!$value || is_null($value)) ? FALSE : $value;
            endforeach;
            $i++;
	   	}
		return $data;
	}
} // end class Page extends Model
?>