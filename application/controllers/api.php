<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/RestController.php';
use Restserver\Libraries\RestController;

class Api extends RestController {
	public function __construct()
	{
        parent::__construct();
        $this->load->helper('my_api');
	}
	public function students_get()
	{
        $this->load->model('model_students');
        $students = $student = $this->model_students->get_all();
		$this->response($students);
    }
    
	public function student_get()
	{
        $student_id = $this->uri->segment(3);
        $this->load->model('model_students');

        $student = $this->model_students->get_by(array('student_id'=>$student_id, 'status'=>'active'));

        if(isset($student->student_id))
        {
		    $this->response(array('status'=>'Success', 'message' =>$student));
        }
        else{
            $this->response(array('status'=>'Failure', 
                                  'message'=> "The entered student id could not be found!"), 
                            RestController::HTTP_NOT_FOUND);
        }
    }
    
	public function teacher_get()
	{
		$this->response('firstNameAnne');
    }
    
    public function student_put()
    {
        $this->load->library('form_validation');
        $data = removeUnknownFields($this->put(), $this->form_validation->get_field_names("student_put"));
        
        $this->form_validation->set_data($data);

        if($this->form_validation->run('student_put') != FALSE)
        {
            $this->load->model('model_students');
            $exists = $this->model_students->get_by(array('email_address'=> $this->put('email_address')));

            if($exists)
            {
                $this->response(array('status'=>'Failure', 
                                      'message'=> "The specified email address already exists!"), 
                                RestController::HTTP_CONFLICT);
            }
            
            $student_id = $this->model_students->insert($data);

            if(!$student_id)
            {
                $this->response(array('status'=>'Failure', 
                                      'message'=> "An unexpected insert error"), 
                                RestController::HTTP_INTERNAL_SERVER_ERROR);
            }
            else
            {
                $this->response(array('status'=>'Success', 'message' =>'Created'));
            }
        }
        else
        {
            $this->response(array('status'=>'Failure', 
                                  'message'=>$this->form_validation->get_errors_as_array()), 
                            RestController:: HTTP_BAD_REQUEST);
        }
    }

	public function student_post()
	{
        $student_id = $this->uri->segment(3);
        $this->load->model('model_students');
        $student = $this->model_students->get_by(array('student_id'=>$student_id, 'status'=>'active'));

        if(isset($student['student_id']))
        {
            $this->load->library('form_validation');
            $data = removeUnknownFields($this->post(), $this->form_validation->get_field_names("student_post"));
            $this->form_validation->set_data($data);

            if($this->form_validation->run('student_post') != FALSE)
            {
                $this->load->model('model_students');
                $safeEmail = !isset($data['email_address']) || $data['email_address'] == $student['email_address'] || !$this->model_students->get_by(array('email_address'=> $data['email_address']));
    
                if(!$safeEmail)
                {
                    $this->response(array('status'=>'Failure', 
                                          'message'=> "The specified email address already ïn use!"), 
                                    RestController::HTTP_CONFLICT);
                }
                
                $updated = $this->model_students->update($student_id, $data);
    
                if(!$updated)
                {
                    $this->response(array('status'=>'Failure', 
                                          'message'=> "An unexpected update error"), 
                                    RestController::HTTP_INTERNAL_SERVER_ERROR);
                }
                else
                {
                    $this->response(array('status'=>'Success', 'message' =>'Updated'));
                }
            }
            else
            {
                $this->response(array('status'=>'Failure', 
                                      'message'=>$this->form_validation->get_errors_as_array()), 
                                RestController:: HTTP_BAD_REQUEST);
            }
        }
        else{
            $this->response(array('status'=>'Failure', 
                                  'message'=> "The entered student id could not be found!"), 
                            RestController::HTTP_NOT_FOUND);
        }
    }
    
	public function student_delete()
	{
        $student_id = $this->uri->segment(3);
        $this->load->model('model_students');

        $student = $this->model_students->get_by(array('student_id'=>$student_id, 'status'=>'active'));

        if(isset($student['student_id']))
        {
            $data['status'] = 'deleted';
            $deleted = $this->model_students->update($student_id, $data);
    
            if(!$deleted)
            {
                $this->response(array('status'=>'Failure', 
                                      'message'=> "An unexpected error when trying to delete"), 
                                RestController::HTTP_INTERNAL_SERVER_ERROR);
            }
            else
            {
                $this->response(array('status'=>'Success', 'message' =>'Deleted'));
            }
        }
        else{
            $this->response(array('status'=>'Failure', 
                                  'message'=> "The entered student id could not be found!"), 
                            RestController::HTTP_NOT_FOUND);
        }
    }
}
