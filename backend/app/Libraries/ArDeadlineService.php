<?php

namespace App\Libraries;

use Config\Database;

class ArDeadlineService
{
    /**
     * Check all approved activity designs pending AR submission
     * and dispatch milestone notifications/emails to proponents and staff/admin.
     *
     * @return array Summary of processed and sent notifications
     */
    public static function checkAndDispatchDeadlines(): array
    {
        $db = Database::connect();

        // 1. Fetch approved, archived activity designs without an active AR
        $builder = $db->table('activity_design ad');
        $builder->select('
            ad.act_design_id,
            ad.control_number,
            ad.activity_title,
            ad.start_date,
            ad.end_date,
            ad.user_id,
            u.full_name as proponent_name,
            u.email as proponent_email,
            ou.office_name
        ');
        $builder->join('accomplishment_report ar', 'ar.control_number = ad.control_number AND ar.deleted_at IS NULL', 'left');
        $builder->join('users u', 'u.id = ad.user_id', 'left');
        $builder->join('office_units ou', 'ou.office_id = u.office_id', 'left');
        $builder->where('ad.status', 'Approved');
        $builder->where('ad.is_archived', 1);
        $builder->where('ad.deleted_at IS NULL', null, false);
        $builder->where('ar.id IS NULL', null, false);
        $builder->where('ad.end_date IS NOT NULL', null, false);

        $activities = $builder->get()->getResultArray();

        // 2. Fetch all active GAD staff and admin users
        $adminStaffUsers = $db->table('users')
            ->select('id, full_name, email, role')
            ->where('deleted_at', null)
            ->groupStart()
                ->whereIn('role', ['admin', 'gad_staff'])
                ->orWhere('profile_role', 'Director')
            ->groupEnd()
            ->get()->getResultArray();

        $today = new \DateTime('today');
        $holidays = CalendarService::getHolidayDates();

        $processedCount = 0;
        $sentCount = 0;

        foreach ($activities as $activity) {
            $endDateRaw = $activity['end_date'];
            if (empty($endDateRaw)) {
                continue;
            }

            $endDate = new \DateTime(substr($endDateRaw, 0, 10));
            // If the activity has not concluded yet, skip
            if ($today < $endDate) {
                continue;
            }

            $processedCount++;

            // Calculate 15 working days deadline
            $deadline = CalendarService::addWorkingDays($endDate, 15, $holidays);
            $workingDaysDiff = CalendarService::getWorkingDaysDiff($today, $deadline, $holidays);

            // Determine active milestone based on working days remaining
            $milestonesToTrigger = [];

            // Milestone 1: Activity finished notice
            // Triggered if activity ended and not yet sent
            $milestonesToTrigger[] = 'end_date';

            // Milestone 2: 5 working days reminder
            if ($workingDaysDiff <= 5 && $workingDaysDiff > 0) {
                $milestonesToTrigger[] = 'reminder_5d';
            }

            // Milestone 3: Deadline day (today is the 15th working day)
            if ($workingDaysDiff === 0) {
                $milestonesToTrigger[] = 'deadline_day';
            }

            // Milestone 4: Overdue (past deadline)
            if ($workingDaysDiff < 0) {
                $milestonesToTrigger[] = 'overdue';
            }

            $deadlineFormatted = $deadline->format('F d, Y');
            $endDateFormatted = $endDate->format('F d, Y');
            $actTitle = $activity['activity_title'];
            $controlNo = $activity['control_number'];
            $proponentName = $activity['proponent_name'] ?: 'Proponent';
            $officeName = $activity['office_name'] ?: 'Unit/Office';

            foreach ($milestonesToTrigger as $milestone) {
                // Prepare Proponent Notification Content
                $propTitle = '';
                $propMsg = '';
                $propType = 'info';

                // Prepare Admin/Staff Notification Content
                $staffTitle = '';
                $staffMsg = '';
                $staffType = 'info';

                switch ($milestone) {
                    case 'end_date':
                        $propTitle = 'AR Submission Window Open: ' . $controlNo;
                        $propMsg = "Your activity \"{$actTitle}\" concluded on {$endDateFormatted}. In accordance with GAD guidelines, please submit your Accomplishment Report within 15 working days.\nSubmission Deadline: {$deadlineFormatted}.";
                        $propType = 'info';

                        $staffTitle = 'Activity Concluded — AR Submission Window Open: ' . $controlNo;
                        $staffMsg = "The activity \"{$actTitle}\" by {$proponentName} ({$officeName}) concluded on {$endDateFormatted}. The 15 working days Accomplishment Report submission window is open until {$deadlineFormatted}.";
                        $staffType = 'info';
                        break;

                    case 'reminder_5d':
                        $propTitle = "Reminder: AR Due in {$workingDaysDiff} Working Day(s) — {$controlNo}";
                        $propMsg = "Action Required: You have {$workingDaysDiff} working day(s) remaining to submit the Accomplishment Report for \"{$actTitle}\".\nDeadline: {$deadlineFormatted}.";
                        $propType = 'warning';

                        $staffTitle = "Upcoming AR Deadline (5 Working Days Remaining) — {$controlNo}";
                        $staffMsg = "{$proponentName} ({$officeName}) has {$workingDaysDiff} working day(s) remaining to submit the Accomplishment Report for \"{$actTitle}\" (Deadline: {$deadlineFormatted}).";
                        $staffType = 'info';
                        break;

                    case 'deadline_day':
                        $propTitle = 'Final Notice: AR Submission Deadline Today — ' . $controlNo;
                        $propMsg = "Today ({$deadlineFormatted}) is the final working day to submit the Accomplishment Report for \"{$actTitle}\". Please finalize and submit your report today.";
                        $propType = 'warning';

                        $staffTitle = 'AR Submission Deadline Today — ' . $controlNo;
                        $staffMsg = "Today ({$deadlineFormatted}) is the 15th working day deadline for {$proponentName} ({$officeName}) to submit the Accomplishment Report for \"{$actTitle}\".";
                        $staffType = 'warning';
                        break;

                    case 'overdue':
                        $propTitle = 'Overdue Notice: Accomplishment Report Deadline Passed — ' . $controlNo;
                        $propMsg = "The 15 working day submission deadline ({$deadlineFormatted}) for \"{$actTitle}\" has passed. Please submit your Accomplishment Report immediately. Note that it will be recorded as a late submission.";
                        $propType = 'warning';

                        $staffTitle = 'AR Overdue Alert — ' . $controlNo;
                        $staffMsg = "The Accomplishment Report for \"{$actTitle}\" by {$proponentName} ({$officeName}) is overdue. The 15 working days deadline was {$deadlineFormatted}.";
                        $staffType = 'warning';
                        break;
                }

                // 1. Dispatch to Proponent if not already sent
                if (!empty($activity['user_id'])) {
                    $proponentId = (int)$activity['user_id'];
                    if (!self::isNotificationSent($db, (int)$activity['act_design_id'], $milestone, $proponentId)) {
                        NotificationService::send(
                            $proponentId,
                            $propTitle,
                            $propMsg,
                            '/college/submit-ar',
                            $propType
                        );
                        self::recordSent($db, (int)$activity['act_design_id'], $milestone, $proponentId);
                        $sentCount++;
                    }
                }

                // 2. Dispatch to GAD Staff and Admins if not already sent
                foreach ($adminStaffUsers as $staff) {
                    $staffId = (int)$staff['id'];
                    // Don't send duplicate if the staff member is also the proponent
                    if ($staffId === (int)$activity['user_id']) {
                        continue;
                    }

                    if (!self::isNotificationSent($db, (int)$activity['act_design_id'], $milestone, $staffId)) {
                        NotificationService::send(
                            $staffId,
                            $staffTitle,
                            $staffMsg,
                            '/admin/ar-list',
                            $staffType
                        );
                        self::recordSent($db, (int)$activity['act_design_id'], $milestone, $staffId);
                        $sentCount++;
                    }
                }
            }
        }

        return [
            'success'   => true,
            'processed' => $processedCount,
            'sent'      => $sentCount,
        ];
    }

    /**
     * Check if a milestone notification has already been recorded for this activity and recipient.
     */
    private static function isNotificationSent($db, int $actDesignId, string $milestone, int $recipientUserId): bool
    {
        $existing = $db->table('ar_deadline_notifications')
            ->where('act_design_id', $actDesignId)
            ->where('milestone', $milestone)
            ->where('recipient_user_id', $recipientUserId)
            ->get()->getRowArray();

        return !empty($existing);
    }

    /**
     * Record that a milestone notification has been sent.
     */
    private static function recordSent($db, int $actDesignId, string $milestone, int $recipientUserId): void
    {
        $db->table('ar_deadline_notifications')->insert([
            'act_design_id'     => $actDesignId,
            'milestone'         => $milestone,
            'recipient_user_id' => $recipientUserId,
            'sent_at'           => date('Y-m-d H:i:s'),
        ]);
    }
}

