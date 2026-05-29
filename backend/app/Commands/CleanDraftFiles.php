<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * CleanDraftFiles
 *
 * Finds AD and AR submissions that are still PENDING or REVISION REQUIRED
 * and are older than 14 days, then:
 *   1. Deletes the physical PDF file(s) from the drafts/ folder
 *   2. Deletes the database record
 *
 * Approved / Archived records are NEVER touched.
 *
 * Usage (Spark CLI):
 *   php spark cleanup:drafts
 *
 * Usage (Windows Task Scheduler):
 *   php C:\xampp\htdocs\GAD_AMS-main\backend\spark cleanup:drafts
 */
class CleanDraftFiles extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'cleanup:drafts';
    protected $description = 'Deletes draft AD/AR records (and their files) older than 14 days that are still Pending or Revision Required.';

    /** Days after which an unreviewed draft is considered expired */
    private int $expiryDays = 14;

    public function run(array $params): void
    {
        $db     = \Config\Database::connect();
        $cutoff = date('Y-m-d H:i:s', time() - ($this->expiryDays * 86400));

        $deletedFiles  = 0;
        $deletedAds    = 0;
        $deletedArs    = 0;
        $errorFiles    = 0;

        CLI::write('');
        CLI::write('GAD AMS — Draft Cleanup (' . $this->expiryDays . '-day expiry)', 'green');
        CLI::write('Cutoff date  : ' . $cutoff, 'yellow');
        CLI::write('Targets      : Pending (1) and Revision Required (3) submissions only', 'yellow');
        CLI::write('Safe from    : Approved (2) and Archived (4) — never touched', 'cyan');
        CLI::write(str_repeat('─', 65), 'dark_gray');

        // ── 1. Expire old Activity Designs ────────────────────────────
        CLI::write('[Activity Designs]', 'light_cyan');

        $expiredAds = $db->table('activity_design')
            ->whereIn('status_id', [1, 3])          // Pending or Revision Required only
            ->where('created_at <', $cutoff)
            ->get()
            ->getResultArray();

        foreach ($expiredAds as $ad) {
            $filePath = $ad['attachment_path'] ?? '';

            // Delete the physical file if it exists
            if ($filePath) {
                $absPath = WRITEPATH . $filePath;
                if (file_exists($absPath)) {
                    if (@unlink($absPath)) {
                        $deletedFiles++;
                        CLI::write('  [FILE DEL] ' . $filePath, 'red');
                    } else {
                        $errorFiles++;
                        CLI::write('  [FILE ERR] Could not delete: ' . $filePath, 'light_red');
                    }
                } else {
                    CLI::write('  [FILE N/A] Already missing: ' . $filePath, 'dark_gray');
                }
            }

            // Delete the database record
            $db->table('activity_design')->where('ad_id', $ad['ad_id'])->delete();
            $deletedAds++;
            CLI::write('  [DB DEL  ] AD #' . $ad['ad_id'] . ' — "' . ($ad['act_title'] ?? '') . '" (' . $ad['created_at'] . ')', 'red');
        }

        if (empty($expiredAds)) {
            CLI::write('  (no expired activity designs found)', 'dark_gray');
        }

        // ── 2. Expire old Accomplishment Reports ───────────────────────
        CLI::write('[Accomplishment Reports]', 'light_cyan');

        $expiredArs = $db->table('accomplishment_report')
            ->whereIn('status_id', [1, 3])
            ->where('created_at <', $cutoff)
            ->get()
            ->getResultArray();

        foreach ($expiredArs as $ar) {
            $pathsRaw = $ar['attachments_path'] ?? '';

            if ($pathsRaw) {
                foreach (explode(',', $pathsRaw) as $relPath) {
                    $relPath = trim($relPath);
                    if (!$relPath) continue;

                    $absPath = WRITEPATH . $relPath;
                    if (file_exists($absPath)) {
                        if (@unlink($absPath)) {
                            $deletedFiles++;
                            CLI::write('  [FILE DEL] ' . $relPath, 'red');
                        } else {
                            $errorFiles++;
                            CLI::write('  [FILE ERR] Could not delete: ' . $relPath, 'light_red');
                        }
                    } else {
                        CLI::write('  [FILE N/A] Already missing: ' . $relPath, 'dark_gray');
                    }
                }
            }

            $db->table('accomplishment_report')->where('ar_id', $ar['ar_id'])->delete();
            $deletedArs++;
            CLI::write('  [DB DEL  ] AR #' . $ar['ar_id'] . ' — Control: ' . ($ar['control_number'] ?? 'N/A') . ' (' . $ar['created_at'] . ')', 'red');
        }

        if (empty($expiredArs)) {
            CLI::write('  (no expired accomplishment reports found)', 'dark_gray');
        }

        // ── Summary ────────────────────────────────────────────────────
        CLI::write(str_repeat('─', 65), 'dark_gray');
        CLI::write('Done.', 'green');
        CLI::write('  Activity Designs removed : ' . $deletedAds,   $deletedAds  > 0 ? 'red' : 'white');
        CLI::write('  Accomplishment Reports   : ' . $deletedArs,   $deletedArs  > 0 ? 'red' : 'white');
        CLI::write('  PDF files deleted        : ' . $deletedFiles, $deletedFiles > 0 ? 'red' : 'white');
        if ($errorFiles > 0) {
            CLI::write('  File errors              : ' . $errorFiles, 'light_red');
        }
        CLI::write('');
        CLI::write('Note: Approved and Archived records were NOT touched.', 'cyan');
        CLI::write('');
    }
}
