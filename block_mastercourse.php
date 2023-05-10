<?php



class block_mastercourse extends block_base
{
    function init()
    {
        $this->title = get_string('pluginname', 'block_mastercourse');
        
    }

    function has_config()
    {
        return true;
    }

    function get_content()
    {   
        // require_once(__DIR__ . '/../../config.php');
        // // require_once($CFG->dirroot . '/config.php');
        // require_once($CFG->dirroot . '/my/lib.php');
        // require_once($CFG->dirroot . '/course/lib.php');
        // global $DB;
        // $this->title = get_string('pluginname', 'block_mastercourse');
        // if (isguestuser()) {  // Force them to see system default, no editing allowed
        //     // If guests are not allowed my moodle, send them to front page.
        //     if (empty($CFG->allowguestmymoodle)) {
        //         redirect(new moodle_url('/', array('redirect' => 0)));
        //     }
        
        //     $userid = null;
        //     $USER->editing = $edit = 0;  // Just in case
        //     $context = context_system::instance();
        //     $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :)
        //     $strguest = get_string('guest');
        //     $pagetitle = "$strmymoodle ($strguest)";
        
        // } else {        // We are trying to view or edit our own My Moodle page
        //     $userid = $USER->id;  // Owner of the page
        //     $context = context_user::instance($USER->id);
        //     $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
        //     $pagetitle = $strmymoodle;
        // }
        // $mastercourse = (array)$DB->get_records_sql('SELECT  cm.id,cm.name ,u.username  FROM `mdl_user` as u 
        //                                         INNER JOIN `mdl_user_enrol_mastercourse` as uem
        //                                         ON `u`.`id` = `uem`.id_user 
        //                                         INNER JOIN `mdl_course_master` as cm
        //                                         ON `cm`.`id` = uem.id_mastercourse
        //                                         WHERE u.id ='.$USER->id);
     
        
        if ($this->content !== null) {
            return $this->content;
        }

      
        $url = 'mastercourse.php';
        $content = '<input type="button" class="btn btn-primary" value="See Your Mastercourse" onclick="location.href=\'' . $url . '\'">';


        $this->content = new stdClass();
        $this->content->text = $content;
       

        return $this->content;
    }
}
