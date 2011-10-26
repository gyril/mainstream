<?php

class User_Model extends CI_Model
{
	
	/** Utility Methods **/
	function _required($required, $data)
	{
		foreach($required as $field)
			if(!isset($data[$field])) return false;
			
		return true;
	}
	
	function _default($defaults, $options)
	{
		return array_merge($defaults, $options);
	}
	
	
	/** User Methods **/
	
	function AddUser($options = array())
	{
		// required values
		if(!$this->_required(
			array('email', 'password'),
			$options)
		) return false;
		
		$options['email'] = strtolower($options['email']);
		$options['password'] = md5($options['password']);
		
		$this->db->insert('users', $options);
		
		return $this->db->insert_id();
	}
	
	function GetUsers($options = array())
	{
		// Qualification
		if(isset($options['id_user']))
			$this->db->where('id_user', $options['id_user']);
		if(isset($options['email']))
			$this->db->where('email', strtolower($options['email']));
		if(isset($options['nickname']))
			$this->db->where('nickname', $options['nickname']);	

		// limit / offset
		if(isset($options['limit']) && isset($options['offset']))
			$this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit']))
			$this->db->limit($options['limit']);
			
		// sort
		if(isset($options['sortBy']) && isset($options['sortDirection']))
			$this->db->order_by($options['sortBy'], $options['sortDirection']);
			
		if(isset($options['count'])) return $query->num_rows();
		
		if(isset($options['id_user']) || isset($options['email']) || isset($options['nickname']))
			return $query->row(0);
			
		return $query->result();
	}
	
	/** Login Methods **/
	
	function Login($options = array())
	{
		// required values
		if(!$this->_required(
			array('email', 'password'),
			$options)
		) return false;
		
		$user = $this->GetUsers(array('email' => $options['email']));
		if(!$user) return false;
		if($user->password != md5($options['password'])) return false;
		
		$this->session->set_userdata('email', $user->email);
		$this->session->set_userdata('id_user', $user->id_user);
		$this->session->set_userdata('nickname', $user->nickname);
		
		/** remember me feature **/
		
		if(isset($options['permanent']) && $options['permanent'] == true) {			
			$token = sha1(uniqid(rand(), true));
			$expire = 1000000;
			
			$this->db->insert('cookies', array('id_user' => $user->id_user,'token' => $token, 'death' => date('Y-m-d H:i:s', time()+$expire)));
			
			$cookie = array(
			    'name'   => 'permanent',
			    'value'  => 'id_user='.$user->id_user.'&token='.$token,
			    'expire' => $expire,
			);

			$this->input->set_cookie($cookie);
		}
		
		return true;
	}
	
	function isLogged($options = array())
	{
		$id_user = $this->session->userdata('id_user');
		if(empty($id_user))
			return false;
			
		return $id_user;
	}
	
	function UpdateSession($options = array()) 
	{
		// required values
		if(!$this->_required(
			array('id_user'),
			$options)
		) return false;
		
		$user = $this->GetUsers(array('id_user' => $options['id_user']));
		
		if(!$user) return false;
		
		$this->session->set_userdata('email', $user->email);
		$this->session->set_userdata('nickname', $user->nickname);
		
		return true;	
	}
	
	function autoLogin($cookie)
	{
		$cookieData = explode('&', $cookie);
		$id_user = explode('=', $cookieData[0]);
		$id_user = $id_user[1];
		$token = explode('=', $cookieData[1]);
		$token = $token[1];
		
		$this->db->where('id_user', $id_user);
		$this->db->where('token', $token);
		
		$query = $this->db->get('cookies');
		
		if($query->result() && strtotime($query->row(0)->death) - time() > 0) 
		{
			$user = $this->GetUsers(array('id_user' => $id_user));
			
			$this->session->set_userdata('id_user', $user->id_user);
			$this->session->set_userdata('nickname', $user->nickname);
			return true;
		}
		
		return false;
	}
	
}

?>
