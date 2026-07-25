<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SettingModel;

class SettingController extends ResourceController
{
    protected $format = 'json';
    protected $defaultBaselines = [
        'meals_inside' => 220,
        'meals_outside' => 350,
        'snacks_inside' => 80,
        'snacks_outside' => 150,
        'pf_honoraria' => 2258.25,
        'tokens' => 1000,
        'materials' => 1000,
        'transportation_limit' => 20000
    ];

    public function getBaselineAmounts()
    {
        $settingModel = new SettingModel();
        // We use year 0 or a specific key for global settings not tied to a specific fiscal year
        $baselines = $settingModel->where('key', 'baseline_amounts')->first();

        if ($baselines && isset($baselines['value'])) {
            $values = json_decode($baselines['value'], true);
            if (is_array($values)) {
                // Merge with defaults to ensure all keys exist
                return $this->respond(array_merge($this->defaultBaselines, $values));
            }
        }

        return $this->respond($this->defaultBaselines);
    }

    public function updateBaselineAmounts()
    {
        $settingModel = new SettingModel();
        
        $rules = [
            'meals_inside' => 'required|numeric',
            'meals_outside' => 'required|numeric',
            'snacks_inside' => 'required|numeric',
            'snacks_outside' => 'required|numeric',
            'pf_honoraria' => 'required|numeric',
            'tokens' => 'required|numeric',
            'materials' => 'required|numeric',
            'transportation_limit' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $settingModel->saveSetting('baseline_amounts', $data, 0); // Using 0 for global fiscal year

        return $this->respondUpdated(['message' => 'Baseline amounts updated successfully']);
    }

    public function getSystemSettings()
    {
        $settingModel = new SettingModel();
        $setting = $settingModel->where('key', 'system_settings')->first();
        
        $defaults = [
            'ad_submission_limit_enabled' => true,
            'trash_ttl_days' => 30,
            'messages_ttl_days' => 365,
            'activity_logs_ttl_days' => 365,
            'archived_documents_ttl_days' => 1825
        ];

        if ($setting && isset($setting['value'])) {
            $values = json_decode($setting['value'], true);
            if (is_array($values)) {
                return $this->respond(array_merge($defaults, $values));
            }
        }
        return $this->respond($defaults);
    }

    public function updateSystemSettings()
    {
        $settingModel = new SettingModel();
        $data = $this->request->getJSON(true);
        $settingModel->saveSetting('system_settings', $data, 0); // Using 0 for global fiscal year
        return $this->respondUpdated(['message' => 'Settings updated successfully']);
    }

    public function triggerCleanup()
    {
        try {
            $result = \App\Libraries\DataRetentionService::runCleanup(true); // force run
            return $this->respond(['success' => true, 'message' => 'Cleanup triggered', 'result' => $result]);
        } catch (\Exception $e) {
            return $this->fail('Cleanup failed: ' . $e->getMessage());
        }
    }
}
