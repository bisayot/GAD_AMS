<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class MandateController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $db = \Config\Database::connect();
        
        // Fetch all mandates
        $mandates = $db->table('gad_mandates')->get()->getResultArray();
        
        // Fetch all issues
        $issues = $db->table('gender_issues')->get()->getResultArray();
        
        // Map issues to their mandates
        $issuesByMandate = [];
        foreach ($issues as $issue) {
            $mandateId = $issue['mandate_id'];
            if (!isset($issuesByMandate[$mandateId])) {
                $issuesByMandate[$mandateId] = [];
            }
            $issuesByMandate[$mandateId][] = $issue;
        }
        
        // Attach to mandates
        foreach ($mandates as &$mandate) {
            $mandate['gender_issues'] = $issuesByMandate[$mandate['id']] ?? [];
        }
        
        return $this->respond([
            'success' => true,
            'data' => $mandates
        ]);
    }

    public function storeMandate()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true);
        
        if (empty($data['code']) || empty($data['title'])) {
            return $this->failValidationErrors('Code and Title are required');
        }
        
        $insertData = [
            'code' => $data['code'],
            'title' => $data['title'],
            'status' => $data['status'] ?? 'active',
            'budget' => $data['budget'] ?? '₱0.00',
            'responsible_unit' => $data['responsible_unit'] ?? 'OSS'
        ];
        
        if ($db->table('gad_mandates')->insert($insertData)) {
            return $this->respondCreated(['success' => true, 'id' => $db->insertID()]);
        }
        return $this->failServerError('Failed to create mandate');
    }

    public function updateMandate($id = null)
    {
        if (!$id) return $this->failNotFound('ID is required');
        
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true);
        
        $updateData = [];
        if (isset($data['code'])) $updateData['code'] = $data['code'];
        if (isset($data['title'])) $updateData['title'] = $data['title'];
        if (isset($data['status'])) $updateData['status'] = $data['status'];
        if (isset($data['budget'])) $updateData['budget'] = $data['budget'];
        if (isset($data['responsible_unit'])) $updateData['responsible_unit'] = $data['responsible_unit'];
        
        if ($db->table('gad_mandates')->where('id', $id)->update($updateData)) {
            return $this->respond(['success' => true]);
        }
        return $this->failServerError('Failed to update mandate');
    }

    public function deleteMandate($id = null)
    {
        if (!$id) return $this->failNotFound('ID is required');
        
        $db = \Config\Database::connect();
        
        $db->transStart();
        // Delete issues first to maintain referential integrity
        $db->table('gender_issues')->where('mandate_id', $id)->delete();
        $db->table('gad_mandates')->where('id', $id)->delete();
        $db->transComplete();
        
        if ($db->transStatus() === FALSE) {
            return $this->failServerError('Failed to delete mandate');
        }
        
        return $this->respondDeleted(['success' => true]);
    }

    public function storeIssue()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true);
        
        if (empty($data['mandate_id']) || empty($data['title'])) {
            return $this->failValidationErrors('Mandate ID and Title are required');
        }
        
        $insertData = [
            'mandate_id' => $data['mandate_id'],
            'title' => $data['title'],
            'gad_objective' => $data['gad_objective'] ?? '',
            'status' => $data['status'] ?? 'active'
        ];
        
        if ($db->table('gender_issues')->insert($insertData)) {
            return $this->respondCreated(['success' => true, 'id' => $db->insertID()]);
        }
        return $this->failServerError('Failed to create issue');
    }

    public function updateIssue($id = null)
    {
        if (!$id) return $this->failNotFound('ID is required');
        
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true);
        
        $updateData = [];
        if (isset($data['title'])) $updateData['title'] = $data['title'];
        if (isset($data['gad_objective'])) $updateData['gad_objective'] = $data['gad_objective'];
        if (isset($data['status'])) $updateData['status'] = $data['status'];
        
        if ($db->table('gender_issues')->where('id', $id)->update($updateData)) {
            return $this->respond(['success' => true]);
        }
        return $this->failServerError('Failed to update issue');
    }

    public function deleteIssue($id = null)
    {
        if (!$id) return $this->failNotFound('ID is required');
        
        $db = \Config\Database::connect();
        if ($db->table('gender_issues')->where('id', $id)->delete()) {
            return $this->respondDeleted(['success' => true]);
        }
        return $this->failServerError('Failed to delete issue');
    }
}
