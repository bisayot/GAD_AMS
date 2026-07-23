<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['key', 'value', 'fiscal_year'];
    protected $useTimestamps    = true;

    // Get all settings for a fiscal year
    public function getByFiscalYear(int $fiscalYear): array
    {
        $rows = $this->where('fiscal_year', $fiscalYear)->findAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = is_string($row['value']) && (json_decode($row['value']) !== null) ? json_decode($row['value'], true) : $row['value'];
        }
        return $settings;
    }

    // Save a setting for a fiscal year
    public function saveSetting(string $key, $value, int $fiscalYear): bool
    {
        $existing = $this->where('key', $key)->where('fiscal_year', $fiscalYear)->first();
        $data = [
            'key' => $key,
            'value' => is_array($value) ? json_encode($value) : $value,
            'fiscal_year' => $fiscalYear,
        ];
        if ($existing) {
            return $this->update($existing['id'], $data);
        }
        return $this->insert($data);
    }
}
