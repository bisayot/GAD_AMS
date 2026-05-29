<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class SubmissionController extends ResourceController
{
    protected $format = 'json';
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ---------------------------------------------------------------
    // Helper: Validate a single uploaded file is PDF ≤ 10 MB
    // ---------------------------------------------------------------
    private function validatePdf($file): ?string
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return 'No valid file uploaded.';
        }
        $ext  = strtolower($file->getClientExtension());
        $mime = $file->getMimeType();
        if ($ext !== 'pdf' || strpos($mime, 'pdf') === false) {
            return 'Only PDF files are allowed. Received: ' . $ext;
        }
        if ($file->getSize() > 10 * 1024 * 1024) {
            return 'File size must not exceed 10 MB.';
        }
        return null;
    }

    // ---------------------------------------------------------------
    // POST /api/submit-activity-design
    // ---------------------------------------------------------------
    public function submitActivityDesign()
    {
        $post = $this->request->getPost();

        $required = ['form_type', 'activity_title', 'start_date', 'end_date',
                     'venue', 'target_participants', 'proposed_budget', 'user_id'];

        foreach ($required as $field) {
            if (empty($post[$field])) {
                return $this->fail("Field '{$field}' is required.", ResponseInterface::HTTP_BAD_REQUEST);
            }
        }

        // PDF-only validation
        $file     = $this->request->getFile('design_file');
        $pdfError = $this->validatePdf($file);
        if ($pdfError) {
            return $this->fail($pdfError, ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Save file to drafts folder
        $newName    = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/drafts/activity_designs/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $file->move($uploadPath, $newName);
        $attachmentPath = 'uploads/drafts/activity_designs/' . $newName;

        // control_number stays NULL until Director approves
        $data = [
            'act_title'             => $post['activity_title'],
            'form_type'             => $post['form_type'],
            'user_id'               => (int) $post['user_id'],
            'start_date'            => $post['start_date'],
            'end_date'              => $post['end_date'],
            'venue'                 => $post['venue'],
            'target_participants'   => (int) $post['target_participants'],
            'proposed_budget'       => (float) $post['proposed_budget'],
            'attachment_path'       => $attachmentPath,
            'control_number' => null, // assigned by Director on approval
            'status_id'      => 1,    // 1 = Pending
            // Staff-specified office/unit (overrides profile-linked office when set)
            'office_unit'    => !empty($post['office_unit']) ? trim($post['office_unit']) : null,
        ];

        $this->db->table('activity_design')->insert($data);

        if ($this->db->affectedRows() > 0) {
            return $this->respondCreated([
                'success' => true,
                'message' => 'Activity Design submitted successfully.',
            ]);
        }

        return $this->fail('Failed to save activity design.', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
    }

    // ---------------------------------------------------------------
    // POST /api/submit-accomplishment
    // ---------------------------------------------------------------
    public function submitAccomplishment()
    {
        $post = $this->request->getPost();

        $required = ['control_number', 'total_participants', 'male', 'female', 'rating', 'user_id'];

        foreach ($required as $field) {
            if (!isset($post[$field]) || $post[$field] === '') {
                return $this->fail("Field '{$field}' is required.", ResponseInterface::HTTP_BAD_REQUEST);
            }
        }

        // Verify control number exists and is approved
        $ad = $this->db->table('activity_design')
                       ->where('control_number', $post['control_number'])
                       ->get()->getRow();

        if (!$ad) {
            return $this->fail('Invalid control number. No matching approved Activity Design found.', ResponseInterface::HTTP_NOT_FOUND);
        }

        // PDF-only validation for each uploaded file
        $files     = $this->request->getFiles();
        $filePaths = [];

        if (!empty($files['report_files'])) {
            $uploadPath = WRITEPATH . 'uploads/drafts/accomplishment_reports/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            foreach ($files['report_files'] as $file) {
                $pdfError = $this->validatePdf($file);
                if ($pdfError) {
                    return $this->fail('Upload error: ' . $pdfError . ' All files must be PDF.', ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
                }
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $filePaths[] = 'uploads/drafts/accomplishment_reports/' . $newName;
            }
        }

        $data = [
            'control_number'      => $post['control_number'],
            'total_participants'  => (int) $post['total_participants'],
            'male_participants'   => (int) $post['male'],
            'female_participants' => (int) $post['female'],
            'activity_rating'     => (float) $post['rating'],
            'attachments_path'    => !empty($filePaths) ? implode(',', $filePaths) : null,
            'status_id'           => 1, // 1 = Pending
        ];

        $this->db->table('accomplishment_report')->insert($data);

        if ($this->db->affectedRows() > 0) {
            return $this->respondCreated([
                'success' => true,
                'message' => 'Accomplishment Report submitted successfully.',
            ]);
        }

        return $this->fail('Failed to save accomplishment report.', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
    }

    // ---------------------------------------------------------------
    // GET /api/activity-designs
    // All AD submissions across all users — for GAD Staff monitor
    // ---------------------------------------------------------------
    public function listActivityDesigns()
    {
        $rows = $this->db->table('activity_design ad')
            ->select([
                'ad.ad_id',
                'ad.user_id',
                'ad.act_title',
                'ad.form_type',
                'COALESCE(ad.control_number, "PENDING") AS control_number',
                'ad.start_date',
                'ad.end_date',
                'ad.proposed_budget',
                'ad.created_at',
                'ad.status_id',
                's.status_name',
                'CONCAT(up.first_name, " ", up.last_name) AS submitter_name',
                // ad.office_unit (staff-specified) takes priority over profile-linked unit
                'COALESCE(ad.office_unit, ou.unit_name) AS office_unit',
            ])
            ->join('activity_statuses s',  's.id = ad.status_id',          'left')
            ->join('user_profiles up',     'up.user_id = ad.user_id',      'left')
            ->join('office_units ou',      'ou.office_id = up.office_unit_id', 'left')
            ->orderBy('ad.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->respond(['success' => true, 'data' => $rows]);
    }

    // ---------------------------------------------------------------
    // GET /api/accomplishment-reports
    // All AR submissions across all users — for GAD Staff monitor
    // ---------------------------------------------------------------
    public function listAccomplishmentReports()
    {
        $rows = $this->db->table('accomplishment_report ar')
            ->select([
                'ar.ar_id',
                'ar.control_number',
                'ar.total_participants',
                'ar.male_participants',
                'ar.female_participants',
                'ar.activity_rating',
                'ar.created_at',
                'ar.status_id',
                's.status_name',
                'ad.act_title',
                'ad.form_type',
                'ad.user_id AS submitter_user_id',
                'CONCAT(up.first_name, " ", up.last_name) AS submitter_name',
                'ou.unit_name AS office_unit',
            ])
            ->join('activity_statuses s',  's.id = ar.status_id',          'left')
            ->join('activity_design ad',   'ad.control_number = ar.control_number', 'left')
            ->join('user_profiles up',     'up.user_id = ad.user_id',      'left')
            ->join('office_units ou',      'ou.office_id = up.office_unit_id', 'left')
            ->orderBy('ar.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->respond(['success' => true, 'data' => $rows]);
    }

    // ---------------------------------------------------------------
    // POST /api/archive
    // Archive any approved AD or AR (sets status_id = 4)
    // Physically moves file from drafts/ → archived/ folder
    // ---------------------------------------------------------------
    public function archiveDocument()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        $type = $data['type'] ?? null;  // 'design' | 'report'
        $id   = (int) ($data['id']   ?? 0);

        if (!in_array($type, ['design', 'report']) || $id <= 0) {
            return $this->fail('Invalid type or id.', ResponseInterface::HTTP_BAD_REQUEST);
        }

        $table      = $type === 'design' ? 'activity_design'      : 'accomplishment_report';
        $pk         = $type === 'design' ? 'ad_id'                : 'ar_id';
        $pathColumn = $type === 'design' ? 'attachment_path'      : 'attachments_path';

        $record = $this->db->table($table)->where($pk, $id)->get()->getRow();

        if (!$record) {
            return $this->fail('Record not found.', ResponseInterface::HTTP_NOT_FOUND);
        }

        if ((int) $record->status_id !== 2) { // 2 = Approved
            return $this->fail('Only Approved records can be archived.', ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Move file(s) from drafts/ to archived/ and update DB path
        $updateData = ['status_id' => 4]; // 4 = Archived

        $currentPath = $record->$pathColumn ?? '';
        if ($currentPath) {
            // Handle single or comma-separated paths (AR can have multiple)
            $paths    = explode(',', $currentPath);
            $newPaths = [];
            foreach ($paths as $relPath) {
                $relPath = trim($relPath);
                if (!$relPath) continue;

                // Determine archived destination
                $archivedRel = str_replace(
                    ['uploads/drafts/activity_designs/', 'uploads/drafts/accomplishment_reports/'],
                    ['uploads/archived/activity_designs/', 'uploads/archived/accomplishment_reports/'],
                    $relPath
                );

                $srcAbs  = WRITEPATH . $relPath;
                $dstAbs  = WRITEPATH . $archivedRel;
                $dstDir  = dirname($dstAbs);

                if (!is_dir($dstDir)) mkdir($dstDir, 0777, true);

                if (file_exists($srcAbs)) {
                    rename($srcAbs, $dstAbs);
                    $newPaths[] = $archivedRel;
                } else {
                    // File already moved or missing — keep existing path
                    $newPaths[] = $relPath;
                }
            }
            $updateData[$pathColumn] = implode(',', $newPaths);
        }

        $this->db->table($table)->where($pk, $id)->update($updateData);

        return $this->respond([
            'success' => true,
            'message' => ucfirst($type) . ' archived successfully. File moved to archive storage.',
        ]);
    }

    // ---------------------------------------------------------------
    // GET /api/archived-designs
    // ---------------------------------------------------------------
    public function archivedDesigns()
    {
        $rows = $this->db->table('activity_design ad')
            ->select([
                'ad.ad_id AS id',
                'COALESCE(ad.control_number, "PENDING") AS control',
                'ad.act_title AS title',
                'ad.created_at AS dateRaw',
                'ad.status_id',
                's.status_name AS statusText',
            ])
            ->join('activity_statuses s', 's.id = ad.status_id', 'left')
            ->where('ad.status_id', 4)
            ->orderBy('ad.created_at', 'DESC')
            ->get()->getResultArray();

        foreach ($rows as &$r) {
            $r['type']         = 'design';
            $r['dateArchived'] = $r['dateRaw'] ? date('M d, Y', strtotime($r['dateRaw'])) : '—';
            $r['statusClass']  = 'status-archived';
        }

        return $this->respond($rows);
    }

    // ---------------------------------------------------------------
    // GET /api/archived-reports
    // ---------------------------------------------------------------
    public function archivedReports()
    {
        $rows = $this->db->table('accomplishment_report ar')
            ->select([
                'ar.ar_id AS id',
                'ar.control_number AS control',
                'ad.act_title AS title',
                'ar.created_at AS dateRaw',
                'ar.status_id',
                's.status_name AS statusText',
            ])
            ->join('activity_statuses s', 's.id = ar.status_id',          'left')
            ->join('activity_design ad',  'ad.control_number = ar.control_number', 'left')
            ->where('ar.status_id', 4)
            ->orderBy('ar.created_at', 'DESC')
            ->get()->getResultArray();

        foreach ($rows as &$r) {
            $r['type']         = 'report';
            $r['dateArchived'] = $r['dateRaw'] ? date('M d, Y', strtotime($r['dateRaw'])) : '—';
            $r['statusClass']  = 'status-archived';
        }

        return $this->respond($rows);
    }

    // ---------------------------------------------------------------
    // GET /api/approved-designs?user_id=X
    // Used by AR submission dropdown — only approved ADs with control numbers
    // ---------------------------------------------------------------
    public function approvedDesigns()
    {
        $userId = $this->request->getGet('user_id');

        $builder = $this->db->table('activity_design ad')
            ->select('ad.control_number, ad.act_title, ad.start_date')
            ->join('activity_statuses s', 's.id = ad.status_id', 'left')
            ->where('s.status_name', 'Approved')
            ->where('ad.control_number IS NOT NULL', null, false);

        if ($userId) {
            $builder->where('ad.user_id', (int) $userId);
        }

        $rows = $builder->get()->getResultArray();

        return $this->respond(['success' => true, 'data' => $rows]);
    }

    // ---------------------------------------------------------------
    // GET /api/office-units
    // Returns all offices/units from the office_units table.
    // ---------------------------------------------------------------
    public function listOfficeUnits()
    {
        $rows = $this->db->table('office_units')
            ->select('office_id, unit_name')
            ->orderBy('office_id', 'ASC')
            ->get()->getResultArray();

        return $this->respond(['success' => true, 'data' => $rows]);
    }

    public function handleOptions()
    {
        return $this->respond(['status' => 200]);
    }

    // ---------------------------------------------------------------
    // GET /api/submission-tracker
    // Groups all AD/AR submissions by office unit + user role type.
    // Returns per-unit: ad_count, ar_count, total_status, role_type
    // ---------------------------------------------------------------
    public function submissionTracker()
    {
        // All activity designs with their submitter role + office
        $ads = $this->db->table('activity_design ad')
            ->select([
                'ad.ad_id',
                'ad.control_number',
                'ad.act_title',
                'ad.status_id',
                's.status_name AS ad_status',
                'ad.user_id',
                'up.user_role',
                'CONCAT(up.first_name, " ", up.last_name) AS submitter_name',
                'ou.unit_name AS office_unit',
                'ou.office_id',
            ])
            ->join('activity_statuses s',  's.id = ad.status_id',          'left')
            ->join('user_profiles up',     'up.user_id = ad.user_id',      'left')
            ->join('office_units ou',      'ou.office_id = up.office_unit_id', 'left')
            ->where('ad.status_id !=', 4) // exclude archived
            ->orderBy('ou.unit_name', 'ASC')
            ->get()->getResultArray();

        // All accomplishment reports (linked via control_number)
        $arControls = $this->db->table('accomplishment_report ar')
            ->select(['ar.control_number', 'ar.status_id AS ar_status_id', 's.status_name AS ar_status'])
            ->join('activity_statuses s', 's.id = ar.status_id', 'left')
            ->where('ar.status_id !=', 4)
            ->get()->getResultArray();

        // Index ARs by control_number for quick lookup
        $arIndex = [];
        foreach ($arControls as $ar) {
            $arIndex[$ar['control_number']] = $ar;
        }

        // Build per-unit rows
        $unitMap = [];
        foreach ($ads as $ad) {
            $key      = $ad['office_id'] ?? ('__' . $ad['submitter_name']);
            $unitName = $ad['office_unit'] ?: $ad['submitter_name'] ?: 'Unknown Unit';
            $role     = $ad['user_role'] ?? 'Unknown';

            if (!isset($unitMap[$key])) {
                $unitMap[$key] = [
                    'office_unit'    => $unitName,
                    'user_role'      => $role,
                    'ad_count'       => 0,
                    'ar_count'       => 0,
                    'ad_approved'    => 0,
                    'ar_approved'    => 0,
                    'total_status'   => 'Pending',
                ];
            }

            $unitMap[$key]['ad_count']++;
            if ($ad['ad_status'] === 'Approved') {
                $unitMap[$key]['ad_approved']++;
                // Check if there's an AR for this AD
                $cn = $ad['control_number'];
                if ($cn && isset($arIndex[$cn])) {
                    $unitMap[$key]['ar_count']++;
                    if ($arIndex[$cn]['ar_status'] === 'Approved') {
                        $unitMap[$key]['ar_approved']++;
                    }
                }
            }
        }

        // Resolve total_status per unit
        foreach ($unitMap as &$unit) {
            if ($unit['ad_approved'] > 0 && $unit['ar_approved'] >= $unit['ad_approved']) {
                $unit['total_status'] = 'Completed';
            } elseif ($unit['ad_approved'] > 0) {
                $unit['total_status'] = 'Awaiting Accomplishment Report';
            } else {
                $unit['total_status'] = 'Pending';
            }
        }
        unset($unit);

        $rows = array_values($unitMap);

        // Role type counts for stat cards
        $twgCount   = count(array_filter($rows, fn($r) => $r['user_role'] === 'TWG'));
        $nonTwgCount = count(array_filter($rows, fn($r) => $r['user_role'] === 'Non-TWG'));
        $staffCount = count(array_filter($rows, fn($r) => $r['user_role'] === 'Staff'));

        return $this->respond([
            'success' => true,
            'data'    => $rows,
            'stats'   => [
                'total_twg'      => $twgCount,
                'total_non_twg'  => $nonTwgCount,
                'total_staff'    => $staffCount,
                'total_ad'       => array_sum(array_column($rows, 'ad_count')),
                'total_ar'       => array_sum(array_column($rows, 'ar_count')),
            ],
        ]);
    }

    // ---------------------------------------------------------------
    // PUT /api/update-submission
    // Allows owner to re-upload a revised AD or AR (Revision Required)
    // ---------------------------------------------------------------
    public function updateSubmission()
    {
        $post   = $this->request->getPost();
        $type   = $post['type'] ?? null;  // 'design' | 'report'
        $id     = (int) ($post['id'] ?? 0);
        $userId = (int) ($post['user_id'] ?? 0);

        if (!in_array($type, ['design', 'report']) || $id <= 0 || $userId <= 0) {
            return $this->fail('Invalid parameters.', ResponseInterface::HTTP_BAD_REQUEST);
        }

        $table  = $type === 'design' ? 'activity_design'      : 'accomplishment_report';
        $pk     = $type === 'design' ? 'ad_id'                : 'ar_id';
        $owner  = $type === 'design' ? 'user_id'              : null; // AR has no user_id directly

        // Fetch record
        $record = $this->db->table($table)->where($pk, $id)->get()->getRow();
        if (!$record) {
            return $this->fail('Record not found.', ResponseInterface::HTTP_NOT_FOUND);
        }

        // Verify ownership for AD
        if ($type === 'design' && (int)$record->user_id !== $userId) {
            return $this->fail('You can only revise your own submissions.', ResponseInterface::HTTP_FORBIDDEN);
        }

        // For AR: verify ownership via the linked AD
        if ($type === 'report') {
            $ad = $this->db->table('activity_design')
                ->where('control_number', $record->control_number)
                ->get()->getRow();
            if (!$ad || (int)$ad->user_id !== $userId) {
                return $this->fail('You can only revise your own submissions.', ResponseInterface::HTTP_FORBIDDEN);
            }
        }

        // Must be in Revision Required status (3)
        if ((int)$record->status_id !== 3) {
            return $this->fail('Only records with Revision Required status can be revised.', ResponseInterface::HTTP_BAD_REQUEST);
        }

        $updateData = ['status_id' => 1]; // reset to Pending

        // Handle file re-upload if provided
        if ($type === 'design') {
            $file = $this->request->getFile('design_file');
            if ($file && $file->isValid()) {
                $pdfError = $this->validatePdf($file);
                if ($pdfError) {
                    return $this->fail($pdfError, ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
                }
                $newName    = $file->getRandomName();
                $uploadPath = WRITEPATH . 'uploads/drafts/activity_designs/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $newName);
                $updateData['attachment_path'] = 'uploads/drafts/activity_designs/' . $newName;
            }
        } else {
            $files = $this->request->getFiles();
            if (!empty($files['report_files'])) {
                $uploadPath = WRITEPATH . 'uploads/drafts/accomplishment_reports/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
                $filePaths = [];
                foreach ($files['report_files'] as $file) {
                    $pdfError = $this->validatePdf($file);
                    if ($pdfError) {
                        return $this->fail($pdfError, ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $filePaths[] = 'uploads/drafts/accomplishment_reports/' . $newName;
                }
                if ($filePaths) {
                    $updateData['attachments_path'] = implode(',', $filePaths);
                }
            }
        }

        $this->db->table($table)->where($pk, $id)->update($updateData);

        return $this->respond([
            'success' => true,
            'message' => ucfirst($type) . ' resubmitted successfully. Status reset to Pending.',
        ]);
    }

    // ---------------------------------------------------------------
    // POST|GET /api/cleanup-drafts
    // Finds AD/AR records that are Pending or Revision Required and
    // older than 14 days, then deletes BOTH the file AND the DB row.
    // Approved (2) and Archived (4) records are NEVER touched.
    // Protected by a secret token.
    // ---------------------------------------------------------------
    public function cleanupDrafts()
    {
        $secret = getenv('CLEANUP_SECRET') ?: 'gad-cleanup-2025';
        $token  = $this->request->getHeaderLine('X-Cleanup-Token')
               ?: ($this->request->getGet('token') ?? '');

        if ($token !== $secret) {
            return $this->fail('Unauthorized.', ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $cutoff     = date('Y-m-d H:i:s', time() - (14 * 86400));
        $deletedAds = 0;
        $deletedArs = 0;
        $deletedFiles = 0;
        $log        = [];

        // ── Activity Designs ──────────────────────────────────────────
        $expiredAds = $this->db->table('activity_design')
            ->whereIn('status_id', [1, 3])   // Pending or Revision Required only
            ->where('created_at <', $cutoff)
            ->get()->getResultArray();

        foreach ($expiredAds as $ad) {
            $filePath = $ad['attachment_path'] ?? '';
            if ($filePath) {
                $absPath = WRITEPATH . $filePath;
                if (file_exists($absPath) && @unlink($absPath)) {
                    $deletedFiles++;
                    $log[] = 'file_deleted: ' . $filePath;
                }
            }
            $this->db->table('activity_design')->where('ad_id', $ad['ad_id'])->delete();
            $deletedAds++;
            $log[] = 'db_deleted: activity_design #' . $ad['ad_id'] . ' (' . $ad['created_at'] . ')';
        }

        // ── Accomplishment Reports ────────────────────────────────────
        $expiredArs = $this->db->table('accomplishment_report')
            ->whereIn('status_id', [1, 3])
            ->where('created_at <', $cutoff)
            ->get()->getResultArray();

        foreach ($expiredArs as $ar) {
            $pathsRaw = $ar['attachments_path'] ?? '';
            if ($pathsRaw) {
                foreach (explode(',', $pathsRaw) as $relPath) {
                    $relPath = trim($relPath);
                    if (!$relPath) continue;
                    $absPath = WRITEPATH . $relPath;
                    if (file_exists($absPath) && @unlink($absPath)) {
                        $deletedFiles++;
                        $log[] = 'file_deleted: ' . $relPath;
                    }
                }
            }
            $this->db->table('accomplishment_report')->where('ar_id', $ar['ar_id'])->delete();
            $deletedArs++;
            $log[] = 'db_deleted: accomplishment_report #' . $ar['ar_id'] . ' (' . $ar['created_at'] . ')';
        }

        return $this->respond([
            'success'          => true,
            'cutoff_date'      => $cutoff,
            'deleted_ads'      => $deletedAds,
            'deleted_ars'      => $deletedArs,
            'deleted_files'    => $deletedFiles,
            'note'             => 'Approved and Archived records were NOT touched.',
            'log'              => $log,
        ]);
    }
}
