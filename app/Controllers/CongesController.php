<?php

use App\Controllers\BaseController;
use App\Models\Conges;
class CongesController extends BaseController
{
    public function index()
    {
        $congesModel = new Conges();
        $data['conges'] = $congesModel->findAllwithDetails();
        return view('conges/index', $data);
    }
    public function show($id)
    {
        $congesModel = new Conges();
        $data['conge'] = $congesModel->find($id);
        return view('conges/show', $data);
    }
    public function delete($id)
    {
        $congesModel = new Conges();
        $congesModel->delete($id);
        return redirect()->to('/conges');
    }
}   