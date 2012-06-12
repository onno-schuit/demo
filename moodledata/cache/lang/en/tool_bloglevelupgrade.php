<?php $this->cache['en']['tool_bloglevelupgrade'] = array (
  'bloglevelupgradedescription' => '<p>This site has recently been upgraded to Moodle 2.0.</p>
<p>Blog visibility was simplified in 2.0, but your site still uses one of the old visibility types. </p>
<p>To preserve the course-based or group-based visibility of the blog entries on your site, you need to run the following upgrade script, which will create a special "blog" type forum in each course whose enrolled users have posted blog entries, and will copy these blog entries in this special forum. </p>
<p>Blogs will then be entirely switched off at the site level. No blog entries will be deleted in the process.</p>
<p>You can run the script by visiting <a href="{$a->fixurl}">the blog level upgrade page</a>.</p>',
  'bloglevelupgradeinfo' => 'Blog visibility was simplified in 2.0, but your site still uses one of the old visibility types. To preserve the course-based or group-based visibility of the blog entries on your site, the following upgrade script will create a special "blog" type forum in each course whose enrolled users have posted blog entries, and will copy these blog entries in this special forum. Blogs will then be entirely switched off at the site level. No blog entries will be deleted in the process.',
  'bloglevelupgradeprogress' => 'Conversion progress: {$a->userscount} users reviewed, {$a->blogcount} entries converted.',
  'pluginname' => 'Blog visibility upgrade',
);