<?php

// included from ousearch/searchlib.php

/**
 * All modules should have an extension of the ousbase class below and should include the ousearch/searchlib.php
 * like so: include_once(dirname(__FILE__).'/../../local/ousearch/searchlib.php');
 * 
 */
 
 
 
abstract class ousbase {
    
    static protected $_type; // set this to the name of your module, e.g. 'forum'
    static protected $_has_group_id = true;
    static $login_required = true;

    /**
     * Obtains a search document given the ousearch parameters.
     * @param object $document Object containing fields from the ousearch documents table
     * @return mixed False if object can't be found, otherwise object containing the following
     *   fields: ->content, ->title, ->url, ->activityname, ->activityurl
     */
    abstract static public function get_document($document);


    static public function get_group_ids($user_id=false) {
        static $ids_as_string;

        global $DB, $USER;

        $user_id = $user_id ? $user_id : $USER->id;
        if (!isset($ids_as_string[$user_id])) {
            $results = $DB->get_records_select('groups_members', 'userid=?', array($USER->id));
            $ids = array(-9999); // something non existent
            foreach ($results as $result) {
                $ids[] = (int)$result->groupid;
            }
            $ids_as_string[$user_id] = join(',', $ids);
        }
        return $ids_as_string[$user_id];    
    }
    
    /**
     * Returns group id for a search document if we're inside a course module
     * 
     * @return
     */
    static public function get_current_group_id() {
        if (!isset(static::$_has_group_id)) {
            return null;
        }
        
        global $CFG, $course, $SESSION, $cm;
        $currentgroup = null;
        if (isset($_REQUEST['group'])) {
            // group from REQUEST
            $currentgroup = (int)$_REQUEST['group'];
        } elseif (isset($cm->id)) {
            // group from course module
            $currentgroup = groups_get_activity_group($cm);
        }
        return $currentgroup;        
    }
    
    /**
     * Returns an instance of local_ousearch_document; Required $post properties: ->id, ->userid; Optional: ->groupid
     * 
     * @param object $post
     * @param object $cm
     * @return local_ousearch_document
     */
    static public function get_search_document($post, $cm=null) {
        global $DB;
        // Set up 'search document' to refer to this post
        $doc = new local_ousearch_document();
        if (!$cm) {
            $cm = new stdClass();
            $cm->id = 0;
            $cm->course = 0;
        }
        $doc->init_module_instance(static::$_type, $cm);
        $group_id = isset($post->group_id) ? $post->group_id : null;
        if (!$group_id) {
            $group_id = self::get_current_group_id();
        }
        if(!empty($group_id)) {
            $doc->set_group_id($group_id);
        }
        $doc->set_user_id($post->userid);
        $doc->set_int_refs($post->id);
        return $doc;
    }

    
    static public function get_type() {
        return static::$_type;
    }

    /**
     * Returns true if user must be logged in to view search results for this 
     * module, otherwise false.
     * 
     * @return boolean
     */
    static public function login_required() {
        return static::$login_required;        
    } // function login_required

    abstract static public function update($post, $cm=null);
    
    static public function title_prefix($doctype=null) {
        $doctype = $doctype ? $doctype : static::$_type;
        return get_string('doctype_' . $doctype, 'block_tl_search'). ': ';
    }
    
    
}

abstract class ousbase_file extends ousbase {
    
    static protected function _filesize($size) {
        $mod = 1024;
        
        $units = explode(' ','bytes KB MB GB TB PB');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

}

class ousforum extends ousbase {
    
    static protected $_type = 'forum';

    static public function get_document($document) {
        global $DB, $CFG, $USER;
        //$post = $DB->get_record_select('forum_posts', 'id=?', array('id' => $document->intref1));

        /* if a forum is in groupmode, you must be a groupmember to view the particular results */
        $group_ids = self::get_group_ids();
        $post = $DB->get_record_sql("SELECT p.*, NOT(d.groupid < 1 OR d.groupid IN ( :group_ids )) AS hide 
            FROM {forum_posts} AS p, {forum_discussions} AS d
            WHERE p.discussion = d.id
            AND p.id = :post_id",
            array('group_ids' => $group_ids, 'post_id' => $document->intref1));

        if (!$post) { return false; }
        $result = (object)array(
            'hide' => $post->hide,
            'content' => $post->message,
            'title' => self::title_prefix('forumpost') . $post->subject,
            'url' => $CFG->wwwroot . sprintf('/mod/forum/discuss.php?d=%d#p%d', $post->discussion, $post->id),
            'activityname' => 'Forumbericht',
            'activityurl' => '#',
            'data' => $post
        );
        return $result;
    }

    static public function update($post, $cm=null) {
        // Get search document
        $doc = self::get_search_document($post, $cm);
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->subject, $post->message, time());
        return true;
        
    }
    
}

class ousforum_file extends ousbase_file {
    
    static protected $_type = 'forum_file';

    static public function get_document($document) {
        global $DB, $CFG;

        $post = $DB->get_record_select('files', 'id=?', array('id' => $document->intref1));
        if ($post->filename == '.') { return false; } // MIND U: removes result from DB
        
        if (!$post) { return false; }
        $result = (object)array(
            'content' => $post->filename . ' - ' . self::_filesize($post->filesize),
            'title' => self::title_prefix('forumfile') . $post->filename,
            'url' => $CFG->wwwroot . sprintf('/pluginfile.php/%d/mod_forum/attachment/%d/%s', $post->contextid, $post->itemid, $post->filename),
            'activityname' => 'Forumfile',
            'activityurl' => '#',
            'data' => $post
        );
        return $result;
    }

    static public function update($post, $cm=null) {
        // Get search document
        $doc = self::get_search_document($post, $cm);
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->subject, $post->filename, time());
        return true;
        
    }
    
}

class ouscalendar extends ousbase {
    
    static protected $_type = 'calendar';

    static public function get_document($document) {
        global $DB;
        $post = $DB->get_record_select('event', 'id=?', array('id' => $document->intref1));
        $url = sprintf('http://thnklnk.debian.local/calendar/view.php?course=%d&view=day&cal_d=%d&cal_m=%d&cal_y=%d#event_%d',
            $post->courseid, date('d', $post->timestart), date('m', $post->timestart), date('Y', $post->timestart),
            $post->id);
        $result = (object)array(
            'content' => $post->description,
            'title' => self::title_prefix('calendar') . $post->name,
            'url' => $url,
            'activityname' => 'Activiteit',
            'activityurl' => '#', 
            'data' => $post
        );
        return $result;
    }

    static public function update($post, $cm=null) {
        // calendar doesnt have a course module instance, so create a 'fake' one
        $cm = new stdClass();
        $cm->id = 0;
        $cm->course = $post->courseid;
        
        // Get search document
        $doc = self::get_search_document($post, $cm);
        
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->name, $post->description, time());
        return true;
        
    }
    
}

class ouscoursemodule extends ousbase {
    
    static protected $_type = 'coursemodule';

    static public function get_document($document) {
        global $DB, $CFG;
        $result = array();
        // join the course_modules table with the module's table e.g. mdl_course_modules INNER JOIN mdl_page
        $sql = sprintf("SELECT m.* FROM {course_modules} cm
            INNER JOIN {%s} m ON (cm.id=%d AND instance=m.id)",
            $document->stringref,
            $document->coursemoduleid
        );
        $post = $DB->get_record_sql($sql);
        if (!$post) { return false; }
        
        $result['title'] = self::title_prefix($document->stringref) . $post->name;
        $result['content'] = $post->intro;
        $result['url'] = $CFG->wwwroot . sprintf('/mod/%s/view.php?id=%d', $document->stringref, $document->coursemoduleid);
        $result['activityname'] = $document->stringref;
        $result['activityurl'] = '#';
        $result['data'] = $post;
        
        return (object)$result;
    }

    static public function update($post, $cm=null) {
        $cm = new stdClass();
        $cm->id = $post->coursemodule;
        $cm->course = $post->course;

        $doc = self::get_search_document($post, $cm);
        $doc->stringref = $post->modulename;
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->name, $post->intro, time());
        return true;
        
    }
    
}

class ouscoursemodule_file extends ousbase_file {
    
    static protected $_type = 'coursemodule_file';

    static public function get_document($document) {
        global $DB, $CFG;
        $post = $DB->get_record_select('files', 'id=?', array('id' => $document->intref1));
        if ($post->filename == '.') { return false; } // MIND U: removes result from DB
        
        if (!$post) { return false; }
        $result = (object)array(
            'content' => $post->filename . ' - ' . self::_filesize($post->filesize),
            'title' => self::title_prefix('resourcefile') . $post->filename,
            'url' => $CFG->wwwroot . sprintf('/pluginfile.php/%d/mod_%s/content/%d/%s', 
                $post->contextid, $document->stringref ? $document->stringref : 'resource', 1, $post->filename),
            'activityname' => 'Forumfile',
            'activityurl' => '#',
            'data' => $post
        );
        return $result;
    }

    static public function update($post, $cm=null) {
        $cm = new stdClass();
        $cm->id = $post->coursemodule;
        $cm->course = $post->course;

        $doc = self::get_search_document($post, $cm);
        $doc->stringref = $post->modulename;
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->subject, $post->filename, time());
        return true;
        
    }
    
}

class oususerenrolment extends ousbase {
    
    static protected $_type = 'userenrolment';
    static protected $_has_group_id = false;

    static public function get_document($document) {
        global $DB, $CFG, $USER;

        // join the course_modules table with the module's table e.g. mdl_course_modules INNER JOIN mdl_page
/*
        // Can't do this: there is no "fixed" group_id for each user (unlike, say, the forum posts)
        $sql = "SELECT u.* FROM {user} AS u, {groups_members} AS gm
                WHERE u.id= :user_id
                AND u.id = gm.userid 
                AND gm.groupid IN (
                    SELECT groupid 
                    FROM {groups_members} 
                    WHERE userid = :current_user_id)";

        $post = $DB->get_record_sql($sql, array('user_id' => $document->userid,
                                                'current_user_id' => $USER->id));
*/       

        $sql = "SELECT u.* FROM {user} AS u WHERE u.id= :user_id";
        $post = $DB->get_record_sql($sql, array('user_id' => $document->userid));
        $result = array(
            'title' => self::title_prefix() . join(' ', array($post->firstname, $post->lastname)),
            'content' => join(', ', array($post->email, $post->city, $post->phone1, $post->description)),
            'url' => $CFG->wwwroot . sprintf('/user/profile.php?id=%d', $post->id),
            'activityname' => 'userenrolment',
            'activityurl' => '#',
            'data' => $post
             
        );
        
        return (object)$result;
    }

    static public function update($post, $cm=null) {
        global $DB;
        // userenrollment doesnt have a course module instance, so create a 'fake' one
        $cm = new stdClass();
        $cm->id = 0;
        $cm->course = $post->courseid;

        $doc = self::get_search_document($post, $cm);
        $user = $DB->get_record_select('user', 'id=?', array('id' => $post->userid));
        $sql = "SELECT t.name FROM {tag} t
            INNER JOIN {tag_instance} ti ON (t.id=ti.tagid AND ti.itemtype='user')
            WHERE ti.itemid=?";
        $tags = $DB->get_records_sql($sql, array('userid' => $post->userid));
        $tags_string = array();
        foreach ($tags as $tag) {
            $tags_string[] = $tag->name;
        }
        $tags_string = join(', ', $tags_string) . '. ';
        
        $name = join(' ', array($user->firstname, $user->lastname)); 
        
        $extrastrings = array(
            $tags_string,
            $user->email,
            $user->icq,
            $user->skype,
            $user->yahoo,
            $user->aim,
            $user->msn,
            $user->phone1,
            $user->phone2,
            $user->institution,
            $user->department,
            $user->address,
            $user->city,
            $user->country
        );
        
    
        // Update information about this post (works ok for add or edit)
        $doc->update($name, $user->description, time(), null, $extrastrings);
        return true;
        
    }
    
    /**
     * Updates all search document instances of this user's enrolment, probably when user has changed his profile
     * 
     * @param int $userid
     * @return void
     */
    static public function update_all_instances($userid) {
        global $DB;

        # retrieve all search documents of this user's enrolment        
        $instances = $DB->get_records_select('local_ousearch_documents', 
            'plugin=? AND userid=?', array('mod_userenrolment', $userid));
        
        # update all instances
        foreach ($instances as $instance) {
            $post->id = $instance->intref1;
            $post->userid = $userid;
            $post->courseid = $instance->courseid;
            self::update($post);
        } 
        
    }
    
}


class oussource_file extends ousbase_file {
    
    static protected $_type = 'source_file';

    static public function get_document($document) {
        global $DB, $CFG;
        $group_ids = self::get_group_ids();
        $post = $DB->get_record_sql("SELECT f.*,sf.*,(sf.group_id NOT IN ($group_ids)) AS hide FROM {files} f
            INNER JOIN {source_file} sf ON (f.id=sf.files_id)
            WHERE f.id=?", array('id' => $document->intref1));
        if ($post->filename == '.') { return false; } // MIND U: removes result from DB
        
        if (!$post) { return false; }
        $result = (object)array(
            'hide' => $post->hide,
            'content' => $post->filename . ' - ' . self::_filesize($post->filesize) . ' ' . $post->description,
            'title' => self::title_prefix('source_file') . $post->filename,
            'url' => $CFG->wwwroot . sprintf('/pluginfile.php/%d/mod_source/file/%d/%d/%s', 
                $post->contextid, $post->group_id, $post->path_id, $post->filename),
            'activityname' => 'Source file',
            'activityurl' => '#',
            'data' => $post
        );
        return $result;
    }

    static public function update($post, $cm=null) {

        $doc = self::get_search_document($post, $cm);
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->filename, $post->description, time());
        return true;
        
    }
    
}

class oustodotwo extends ousbase {
    
    static protected $_type = 'todotwo';

    static public function get_document($document) {
        global $DB, $CFG;
        $group_ids = self::get_group_ids();
        $post = $DB->get_record_sql("SELECT i.*, (i.group_id NOT IN ($group_ids)) AS hide FROM {todotwo_items} i
            WHERE i.id=?", array('id' => $document->intref1));
        
        if (!$post) { return false; }
        $result = (object)array(
            'hide' => $post->hide,
            'content' => $post->note,
            'title' => self::title_prefix('todotwo') . $post->title,
            'url' => '#',
            'activityname' => 'Activity',
            'activityurl' => '#',
            'data' => $post
        );
        return $result;
    }

    static public function update($post, $cm=null) {

        $doc = self::get_search_document($post, $cm);
    
        // Update information about this post (works ok for add or edit)
        $doc->update($post->title, $post->description, time());
        return true;
        
    }
    
}

function get_registered_ousmodules() {
    $result = array();
    foreach (get_declared_classes() as $class) {
        if (is_subclass_of($class, 'ousbase') && $class!='ousbase_file') {
            $result[] = $class::get_type();
        }
    }
    $result[] = 'wiki';
    $result[] = 'blog';
    return $result;
}

// unfortunately the OUSearch module needs a [module]_ousearch_get_document() function for each document type;
// and also a [module]_ousearch_login_required() function
foreach (get_registered_ousmodules() as $ousmodule_name) {
    if (method_exists('ous' . $ousmodule_name, 'get_document')) {
        // declare xxx_ousearch_get_document() functions
        $functionname = $ousmodule_name . '_ousearch_get_document';
        $declare_function = sprintf('function %s($document) { return ous%s::get_document($document); }', 
            $functionname, $ousmodule_name);
        eval($declare_function);
        // declare xxx_ousearch_get_login_required() functions
        $functionname = $ousmodule_name . '_ousearch_login_required';
        $declare_function = sprintf('function %s($document) { return ous%s::get_document($document); }', 
            $functionname, $ousmodule_name);
        eval($declare_function);
    }
}

function ouwiki_ousearch_login_required() {
    return false;
}

?>