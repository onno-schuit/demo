Linkedin Oauth 2.0 Authentication Plugin
===========================================================

* Credits:

This authentication plugin has been created based on the code written by:
https://github.com/mahmudahsan using code published here: 
https://github.com/mahmudahsan/Linkedin---Simple-integration-for-your-website

Using Moodle OAuth examples by Jerome Mouneyrac:
http://moodle.org/plugins/view.php?plugin=auth_googleoauth2


* Requirements:
A Linked in Account
A Linked in integration Key / Secret
Some custom code in your theme or the linkedin block

* What it does (using the linkedin block):
When installed correctly a user can log in using their linked in account.

1. The user clicks the login with linkedin button
2. A popup opens that redirects to the linkedin page asking for your linkedin username/pass and your
approval to give moodle access to your profile. If the user was already logged into linkedin in some
other browsers window the popup only asks permission to login using linkedin
3. When the users finishes the linked in auth the Moodle page reloads and redirects the user the a form 
asking for the users email. 
4. When finished the users is logged into Moodle and profile information is shown in the linkedin block.

Profile info retreived from LinkedIn:
- firstname, lastname
- current job
- country
- city 
- profile picture 

* LinkedIn auth plugin installation

1. Add the auth plugin to /auth/linkedin
2. In Moodle go to the plugins page for authentication and enable linkedin
3. visit https://www.linkedin.com/secure/developer and click Add New Application.
4. Enter your Moodle website details, most important field: Integration URL, use the wwwroot of your Moodle installation
5. Once received from LinkedIn Enter you key / secret to the LinkedIn authentication plugin settings page in Moodle

* Linkedin block
1. Install the linkedin block 
2. Go to the Siteadmin -> notifications to install the block
3. Add the block to the moodle frontpage


