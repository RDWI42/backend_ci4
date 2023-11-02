<?php

namespace App\Controllers;

// use App\Controllers\BaseController;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Validation\Validation;

use App\Entities\Artikels as ArtikelsEntity;
use CodeIgniter\HTTP\Request;

class Artikels extends ResourceController
{
    protected $modelName = 'App\Models\ArtikelsModel';
    protected $format = 'json';
    
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $page = $this->request->getGet('page');
        $take = $this->request->getGet('take');
        
        $offset = ($page * $take) - $take;
        
        $model = $this->model
            ->groupStart()
                ->like('UPPER(title)', '%' . strtoupper($keyword) . '%')
                ->orLike('UPPER(content)', '%' . strtoupper($keyword) . '%')
            ->groupEnd()
            ->where('deleted_at IS NULL');
        
        $totalData = $model->countAllResults();

        $data = $model
            ->groupStart()
                ->like('UPPER(title)', '%' . strtoupper($keyword) . '%')
                ->orLike('UPPER(content)', '%' . strtoupper($keyword) . '%')
            ->groupEnd()
            ->where('deleted_at IS NULL')
            ->orderBy('id', 'desc');
        
        if ($take !== null) {
            $getData = $data->findAll($take,$offset);
        } else {
            $getData = $data->findAll();
        }

        return $this->respond([
            'data' => $getData,
            'total' => $totalData
        ]);
    }

    public function show($slug = null)
    {
        $record = $this->model->findBySlug($slug);
        if (!$record) {
            return $this->failNotFound(sprintf(
                'artikels with slug %d not found',
                $slug
            ));
        }

        return $this->respond($record);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $entity = new ArtikelsEntity($data);
        $entity->setSlug($entity->title);
        $rules = [
            'title' => 'required|alpha_numeric_space|min_length[3]|max_length[255]|is_unique[artikels.title]',
            'content' => 'required',
        ];
        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        if ($validation->run($data)) {
    
            if ($this->model->save($entity)) {
                return $this->respondCreated($entity, 'Artikel created');
            } else {
                return $this->fail($this->model->errors());
            }
        } else {
            return $this->fail($validation->getErrors());
        }
        
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        // Mengambil model dengan ID tertentu
        $article = $this->model->find($id);

        if (empty($article)) {
            return $this->failNotFound('Artikel not found');
        }

        $originalTitle = $article->title;
        
        if ($data['title'] !== $originalTitle) {
            $rules = [
                'title' => 'required|alpha_numeric_space|min_length[3]|max_length[255]|is_unique[artikels.title,id,' . $id . ']',
                'content' => 'required',
            ];
            
        } else {
            $rules = [
                'title' => 'alpha_numeric_space|min_length[3]|max_length[255]',
                'content' => 'required',
            ];
        }

        $validation = \Config\Services::validation();
        $validation->setRules($rules);
    
        if ($validation->run($data)) {
            $article->fill($data);
            $entity = new ArtikelsEntity($data);
            if($data['title'] !== $originalTitle){
                $entity->setSlug($entity->title);
            }
            if ($this->model->update($id,$entity)) {
                return $this->respond($data, 200, 'Artikel updated');
            } else {
                return $this->fail($this->model->errors());
            }
        } else {
            return $this->fail($validation->getErrors());
        }
    }

    public function delete($id = null)
    {
        $delete = $this->model->delete($id);
        if ($delete === null) {
            return $this->failNotFound(sprintf('Artikels with id %d not found', $id));
        } elseif ($delete === false) {
            return $this->failServerError('An error occurred while deleting the artikel.');
        }

        return $this->respond(['id' => $id],200, 'Artikel deleted');
    }

    public function uploadImage()
    {
        $request = service('request');
        
        if ($request->getMethod() === 'post') {
            $image = $request->getFile('image');

            if ($image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads', $newName);
                $response = [
                    'message' => 'Image uploaded successfully',
                    'filename' => $newName
                ];
                return $this->respond($response);
            } else {
                return $this->fail($image->getErrorString());
            }
        }
    }
}
