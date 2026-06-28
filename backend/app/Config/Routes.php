<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function($routes) {

    // ----------------------------------------------------------------
    // AUTH ROUTES (existing)
    // ----------------------------------------------------------------
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('logout', 'AuthController::logout');
    $routes->post('forgot-password', 'AuthController::forgotPassword');
    $routes->post('reset-password', 'AuthController::resetPassword');
    $routes->options('forgot-password', 'AuthController::handleOptions');
    $routes->options('reset-password', 'AuthController::handleOptions');

    // CORS preflight routes (existing)
    $routes->options('login', 'AuthController::handleOptions');
    $routes->options('register', 'AuthController::handleOptions');
    $routes->options('logout', 'AuthController::handleOptions');
    $routes->get('office_units', 'AuthController::getOffices');
    $routes->post('add_office', 'AuthController::addOffice');
    $routes->options('office_units', 'AuthController::handleOptions');
    $routes->options('add_office', 'AuthController::handleOptions');

    // ----------------------------------------------------------------
    // OFFICE MANAGEMENT ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('offices', 'AuthController::handleOptions');
    $routes->get('offices', 'OfficeController::index');
    $routes->post('offices', 'OfficeController::create');
    $routes->options('offices/(:num)', 'AuthController::handleOptions');
    $routes->put('offices/(:num)', 'OfficeController::update/$1');
    $routes->delete('offices/(:num)', 'OfficeController::delete/$1');
    $routes->get('users', 'AuthController::getAllUsers');
    $routes->options('users', 'AuthController::handleOptions');

    // ----------------------------------------------------------------
    // USER MANAGEMENT ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('users/suspend/(:num)', 'AuthController::handleOptions');
    $routes->post('users/suspend/(:num)', 'UserManagementController::suspend/$1');
    $routes->options('users/restore/(:num)', 'AuthController::handleOptions');
    $routes->post('users/restore/(:num)', 'UserManagementController::restore/$1');
    $routes->options('users/delete/(:num)', 'AuthController::handleOptions');
    $routes->delete('users/delete/(:num)', 'UserManagementController::permanentlyDelete/$1');
    $routes->options('users/create', 'AuthController::handleOptions');
    $routes->post('users/create', 'UserManagementController::create');
    $routes->options('users/update/(:num)', 'AuthController::handleOptions');
    $routes->post('users/update/(:num)', 'UserManagementController::update/$1');

    $routes->options('users/profile', 'AuthController::handleOptions');
    $routes->get('users/profile', 'UserManagementController::getProfile');
    $routes->options('users/profile/update', 'AuthController::handleOptions');
    $routes->post('users/profile/update', 'UserManagementController::updateProfile');

    // ----------------------------------------------------------------
    // ACTIVITY LOGS ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('activity-logs', 'AuthController::handleOptions');
    $routes->get('activity-logs', 'ActivityLogController::index');

    // ----------------------------------------------------------------
    // CLOUDFLARE R2 STORAGE ROUTE (existing)
    // ----------------------------------------------------------------
    $routes->post('storage/ticket', 'StorageController::getUploadTicket');
    $routes->options('storage/ticket', 'AuthController::handleOptions');

    // ----------------------------------------------------------------
    // ACTIVITY DESIGN ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('submit-activity-design', 'AuthController::handleOptions');
    $routes->post('submit-activity-design', 'ActivityDesignController::submitDesign');
    $routes->options('activity-designs/submit', 'AuthController::handleOptions');
    $routes->post('activity-designs/submit', 'ActivityDesignController::submitDesign');
    $routes->options('activity-designs/trash/(:num)', 'AuthController::handleOptions');
    $routes->delete('activity-designs/trash/(:num)', 'ActivityDesignController::trash/$1');

    $routes->options('get-form-types', 'AuthController::handleOptions');
    $routes->get('get-form-types', 'ActivityDesignController::getFormTypes');
    
    $routes->options('get-gad-mandates', 'AuthController::handleOptions');
    $routes->get('get-gad-mandates', 'ActivityDesignController::getGADMandates');
    
    $routes->options('get-gender-issues', 'AuthController::handleOptions');
    $routes->get('get-gender-issues', 'ActivityDesignController::getGenderIssues');
    
    $routes->options('get-gender-issues/(:num)', 'AuthController::handleOptions');
    $routes->get('get-gender-issues/(:num)', 'ActivityDesignController::getGenderIssues/$1');
    
    $routes->options('get-activity-classifications', 'AuthController::handleOptions');
    $routes->get('get-activity-classifications', 'ActivityDesignController::getActivityClassifications');

    $routes->options('activity-designs', 'ActivityDesignController::index');
    $routes->get('activity-designs', 'ActivityDesignController::index');

    $routes->options('activity-design/(:num)', 'ActivityDesignController::show/$1');
    $routes->get('activity-design/(:num)', 'ActivityDesignController::show/$1');

    // User-specific designs list
    $routes->options('activity-designs/(:num)', 'ActivityDesignController::getUserDesigns/$1');
    $routes->get('activity-designs/(:num)', 'ActivityDesignController::getUserDesigns/$1');

    // Update/revision
    $routes->options('update-design/(:num)', 'ActivityDesignController::updateDesign/$1');
    $routes->post('update-design/(:num)', 'ActivityDesignController::updateDesign/$1');

    // Update deadline
    $routes->options('update-deadline/(:num)', 'AuthController::handleOptions');
    $routes->post('update-deadline/(:num)', 'ActivityDesignController::updateDeadline/$1');

    // ----------------------------------------------------------------
    // MANDATES & GENDER ISSUES ROUTES
    // ----------------------------------------------------------------
    $routes->options('mandates', 'AuthController::handleOptions');
    $routes->get('mandates', 'MandateController::index');
    $routes->post('mandates', 'MandateController::storeMandate');
    $routes->options('mandates/(:num)', 'AuthController::handleOptions');
    $routes->put('mandates/(:num)', 'MandateController::updateMandate/$1');
    $routes->delete('mandates/(:num)', 'MandateController::deleteMandate/$1');

    $routes->options('gender-issues', 'AuthController::handleOptions');
    $routes->post('gender-issues', 'MandateController::storeIssue');
    $routes->options('gender-issues/(:num)', 'AuthController::handleOptions');
    $routes->put('gender-issues/(:num)', 'MandateController::updateIssue/$1');
    $routes->delete('gender-issues/(:num)', 'MandateController::deleteIssue/$1');

    // ----------------------------------------------------------------
    // ACCOMPLISHMENT REPORT ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('submit-activity-report', 'AuthController::handleOptions');
    $routes->post('submit-activity-report', 'AccomplishmentReportController::submitReport');
    $routes->options('accomplishment-reports/submit', 'AuthController::handleOptions');
    $routes->post('accomplishment-reports/submit', 'AccomplishmentReportController::submitReport');
    $routes->options('accomplishment-reports/trash/(:num)', 'AuthController::handleOptions');
    $routes->delete('accomplishment-reports/trash/(:num)', 'AccomplishmentReportController::trash/$1');

    $routes->options('activity-reports', 'AccomplishmentReportController::index');
    $routes->get('activity-reports', 'AccomplishmentReportController::index');

    $routes->options('activity-report/(:num)', 'AccomplishmentReportController::show/$1');
    $routes->get('activity-report/(:num)', 'AccomplishmentReportController::show/$1');

    // User-specific reports list
    $routes->options('activity-reports/(:num)', 'AccomplishmentReportController::getUserReports/$1');
    $routes->get('activity-reports/(:num)', 'AccomplishmentReportController::getUserReports/$1');

    // Update/revision
    $routes->options('update-report/(:num)', 'AccomplishmentReportController::updateReport/$1');
    $routes->post('update-report/(:num)', 'AccomplishmentReportController::updateReport/$1');

    // ----------------------------------------------------------------
    // APPROVED CONTROL NUMBERS (new)
    // ----------------------------------------------------------------
    $routes->options('approved-controls/(:num)', 'AuthController::handleOptions');
    $routes->get('approved-controls/(:num)', 'ApprovedControlsController::index/$1');

    // ----------------------------------------------------------------
    // ARCHIVE ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('archives', 'ArchiveController::index');
    $routes->get('archives', 'ArchiveController::index');
    $routes->options('venues', 'AuthController::handleOptions');
    $routes->get('venues', 'VenueController::index');
    $routes->post('archive-design/(:num)', 'ArchiveController::archiveDesign/$1');
    $routes->post('archive-report/(:num)', 'ArchiveController::archiveReport/$1');

    // ----------------------------------------------------------------
    // ANALYTICS ROUTES
    // ----------------------------------------------------------------
    $routes->options('analytics/participants/(:num)', 'AuthController::handleOptions');
    $routes->get('analytics/participants/(:num)', 'AnalyticsController::getParticipants/$1');
    $routes->options('analytics/participants/user/(:num)/(:num)', 'AuthController::handleOptions');
    $routes->get('analytics/participants/user/(:num)/(:num)', 'AnalyticsController::getParticipantsByUser/$1/$2');

    // ----------------------------------------------------------------
    // ADMIN TRACKING ROUTES (new)
    // ----------------------------------------------------------------
    $routes->options('admin/twg-submissions', 'AuthController::handleOptions');
    $routes->get('admin/twg-submissions', 'ActivityDesignController::getTWGSubmissions');

    // ----------------------------------------------------------------
    // APPROVAL ROUTES (auto-archives on approval)
    // ----------------------------------------------------------------
    $routes->options('approve-design/(:num)', 'AuthController::handleOptions');
    $routes->post('approve-design/(:num)', 'ActivityDesignController::approveDesign/$1');

    $routes->options('approve-report/(:num)', 'AuthController::handleOptions');
    $routes->post('approve-report/(:num)', 'AccomplishmentReportController::approveReport/$1');

    // ----------------------------------------------------------------
    // REVISION ROUTES
    // ----------------------------------------------------------------
    $routes->options('revision-design/(:num)', 'AuthController::handleOptions');
    $routes->post('revision-design/(:num)', 'ActivityDesignController::revisionDesign/$1');

    $routes->options('revision-report/(:num)', 'AuthController::handleOptions');
    $routes->post('revision-report/(:num)', 'AccomplishmentReportController::revisionReport/$1');

    // ----------------------------------------------------------------
    // MESSAGING ROUTES
    // ----------------------------------------------------------------
    $routes->options('messages/send', 'AuthController::handleOptions');
    $routes->post('messages/send', 'MessageController::send');
    $routes->options('messages/announce', 'AuthController::handleOptions');
    $routes->post('messages/announce', 'MessageController::announce');
    $routes->options('messages/inbox/(:num)', 'AuthController::handleOptions');
    $routes->get('messages/inbox/(:num)', 'MessageController::getInbox/$1');
    $routes->options('messages/sent/(:num)', 'AuthController::handleOptions');
    $routes->get('messages/sent/(:num)', 'MessageController::getSent/$1');
    $routes->options('messages/read/(:num)', 'AuthController::handleOptions');
    $routes->post('messages/read/(:num)', 'MessageController::markAsRead/$1');

    $routes->options('messages/trashed/(:num)', 'AuthController::handleOptions');
    $routes->get('messages/trashed/(:num)', 'MessageController::getTrashed/$1');

    $routes->options('messages/bulk-trash', 'AuthController::handleOptions');
    $routes->post('messages/bulk-trash', 'MessageController::bulkTrash');
    $routes->options('messages/bulk-restore', 'AuthController::handleOptions');
    $routes->post('messages/bulk-restore', 'MessageController::bulkRestore');
    
    $routes->options('messages/trash/(:num)', 'AuthController::handleOptions');
    $routes->post('messages/trash/(:num)', 'MessageController::trashMessage/$1');

    $routes->options('messages/restore/(:num)', 'AuthController::handleOptions');
    $routes->post('messages/restore/(:num)', 'MessageController::restoreMessage/$1');

    $routes->options('messages/permanently-delete', 'AuthController::handleOptions');
    $routes->post('messages/permanently-delete', 'MessageController::permanentlyDelete');

    $routes->options('messages/thread/(:num)', 'AuthController::handleOptions');
    $routes->get('messages/thread/(:num)', 'MessageController::getThread/$1');
    
    $routes->options('messages/unread-count/(:num)', 'AuthController::handleOptions');
    $routes->get('messages/unread-count/(:num)', 'MessageController::getUnreadCount/$1');

    // ----------------------------------------------------------------
    // BUDGET ROUTES
    // ----------------------------------------------------------------
    $routes->options('budget/summary', 'BudgetController::optionsHandler');
    $routes->get('budget/summary', 'BudgetController::getSummary');
    $routes->options('budget/gad-plan', 'BudgetController::optionsHandler');
    $routes->get('budget/gad-plan', 'BudgetController::getGadPlan');
    
    // Office Budget Utilization and Realignment Monitoring
    $routes->options('staff/budget-monitoring', 'BudgetController::optionsHandler');
    $routes->get('staff/budget-monitoring', 'BudgetController::getOfficeUtilization');
    
    $routes->options('staff/budget-monitoring/update', 'BudgetController::optionsHandler');
    $routes->post('staff/budget-monitoring/update', 'BudgetController::updateOfficeBudget');
    
    $routes->options('staff/budget/available-mandates', 'BudgetController::optionsHandler');
    $routes->get('staff/budget/available-mandates', 'BudgetController::getAvailableMandates');
    
    $routes->options('staff/budget/realignment-logs', 'BudgetController::optionsHandler');
    $routes->get('staff/budget/realignment-logs', 'BudgetController::getRealignmentLogs');
    
    $routes->options('staff/budget/financial-meta', 'BudgetController::optionsHandler');
    $routes->get('staff/budget/financial-meta', 'BudgetController::getFinancialMeta');
    
    $routes->options('staff/budget/realign', 'BudgetController::optionsHandler');
    $routes->post('staff/budget/realign', 'BudgetController::executeRealignment');

    // ----------------------------------------------------------------
    // FILE SERVING ROUTES (serve PDFs from writable/uploads)
    // ----------------------------------------------------------------
    $routes->get('files/drafts/(:segment)', 'FileController::serveDraft/$1');
    $routes->get('files/archived/(:segment)', 'FileController::serveArchived/$1');
    // Document Trash Endpoints
    $routes->options('documents/trashed', 'AuthController::handleOptions');
    $routes->get('documents/trashed', 'DocumentTrashController::getTrashedDocuments');
    $routes->options('documents/restore', 'AuthController::handleOptions');
    $routes->post('documents/restore', 'DocumentTrashController::restore');
    $routes->options('documents/permanently-delete', 'AuthController::handleOptions');
    $routes->post('documents/permanently-delete', 'DocumentTrashController::permanentlyDelete');
});