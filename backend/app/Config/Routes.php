<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function($routes) {
    $routes->post('login',    'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('logout',    'AuthController::logout');

    // --- CLOUDFLARE R2 STORAGE ---
    $routes->post('storage/ticket', 'StorageController::getUploadTicket');
    $routes->options('storage/ticket', 'AuthController::handleOptions');

    // --- SUBMISSIONS ---
    $routes->post('submit-activity-design', 'SubmissionController::submitActivityDesign');
    $routes->post('submit-accomplishment',  'SubmissionController::submitAccomplishment');
    $routes->post('update-submission',      'SubmissionController::updateSubmission');

    // --- MONITORING (GAD Staff tracker) ---
    $routes->get('activity-designs',       'SubmissionController::listActivityDesigns');
    $routes->get('accomplishment-reports', 'SubmissionController::listAccomplishmentReports');
    $routes->get('submission-tracker',     'SubmissionController::submissionTracker');

    // --- APPROVED DESIGNS DROPDOWN (AR form) ---
    $routes->get('approved-designs', 'SubmissionController::approvedDesigns');

    // --- OFFICE UNITS DROPDOWN ---
    $routes->get('office-units', 'SubmissionController::listOfficeUnits');

    // --- ARCHIVE ---
    $routes->post('archive',           'SubmissionController::archiveDocument');
    $routes->get('archived-designs',   'SubmissionController::archivedDesigns');
    $routes->get('archived-reports',   'SubmissionController::archivedReports');

    // --- MAINTENANCE ---
    $routes->post('cleanup-drafts',    'SubmissionController::cleanupDrafts');
    $routes->get('cleanup-drafts',     'SubmissionController::cleanupDrafts'); // allow GET for task scheduler

    // --- CORS PREFLIGHT ---
    $routes->options('login',                  'AuthController::handleOptions');
    $routes->options('register',               'AuthController::handleOptions');
    $routes->options('logout',                 'AuthController::handleOptions');
    $routes->options('submit-activity-design', 'SubmissionController::handleOptions');
    $routes->options('submit-accomplishment',  'SubmissionController::handleOptions');
    $routes->options('update-submission',      'SubmissionController::handleOptions');
    $routes->options('activity-designs',       'SubmissionController::handleOptions');
    $routes->options('accomplishment-reports', 'SubmissionController::handleOptions');
    $routes->options('submission-tracker',     'SubmissionController::handleOptions');
    $routes->options('approved-designs',       'SubmissionController::handleOptions');
    $routes->options('office-units',            'SubmissionController::handleOptions');
    $routes->options('archive',                'SubmissionController::handleOptions');
    $routes->options('archived-designs',       'SubmissionController::handleOptions');
    $routes->options('archived-reports',       'SubmissionController::handleOptions');
    $routes->options('cleanup-drafts',         'SubmissionController::handleOptions');
});