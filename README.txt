=== UnPointZero Filemanager ===
Contributors: UnPointZero
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JMQUZKHDUR3TG
Tags: downloads,download,manager,files,file,right,file access,control,members,member,groups,group,filemanager,file manager,secure
Requires at least: 3.9.2
Tested up to: 4.1
Stable tag: 0.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

UnPointZero FileManager is a plugin that provides File Download Links or File Management for public or registered users download.

== Description ==
UnPointZero FileManager is a plugin that provides File Download Links or File Management for public or registered users download (group management or user specific).

Public & Private files:

*	Create Folders to easily manage your uploaded files
*	Display a FileManager on WordPress frontend (see screenshots)
*	Use shortcode button to quickly add single files to your websites pages

Private files

*	Manage Groups into folders, add users to existing groups and only allowed users can download the files from the protected folder
*	Files in the protected folder are htaccess protected, only allowed users can download them

User specific files

*	Allow File access for a single specific user
*	Files in the protected folder are htaccess protected, only the specific user can download them


And more things to come...

*	File download statistics
*	FileManager skins & icons
*	Frontend upload
*	File sharing tools

The Frontend filemanager can easily be integrated on your Frontend; Clean CSS format (no dropping shadows, rounded corners) for you to easily customize the way you like in your own theme file.
Before Asking for Help, please consult the “Help” section on the plugin administration page.

== Installation ==
1. Upload `upz-filemanager` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Please read the "Help" tab on plugin administration page to learn how it works

== Screenshots ==
1. Integrate the file manager to your WordPress frontend
2. Or use the single file shortcode to add a single file on your content
3. Single file shortcode generator available next to your upload media button

== Changelog ==
= 0.1.5 =
* Added a cool scrollbar on filemanager panel
* Corrected an error when your upload folder isn't on the default wp folder
* Corrected an error on administration (unable to edit / delete files)
= 0.1.4 =
* Added mime type checking, see on general options.
= 0.1.3 =
* Added some name checking when creating files & groups to prevent errors
* Added database cleaning when deleting a folder (deleting attached files on database too)
* Corrected an administration navigation panel bug
= 0.1.2 =
* Corrected rights management for super admin (access to all files/folders even if not in allowed groups)
= 0.1.1 =
* Added default options on install
* Corrected icons path
= 0.1 =
* Beta release. Feel free to contact pluginwp@unpointzero.com to report bugs.

== Upgrade Notice ==
= 0.1.4 =
* Please add mime type for your allowed file types on plugin general options (you can import default ones with the copy default button).