<?php

require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot . '/my/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

redirect_if_major_upgrade_required();

require_login();


$hassiteconfig = has_capability('moodle/site:config', context_system::instance());
if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect(new moodle_url('/admin/index.php'));
}

$context = context_system::instance();

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page(null, MY_PAGE_PUBLIC, MY_PAGE_COURSES)) {
    throw new Exception('mymoodlesetup');
}

// Start setting up the page.
$PAGE->set_context($context);
$PAGE->set_url('/my/mastercourse.php');
$PAGE->add_body_classes(['limitedwidth', 'page-mycourses']);
$PAGE->set_pagelayout('mycourses');

$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title('Master Course');
$PAGE->set_heading('Master Course');

if (isguestuser()) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = null;
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :)
    $strguest = get_string('guest');
    $pagetitle = "$strmymoodle ($strguest)";

} else {        // We are trying to view or edit our own My Moodle page
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $pagetitle = $strmymoodle;
}


echo $OUTPUT->header();
$mastercourse = (array)$DB->get_records_sql('SELECT  cm.id,cm.name ,u.username  FROM `mdl_user` as u 
                                                INNER JOIN `mdl_user_enrol_mastercourse` as uem
                                                ON `u`.`id` = `uem`.id_user 
                                                INNER JOIN `mdl_course_master` as cm
                                                ON `cm`.`id` = uem.id_mastercourse
                                                WHERE u.id ='.$USER->id);

$templatecontext = (object)[
    'mastercourse' => array_values($mastercourse),
    'viewcourselistuser' => new moodle_url('/local/mastercourse/viewcourselistuser.php'),
    'createmastercoursecourse' => new moodle_url('/local/mastercourse/createmastercourse.php'),
];
echo $OUTPUT->render_from_template('local_mastercourse/listmastercourse', $templatecontext);

echo $OUTPUT->footer();

// Trigger dashboard has been viewed event.
$eventparams = array('context' => $context);
$event = \core\event\mycourses_viewed::create($eventparams);
$event->trigger();
