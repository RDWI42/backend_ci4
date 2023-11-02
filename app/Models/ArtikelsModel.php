<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Artikels;

class ArtikelsModel extends Model
{

    protected $table            = 'artikels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Artikels::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'slug',
        'content',
        'img',
        'views'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title' => 'required|alpha_numeric_space|min_length[3]|max_length[255]',
        'content' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    public function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }
}
