<?php

namespace App\Controllers;

use App\Models\TasksModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Tasks extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\TasksModel';
    protected $format    = 'json';
    // public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    // {
    //     parent::initController($request, $response, $logger);

    //     header('Access-Control-Allow-Origin: *');
    //     header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    //     header('Access-Control-Allow-Headers: Content-Type, Authorization');
    // }
    public function index()
    {
        $tasks = $this->model->findAll();
        return $this->respond($tasks);
    }

    public function create()
    {
        $json = $this->request->getJSON();

        $data = array(
            'task' => $json->task,
            'description' => $json->description,            
            'date' => date('Y-m-d'),
        );
        //$this->model->insert($data);
        $taskId = $this->model->insert($data);
        $task = $this->model->find($taskId);
        return $this->respondCreated($task);
        //return $this->respondCreated($data);
        // $task = $this->model->find($taskId);

        //var_dump($json->task);
        //return $this->respondCreated($task);
    }

    public function show($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound('User not found');
        }

        return $this->respond($task);
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON();
        $data->date = date('Y-m-d');        
        $task = $this->model->find($id);

        if (!$task) {
            return $this->failNotFound('User not found');
        }

        $this->model->update($id, $data);
        $task = $this->model->find($id);

        return $this->respondUpdated($task);
    }

    public function delete($id = null)
    {
        $task = $this->model->find($id);

        if (!$task) {
            return $this->failNotFound('User not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted();
    }
}
