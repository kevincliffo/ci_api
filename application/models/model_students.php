<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Students extends MY_Model{
    protected $_table = 'students';
    protected $primary_key = 'student_id';
    protected $return_type = 'array';
    protected $after_get = array('removeSensitiveData');
    protected $before_create = array('prepData');
    protected $before_update = array('updateTimeStamp');

    protected function removeSensitiveData($student)
    {
        unset($student->password);
        unset($student->ip_address);

        return $student;
    }

    protected function prepData($student)
    {
        $student['password'] = md5($student['password']);
        $student['ip_address'] = $this->input->ip_address();
        $student['created_timestamp'] = date('Y-m-d H:i:s');

        return $student;
    }

    protected function updateTimeStamp($student)
    {
        $student['updated_timestamp'] = date('Y-m-d H:i:s');

        return $student;
    }
}