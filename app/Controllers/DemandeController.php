<?php

namespace App\Controllers;


class DemandeController extends BaseController
{
    public function index()
    {
        $typeCongeModel = new \App\Models\TypeCongeModel();
        $typesConge = $typeCongeModel->findAll();

        return view('employe/create', ['typesConge' => $typesConge]);
    }

    public function create()
    {
        $demandeModel = new \App\Models\CongeModel();
        $data = [
            'employe_id' => session()->get('employe')['id'],
            'type_conge_id' => $this->request->getPost('type_conge_id'),
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'nb_jours' => date_diff(date_create($this->request->getPost('date_debut')), date_create($this->request->getPost('date_fin')))->format('%a') + 1,
            'motif' => $this->request->getPost('motif'),
            'status' => 'en_attente',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $demandeModel->insert($data);
        return redirect()->to('/employe/dashboard')->with('success', 'Demande de congé créée avec succès');
    }

    // public /
}