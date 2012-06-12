<?php $this->cache['en']['mod_lti'] = array (
  'accept' => 'Accept',
  'accept_grades' => 'Accept grades from the tool',
  'accept_grades_admin' => 'Accept grades from the tool',
  'accept_grades_admin_help' => 'Specify whether the tool provider can add, update, read, and delete grades associated with instances of this tool type.

Some tool providers support reporting grades back to Moodle based on actions taken within the tool, creating a more integrated
experience.',
  'accept_grades_help' => 'Specify whether the tool provider can add, update, read, and delete grades associated only with this external tool instance.

Some tool providers support reporting grades back to Moodle based on actions taken within the tool, creating a more integrated
experience.

Note that this setting may be overriden in the tool configuration.',
  'action' => 'Action',
  'active' => 'Active',
  'activity' => 'Activity',
  'addnewapp' => 'Enable External Application',
  'addserver' => 'Add new trusted server',
  'addtype' => 'Add external tool configuration',
  'allow' => 'Allow',
  'allowinstructorcustom' => 'Allow instructors to add custom parameters',
  'allowsetting' => 'Allow tool to store 8K of settings in Moodle',
  'always' => 'Always',
  'automatic' => 'Automatic, based on Launch URL',
  'baseurl' => 'Base URL',
  'basiclti' => 'LTI',
  'basiclti_base_string' => 'LTI OAuth Base String',
  'basiclti_endpoint' => 'LTI Launch Endpoint',
  'basiclti_in_new_window' => 'Your activity has opened in a new window',
  'basiclti_parameters' => 'LTI Launch Parameters',
  'basicltiactivities' => 'LTI Activities',
  'basicltifieldset' => 'Custom example fieldset',
  'basicltiintro' => 'Activity Description',
  'basicltiname' => 'Activity Name',
  'basicltisettings' => 'Basic Learning Tool Interoperability Settings',
  'cannot_delete' => 'You may not delete this tool configuration.',
  'cannot_edit' => 'You may not edit this tool configuration.',
  'comment' => 'Comment',
  'configpassword' => 'Default Remote Tool Password',
  'configpreferheight' => 'Default preferred height',
  'configpreferwidget' => 'Set widget as default launch',
  'configpreferwidth' => 'Default preferred width',
  'configresourceurl' => 'Default Resource URL',
  'configtoolurl' => 'Default Remote Tool URL',
  'configtypes' => 'Enable LTI Applications',
  'course_tool_types' => 'Course tool types',
  'courseid' => 'Course id number',
  'coursemisconf' => 'Course is misconfigured',
  'createdon' => 'Created On',
  'curllibrarymissing' => 'PHP Curl library must be installed to use LTI',
  'custom' => 'Custom parameters',
  'custom_config' => 'Using custom tool configuration.',
  'custom_help' => 'Custom parameters are settings used by the tool provider. For example, a custom parameter may be used to display
a specific resource from the provider.

It is safe to leave this field unchanged unless directed by the tool provider.',
  'custominstr' => 'Custom parameters',
  'debuglaunch' => 'Debug Option',
  'debuglaunchoff' => 'Normal launch',
  'debuglaunchon' => 'Debug launch',
  'default' => 'Default',
  'default_launch_container' => 'Default Launch Container',
  'default_launch_container_help' => 'The launch container affects the display of the tool when launched from the course. Some launch containers provide more screen
real estate to the tool, and others provide a more integrated feel with the Moodle environemnt.

* **Default** - Use the launch container specified by the tool configuration.
* **Embed** - The tool is displayed within the existing Moodle window, in a manner similar to most other Activity types.
* **Embed, without blocks** - The tool is displayed within the existing Moodle window, with just the neavigation controls
        at the top of the page.
* **New window** - The tool opens in a new window, occupying all the available space.
        Depending on the browser, it will open in a new tab or a popup window.
        It is possible that browsers will prevent the new window from opening.',
  'delegate' => 'Delegate to Instructor',
  'delete' => 'Delete',
  'delete_confirmation' => 'Are you sure you want to delete this external tool configuration?',
  'deletetype' => 'Delete external tool configuration',
  'display_description' => 'Display activity description when launched',
  'display_description_help' => 'If selected, the activity description (specified above) will display above the tool provider\'s content.

The description may be used to provide additional instructions for launchers of the tool, but it is not required.

The description is never displayed when the tool\'s launch container is in a new window.',
  'display_name' => 'Display activity name when launched',
  'display_name_help' => 'If selected, the activity name (specified above) will display above the tool provider\'s content.

It is possible that the tool provider may also display the title. This option can prevent the activity title from
being displayed twice.

The title is never displayed when the tool\'s launch container is in a new window.',
  'domain_mismatch' => 'Launch URL\'s domain does not match tool configuration.',
  'donot' => 'Do not send',
  'donotaccept' => 'Do not accept',
  'donotallow' => 'Do not allow',
  'edittype' => 'Edit external tool configuration',
  'embed' => 'Embed',
  'embed_no_blocks' => 'Embed, without blocks',
  'enableemailnotification' => 'Send notification emails',
  'enableemailnotification_help' => 'If enabled, students will receive email notification when their tool submissions are graded.',
  'errormisconfig' => 'Misconfigured tool. Please ask your Moodle administrator to fix the configuration of the tool.',
  'extensions' => 'LTI Extension Services',
  'external_tool_type' => 'External tool type',
  'external_tool_type_help' => 'The main purpose of a tool configuration is to set up a secure communication channel between Moodle and the tool provider.
It also provides an opportunity for configuration defaults and setting up additional services provided by the tool.

* **Automatic, based on Launch URL** - This setting should be used in almost all cases. Moodle will select the most appropriate tool configuration
       based on the Launch URL. Tools configured by both an administrator or within this course will be used.
       When the Launch URL is specified, Moodle will provide feedback on whether it recognizes it or not. If Moodle does not recognize the Launch URL,
       you may need to enter the tool configuration details manually.
* **A specific tool type** - By selecting a specific tool type, you can force Moodle to use that tool configuration when communicating with the
       external tool provider. If the Launch URL does not appear to belong to the tool provider, a warning will appear. In some cases, it is not necessary
       to enter a Launch URL when providing a specific tool type (if not launching to a particular resource within the tool provider).
* **Custom configuration** - To setup custom tool configuration on just this instance, show Advanced options, and enter the consumer key and
       shared secret yourself. If you do not have a consumer key and shared secret, you may be able to request them from the tool provider.
       Not all tools require a consumer key and shared secret, in which case the fields may be left blank.

### Tool type editing

Three icons are available after the External tool type dropdown list:

* **Add** - Create a course level tool configuration. All External Tool instances in this course may use the tool configuration.
* **Edit** - Select a course level tool type from the dropdown, then click this icon. The details of the tool configuration may be edited.
* **Delete** - Remove the selected course level tool type.',
  'external_tool_types' => 'External Tool Types',
  'failedtoconnect' => 'Moodle was unable to communicate with the "{$a}" system',
  'filter_basiclti_configlink' => 'Configure your preferred sites and their passwords',
  'filter_basiclti_password' => 'Password is mandatory',
  'filterconfig' => 'LTI administration',
  'filtername' => 'LTI',
  'fixexistingconf' => 'Use an existing configuration for the misconfigured instance',
  'fixnew' => 'New Configuration',
  'fixnewconf' => 'Define a new configuration for the misconfigured instance',
  'fixold' => 'Use Existing',
  'forced_help' => 'This setting has been forced in a course or site level tool configuration. You may not change it from this interface.',
  'force_ssl' => 'Force SSL',
  'force_ssl_help' => 'Selecting this option forces all launches to this tool provider to use SSL.

In addition, all web service requests from the tool provider will use SSL.

If using this option, confirm that this Moodle site and the tool provider support SSL.',
  'global_tool_types' => 'Global tool types',
  'grading' => 'Grade Routing',
  'icon_url' => 'Icon URL',
  'icon_url_help' => 'The icon URL allows the icon that shows up in the course listing for this activity to be modified. Instead of using the default
LTI icon, an icon which conveys the type of activity may be specified.',
  'id' => 'id',
  'imsroleadmin' => 'Instructor,Administrator',
  'imsroleinstructor' => 'Instructor',
  'imsrolelearner' => 'Learner',
  'invalidid' => 'LTI ID was incorrect',
  'launch_in_moodle' => 'Launch tool in moodle',
  'launch_in_popup' => 'Launch tool in a pop-up',
  'launch_url' => 'Launch URL',
  'launch_url_help' => 'The Launch URL indicates the web address of the External Tool, and may contain additional information, such as the resource to show.
If you are unsure what to enter for the Launch URL, please check with the tool provider for more information.

If you have selected a specific tool type, you may not need to enter a Launch URL. If the tool link is used to just launch
into the tool provider\'s system, and not go to a specific resource, this will likely be the case.',
  'launchinpopup' => 'Launch Container',
  'launchinpopup_help' => 'The launch container affects the display of the tool when launched from the course. Some launch containers provide more screen
real estate to the tool, and others provide a more integrated feel with the Moodle environemnt.

* **Default** - Use the launch container specified by the tool configuration.
* **Embed** - The tool is displayed within the existing Moodle window, in a manner similar to most other Activity types.
* **Embed, without blocks** - The tool is displayed within the existing Moodle window, with just the neavigation controls
        at the top of the page.
* **New window** - The tool opens in a new window, occupying all the available space.
        Depending on the browser, it will open in a new tab or a popup window.
        It is possible that browsers will prevent the new window from opening.',
  'launchoptions' => 'Launch Options',
  'lti' => 'LTI',
  'lti:addcoursetool' => 'Grade LTI activities',
  'lti:grade' => 'Grade LTI activities',
  'lti:manage' => 'Edit LTI activities',
  'lti:requesttooladd' => 'Submit a tool to admins for configuration',
  'lti:view' => 'View LTI activities',
  'lti_administration' => 'LTI Administration',
  'lti_errormsg' => 'The tool returned the following error message: "{$a}"',
  'lti_launch_error' => 'An error occured when launching the external tool: ',
  'lti_launch_error_tool_request' => '<p>
To submit a request for an administrator to complete the tool configuration, click <a href="{$a->admin_request_url}" target="_top">here</a>.
</p>',
  'lti_launch_error_unsigned_help' => '   <p>
        This error may be a result of a missing consumer key and shared secret for the tool provider.
    </p>
    <p>
        If you have a consumer key and shared secret, you may enter it when editing the external tool instance (make sure advanced options are visible).<br />
        Alternatively, you may create a course level tool provider configuration <a href="{$a->course_tool_editor}">here</a>.
    </p>',
  'lti_tool_request_added' => 'Tool configuration request successfully submitted. You may need to contact an administrator to complete the tool configuration.',
  'lti_tool_request_existing' => 'A tool configuration for the tool domain has already been submitted.',
  'main_admin' => 'General help',
  'main_admin_help' => 'External tools allow Moodle users to seamlessly interact with learning resources hosted remotely. Through a special
launch protocol, the remote tool will have access to some general information about the launching user. For example,
the institution name, course id, user id, and other information such as the user\'s name or e-mail address.

Tool types listed on this page are separated into three categories:

* **Active** - These tool providers have been approved and configured by an administrator. They can be used from within any
        course on this Moodle instance. If a consumer key and shared secret are entered, a trust relationship is established
        between this Moodle instance and the remote tool, providing a secure communication channel.
* **Pending** - These tool providers came in through a package import, but have not been configured by an administrator.
        Instructors may still use tools from these providers if they have a consumer key and shared secret, or if none is required.
* **Rejected** - These tools providers are flagged as ones which an administrator has no intention of making available to the entire
        Moodle instance. Instructors may still use tools from these providers if they have a consumer key and shared secret, or if none is required.',
  'miscellaneous' => 'Miscellaneous',
  'misconfiguredtools' => 'Misconfigured tool instances were detected',
  'missingparameterserror' => 'The page is misconfigured: "{$a}"',
  'module_class_type' => 'Moodle module type',
  'modulename' => 'External Tool',
  'modulename_help' => 'External tools allow Moodle users to interact with learning resources and activities on other web sites. For instance, an
external tool could provide access to a new activity type or learning materials from a publisher.

To setup an external tool instance a tool provider which supports LTI (Learning Tools Interoperability) is required.
If you find a tool provider which supports LTI, they should be able to provide instructions on how to configure the
external tool instance. Additionally, tool types configured by a site administrator will also be available for use.

External tools differ from URL resources in a few ways:
* **Context aware** - External tools have access to information about the user who launched the tool, such as
        insitution, course, name, and other information.
* **Deep integration** - External tools support reading, updating, and deleting grades associated with the activity instance. More integration points
        are planned for future releases.
* **Security** - External tool configurations create a trust relationship between Moodle and the tool provider, allowing secure communication
        between them.',
  'modulenameplural' => 'basicltis',
  'modulenamepluralformatted' => 'LTI Instances',
  'never' => 'Never',
  'new_window' => 'New window',
  'no_lti_configured' => 'There are no active External Tools configured.',
  'no_lti_pending' => 'There are no pending External Tools.',
  'no_lti_rejected' => 'There are no rejected External Tools.',
  'noattempts' => 'No attempts have been made on this tool instance',
  'noltis' => 'There are no lti instances',
  'noservers' => 'No servers found',
  'notypes' => 'There are currently no LTI tools setup in Moodle. Click the Install link above to add some.',
  'noviewusers' => 'No users were found with permissions to use this tool',
  'optionalsettings' => 'Optional settings',
  'organization' => 'Organization details',
  'organizationdescr' => 'Organization Description',
  'organizationid' => 'Organization ID',
  'organizationid_help' => 'A unique identifier for this Moodle instance. Typically, the DNS name of the organization is used.

If this field is left blank, the host name of this Moodle site will be used as the default value.',
  'organizationurl' => 'Organization URL',
  'organizationurl_help' => 'The base URL of this Moodle instance.

If this field is left blank, a default value will be used based on the site configuration.',
  'pagesize' => 'Submissions shown per page',
  'password' => 'Shared Secret',
  'password_admin' => 'Shared Secret',
  'password_admin_help' => 'The shared secret can be thought of as a password used to authenticate access to the tool. It should be provided
along with the consumer key from the tool provider.

Tools which do not require secure communication from Moodle and do not provide additional services (such as grade reporting)
may not require a shared secret.',
  'password_help' => 'For pre-configured tools, it is not necessary to enter a shared secret here, as the shared secret will be
provided as part of the configuration process.

This field should be entered if creating a link to a tool provider which is not already configured.
If the tool provider is to be used more than once in this course, adding a course tool configuration is a good idea.

The shared secret can be thought of as a password used to authenticate access to the tool. It should be provided
along with the consumer key from the tool provider.

Tools which do not require secure communication from Moodle and do not provide additional services (such as grade reporting)
may not require a shared secret.',
  'pending' => 'Pending',
  'pluginadministration' => 'LTI administration',
  'pluginname' => 'LTI',
  'preferheight' => 'Preferred Height',
  'preferwidget' => 'Prefer Widget Launch',
  'preferwidth' => 'Preferred Width',
  'press_to_submit' => 'Press to launch this activity',
  'privacy' => 'Privacy',
  'quickgrade' => 'Allow quick grading',
  'quickgrade_help' => 'If enabled, multiple tools can be graded on one page. Add grades and comments then click the "Save all my feedback" button to save all changes for that page.',
  'redirect' => 'You will be redirected in few seconds. If you are not, press the button.',
  'reject' => 'Reject',
  'rejected' => 'Rejected',
  'resource' => 'Resource',
  'resourcekey' => 'Consumer Key',
  'resourcekey_admin' => 'Consumer Key',
  'resourcekey_admin_help' => 'The consumer key can be thought of as a username used to authenticate access to the tool.
It can be used by the tool provider to uniquely identify the Moodle site from which users launch into the tool.

The consumer key must be provided by the tool provider. The method of obtaining a consumer key varies between
tool providers. It may be an automated process, or it may require a dialogue with the tool provider.

Tools which do not require secure communication from Moodle and do not provide additional services (such as grade reporting)
may not require a resource key.',
  'resourcekey_help' => 'For pre-configured tools, it is not necessary to enter a resource key here, as the consumer key will be
provided as part of the configuration process.

This field should be entered if creating a link to a tool provider which is not already configured.
If the tool provider is to be used more than once in this course, adding a course tool configuration is a good idea.

The consumer key can be thought of as a username used to authenticate access to the tool.
It can be used by the tool provider to uniquely identify the Moodle site from which users launch into the tool.

The consumer key must be provided by the tool provider. The method of obtaining a consumer key varies between
tool providers. It may be an automated process, or it may require a dialogue with the tool provider.

Tools which do not require secure communication from Moodle and do not provide additional services (such as grade reporting)
may not require a resource key.',
  'resourceurl' => 'Resource URL',
  'return_to_course' => 'Click <a href="{$a->link}" target="_top">here</a> to return to the course.',
  'saveallfeedback' => 'Save all my feedback',
  'secure_icon_url' => 'Secure Icon URL',
  'secure_icon_url_help' => 'Similar to the icon URL, but used if the user accessing Moodle securely through SSL. The main purpose for this field is to prevent
the browser from warning the user if the underlying page was accessed over SSL, but requesting to show an unsecure image.',
  'secure_launch_url' => 'Secure Launch URL',
  'secure_launch_url_help' => 'Similar to Launch URL, but used instead of the launch url if high security is required. Moodle will use the
secure launch URL instead of the launch URL if the Moodle site is accessed through SSL, or if the tool configuration
is set to always launch through SSL.

The Launch URL may also be set to an https address to force launching through SSL, and this field may be left blank.',
  'send' => 'Send',
  'setdefault' => 'Set a default value for the professor if delegating',
  'setupbox' => 'LTI Tool Setup Box',
  'setupoptions' => 'Setup Options',
  'share_email' => 'Share launcher\'s email with the tool',
  'share_email_admin' => 'Share launcher\'s email with tool',
  'share_email_admin_help' => 'Specify whether the e-mail address of the user launching the tool will be shared with the tool provider.
The tool provider may need launcher\'s e-mail addresses to distinguish users with the same name in the UI, or send e-mails
to users based on actions within the tool.',
  'share_email_help' => 'Specify whether the e-mail address of the user launching the tool will be shared with the tool provider.
The tool provider may need launcher\'s e-mail addresses to distinguish users with the same name, or send e-mails
to users based on actions within the tool.

Note that this setting may be overriden in the tool configuration.',
  'share_name' => 'Share launcher\'s name with the tool',
  'share_name_admin' => 'Share launcher\'s name with tool',
  'share_name_admin_help' => 'Specify whether the full name of the user launching the tool should be shared with the tool provider.
The tool provider may need launchers\' names to show meaningful information within the tool.',
  'share_name_help' => 'Specify whether the full name of the user launching the tool should be shared with the tool provider.
The tool provider may need launchers\' names to show meaningful information within the tool.

Note that this setting may be overriden in the tool configuration.',
  'share_roster' => 'Allow the tool to access this course\'s roster',
  'share_roster_admin' => 'Tool may access course roster',
  'share_roster_admin_help' => 'Specify whether the tool can access the list of users enrolled in courses from which this tool type is launched.',
  'share_roster_help' => 'Specify whether the tool can access the list of users enrolled in this course.

Note that this setting may be overriden in the tool configuration.',
  'show_in_course' => 'Show tool type when creating tool instances',
  'show_in_course_help' => 'If selected, this tool configuration will appear in the "External tool type" dropdown when instructors
configure external tools within courses.

In most cases, this option does not need to be selected. Instructors can use this tool configuration
based on the Launch URL matching the Tool base URL, which is the preferred method.

The only case in which this option should be selected is if the tool configuration is just intended for single sign on.
For example, if all launches to the tool provider just take the user to a landing page instead of to a specific resource.',
  'size' => 'Size parameters',
  'submission' => 'Submission',
  'toggle_debug_data' => 'Toggle Debug Data',
  'tool_config_not_found' => 'Tool configuration not found for this URL.',
  'tool_settings' => 'Tool Settings',
  'toolsetup' => 'External Tool Configuration',
  'toolurl' => 'Tool Base URL',
  'toolurl_help' => 'The tool base URL is used to match tool launch URLs to the correct tool configuration. Prefxing the URL with http(s) is optional.

Additionally, the base URL is used as the launch URL if a launch URL is not specified in the external tool instance.

<table>
    <thead>
        <tr>
            <td>
                <b>Base URL</b>
            </td>
            <td>
                <b>Matches</b>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                tool.com
            </td>
            <td>
                tool.com, tool.com/quizzes, tool.com/quizzes/quiz.php?id=10, www.tool.com/quizzes
            </td>
        </tr>
        <tr>
            <td>
                www.tool.com/quizzes
            </td>
            <td>
                tool.com/quizzes, tool.com/quizzes/take.php?id=10, www.tool.com/quizzes
            </td>
        </tr>
        <tr>
            <td>
                quiz.tool.com
            </td>
            <td>
                quiz.tool.com, quiz.tool.com/take.php?id=10
            </td>
        </tr>
    </tbody>
</table>

If two different tool configurations are for the same domain, the most specific match will be used.',
  'typename' => 'Tool Name',
  'typename_help' => 'The tool name is used to identify the tool provider within Moodle. The name entered will be visible
to instructors when adding external tools within courses.',
  'types' => 'Types',
  'update' => 'Update',
  'using_tool_configuration' => 'Using tool configuration: ',
  'validurl' => 'A valid URL must start with http(s)://',
  'viewsubmissions' => 'View submissions and grading screen',
);