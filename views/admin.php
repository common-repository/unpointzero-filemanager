<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 *
 * TODO : 
 * 		Folders name check
 *		Group name edit & check
 */
?>
<?php
global $wpdb;
?>
<div class="wrap">
	<?php
	$options = get_option( 'upzfile_options' );
	$requesturl = admin_url( 'admin.php?page=UPZFileManager' )."&action=";
	$allowed_fileextensions = $options['allowed_fileextensions'];
	$allowedmimes = filext_tomimetype($allowed_fileextensions);
	?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div class="navi">
		<ul class="tabNavigation">
            <li><a href="#tab-files">Files management & shortcodes</a></li>
            <li><a href="#tab-folders">Folders management</a></li>
			<li><a href="#tab-groups">Groups management</a></li>
			<li><a href="#tab-users">User specific file management</a></li>
			<li><a href="#tab-options">General configuration</a></li>
			<li><a href="#tab-help">Help</a></li>
        </ul>
	</div>
		<div class="tabs" id="tab-files">
			<h2>Files management & shortcodes</h3>
			<div class="metabox-double-container">
				<div class="firstelem metabox-holder postbox">
					<h3>Files management</h3>
					<?php
					echo display_authorized_fullpanel(); ?>
					<h3>Shortcodes</h3>
					<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tbody>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Single file shortcode:
								</th>
								<td>
									<?php form_input("single_shortcode",""); ?>
									<p class="setting-description" style="margin:5px 10px;">Single file shortcode, click on "generate shortcode above a file to generate it.</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Panel shortcode:
								</th>
								<td>
									<?php form_input("panel_shortcode","[upzfiles]"); ?>
									<p class="setting-description" style="margin:5px 10px;">Display the full panel on your website. User will view folders/files depending their rights.</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php
				if ($_GET['action']=="editfile_form") {
				?>
				<div class="metabox-holder postbox">
					<h3>Edit file</h3>
					<form enctype="multipart/form-data" method="post" action="<?php echo $requesturl."edit_file"; ?>">
					<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tbody>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Current file
								</th>
								<td>
									<?php echo display_upzfiles_singlefile($_GET['id'],"panel",false); ?>
									<?php form_input_hidden("oldfile_fullpath",get_filepath_byid($_GET['id'])); ?>
									<?php form_input_hidden("oldfile_id",$_GET['id']); ?>
									<?php form_input_hidden("oldfile_path",get_filepath_byid($_GET['id'],"path")); ?>
									<?php form_input_hidden("oldfile_pathid",get_folderid_byfileid($_GET['id'])); ?>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									File name:
								</th>
								<td>
									<?php form_input("oldfile_name",get_filevisiblename_byid($_GET['id'])); ?>
									<p class="setting-description" style="margin:5px 10px;">Edit the filename</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Update this file:
								</th>
								<td>
									<?php
									upload_form("upload_new_file",$allowedmimes); ?>
									<p class="setting-description" style="margin:5px 10px;">Select a new file</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
								</th>
								<td>
								<?php submit_btn("editfile_form_submit","Edit file"); ?>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
				</div>
				<?php
				} elseif ($_GET['action']=="deletefile_form") {
				?>
				<div class="metabox-holder postbox">
					<h3>Delete file</h3>
					<form enctype="multipart/form-data" method="post" action="<?php echo $requesturl."delete_file"; ?>">
					<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tbody>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Current file
								</th>
								<td>
									<?php echo display_upzfiles_singlefile($_GET['id'],"panel",false); ?>
									<?php form_input_hidden("oldfile_fullpath",get_filepath_byid($_GET['id'])); ?>
									<?php form_input_hidden("oldfile_id",$_GET['id']); ?>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
								</th>
								<td>
								<?php submit_btn("deletefile_form_submit","Delete file"); ?>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
				</div>
				<?php
				} else {
				?>

				<div class="metabox-holder postbox">
					<h3>Upload a file</h3>
					<form enctype="multipart/form-data" method="post" action="<?php echo $requesturl."upload_file"; ?>">
					<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tbody>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									File name:
								</th>
								<td>
									<?php form_input("upload_filename",""); ?>
									<p class="setting-description" style="margin:5px 10px;">Enter the filename</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									File directory:
								</th>
								<td><?php form_select("upload_directory",list_folders_idname_select(list_folders())); ?>
									<p class="setting-description" style="margin:5px 10px;">Select directory</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Select a file:
								</th>
								<td>
									<?php upload_form("upload_file",$allowedmimes); ?>
									<p class="setting-description" style="margin:5px 10px;">Select a file</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
								</th>
								<td>
								<?php submit_btn("upzfile_submit_sendfile","Upload file"); ?>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		
		<div class="tabs" id="tab-folders">
		<h2>Folders management</h2>
		<table class="widefat">
			<thead>
				<tr>
					<th>Path</th>
					<th>Name</th>
					<th>Rights</th>
					<th>Group</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
				<th>Path</th>
				<th>Name</th>
				<th>Rights</th>
				<th>Group</th>
				<th>Action</th>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$folders = list_folders();
			foreach($folders as $folder) {
			?>
			<form method="post" action="<?php echo $requesturl."folder_management_edit&id=".$folder['id']; ?>">
			   <tr>
				 <td><?php form_input_disabled("folder_path", $folder['named_path']); form_input_hidden("real_path", $folder['path']); ?></td>
				 <td><?php form_input("folder_name",$folder['name']);?><?php submit_btn("folder_rename","Rename folder"); ?></td>
				 <td><?php if($folder['right']=="parent") { form_input_disabled("folder_rights",$folder['right']); } else { form_select("folder_rights",array("public"=>"public","private"=>"private","parent"=>"parent"),$folder['right']); submit_btn("folder_updaterights","Update rights"); } ?></td>
				 <td><?php if($folder['right']=="parent" || $folder['right']=="public") { form_input_disabled("folder_group",$folder['right']); } else { form_selectmultiple("folder_group",list_nameid_groups(),$folder['group']); ?><?php submit_btn("folder_updategroup","Update group"); } ?></td>
				 <td><?php submit_btn("folder_delete","Delete folder"); ?></td>
			   </tr>
			</form>
			<?php
			}
			?>
			</tbody>
		</table>
		
		<h2>Create a new folder</h2>
		<div class="metabox-holder postbox">
			<form method="post" action="<?php echo $requesturl."folder_addnew"; ?>">
			<h3>Create a new folder</h3>
			<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
				<tbody>
					<tr>
						<th width="30%" valign="top" style="padding-top:10px;">
							Folder name:
						</th>
						<td><?php form_input("folder_createnew_name","");?>
							<p class="setting-description" style="margin:5px 10px;">Enter folder name</p>
						</td>
					</tr>
					<tr>
						<th width="30%" valign="top" style="padding-top:10px;">
							Select parent folder:
						</th>
						<td><?php form_select("folder_createnew_parent",list_folders_idname_select(list_folders())); ?>
							<p class="setting-description" style="margin:5px 10px;">Select parent folder</p>
						</td>
					</tr>
					<tr class="folder_createnew_parent_hide">
						<th width="30%" valign="top" style="padding-top:10px;">
							Select folder rights:
						</th>
						<td><?php form_select("folder_createnew_rights",array("public"=>"public","private"=>"private")); ?>
							<p class="setting-description" style="margin:5px 10px;">Select folder rights</p>
						</td>
					</tr>
					<tr class="folder_createnew_parent_hide">
						<th width="30%" valign="top" style="padding-top:10px;">
							Select folder groups (if private):
						</th>
						<td><?php form_selectmultiple("folder_createnew_groups",list_nameid_groups()); ?>
							<p class="setting-description" style="margin:5px 10px;">Select folder groups (if private)</p>
						</td>
					</tr>
					<tr>
						<th width="30%" valign="top" style="padding-top:10px;">
						</th>
						<td>
						<?php submit_btn("folder_createnew_btn","Create a new folder"); ?>
						</td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		
		</div>
		
		<div class="tabs" id="tab-groups">
		<h2>Group management</h2>
		<table class="widefat">
			<thead>
				<tr>
					<th>Group name</th>
					<th>Users</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Group name</th>
					<th>Users</th>
					<th>Actions</th>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$select_groups = list_all_groups();
			foreach($select_groups as $select_group){		
			$group_id = $select_group->group_id;
			?>
			<form method="post" action="<?php echo $requesturl."group_management_edit&id=".$group_id; ?>">
			   <tr>
				 <td><?php form_input("group_edit_name",$select_group->group_name);?></td>
				 <td><?php form_selectmultiple("group_edit_users",list_all_users(),$select_group->group_users_id); ?><?php submit_btn("group_update_edit_users","Update group"); ?></td>
				 <td><?php submit_btn("group_edit_deletegroup_btn","Delete group"); ?></td>
			   </tr>
			</form> 
			<?php
			}
			?>
			</tbody>
		</table>
		<h2>Create a new group</h2>
		<div class="metabox-holder postbox">
			<form method="post" action="<?php echo $requesturl."group_addnew"; ?>">
			<h3>Create a new group</h3>
			<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
				<tbody>
					<tr>
						<th width="30%" valign="top" style="padding-top:10px;">
							Group name:
						</th>
						<td><?php form_input("group_createnew_name","");?>
							<p class="setting-description" style="margin:5px 10px;">Enter group name</p>
						</td>
					</tr>
					<tr>
						<th width="30%" valign="top" style="padding-top:10px;">
							Select users:
						</th>
						<td><?php form_selectmultiple("group_createnew_users",list_all_users("array")); ?>
							<p class="setting-description" style="margin:5px 10px;">Select users (multiple allowed)</p>
						</td>
					</tr>
					<tr>
						<th width="30%" valign="top" style="padding-top:10px;">
						</th>
						<td>
						<?php submit_btn("group_createnew_btn","Create a new group"); ?>
						</td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		</div>

		<div class="tabs" id="tab-users">
		<h2>Users file management</h2>
			<div class="metabox-double-container">
					<div class="firstelem metabox-holder postbox">
					<h3>Users files</h3>
					<div id="upzfiles_userfilescontainer">
					<form method="post" action="<?php echo $requesturl."uploadfile_selectuser"; ?>">
					<?php
					if($_GET['action']=="uploadfile_selectuser") {
						$selecteduser = $_POST['uploadfile_selectuser'];
					}
					?>
						<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
							<tbody>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
										Select a user:
									</th>
									<td>
										<?php form_selectsubmit("uploadfile_selectuser",list_all_users()); ?>
										<p class="setting-description" style="margin:5px 10px;">Select a user</p>
									</td>
								</tr>
							</tbody>
						</table>
						</form>

					<?php
					if($_GET['action']=="uploadfile_selectuser") {
						echo display_authorized_folders_content($selecteduser,"user");
					}
					?>
					</div>
					<h3>Shortcodes</h3>
					<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tbody>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Single file shortcode:
								</th>
								<td>
									<?php form_input("single_user_shortcode",""); ?>
									<p class="setting-description" style="margin:5px 10px;">Single user file shortcode, click on "generate shortcode above a file to generate it.</p>
								</td>
							</tr>
						</tbody>
					</table>
					</div>

					<?php
					if ($_GET['action']=="edituserfile_form") {
					?>
					<div class="metabox-holder postbox">
						<h3>Edit file</h3>
						<form enctype="multipart/form-data" method="post" action="<?php echo $requesturl."edit_userfile"; ?>">
						<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
							<tbody>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
										Current file
									</th>
									<td>
										<?php echo display_upzfiles_singlefile($_GET['id'],"singleuser",false); ?>
										<?php form_input_hidden("oldfile_fullpath",get_userfilepath_byid($_GET['id'])); ?>
										<?php form_input_hidden("oldfile_id",$_GET['id']); ?>
										<?php form_input_hidden("oldfile_pathid",get_fileownerid_byid($_GET['id'])); ?>
									</td>
								</tr>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
										File name:
									</th>
									<td>
										<?php form_input("oldfile_name",get_filevisiblename_byid($_GET['id'])); ?>
										<p class="setting-description" style="margin:5px 10px;">Edit the filename</p>
									</td>
								</tr>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
										Update this file:
									</th>
									<td>
										<?php upload_form("upload_new_file"); ?>
										<p class="setting-description" style="margin:5px 10px;">Select a new file</p>
									</td>
								</tr>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
									</th>
									<td>
									<?php submit_btn("editfile_form_submit","Edit file"); ?>
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
					<?php
					} elseif ($_GET['action']=="deleteuserfile_form") {
					?>
					<div class="metabox-holder postbox">
						<h3>Delete file</h3>
						<form enctype="multipart/form-data" method="post" action="<?php echo $requesturl."delete_userfile"; ?>">
						<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
							<tbody>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
										Current file
									</th>
									<td>
										<?php echo display_upzfiles_singlefile($_GET['id'],"singleuser",false); ?>
										<?php form_input_hidden("oldfile_fullpath",get_userfilepath_byid($_GET['id'])); ?>
										<?php form_input_hidden("oldfile_id",$_GET['id']); ?>
									</td>
								</tr>
								<tr>
									<th width="30%" valign="top" style="padding-top:10px;">
									</th>
									<td>
									<?php submit_btn("deletefile_form_submit","Delete file"); ?>
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
					<?php
					} else {
					?>

					<div class="metabox-holder postbox">
					<h3>Upload a file</h3>
					<form enctype="multipart/form-data" method="post" action="<?php echo $requesturl."upload_user_file"; ?>">
					<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tbody>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									File name:
								</th>
								<td>
									<?php form_input("upload_filename",""); ?>
									<p class="setting-description" style="margin:5px 10px;">Enter the filename</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									To user:
								</th>
								<td><?php form_select("upload_user",list_all_users()); ?>
									<p class="setting-description" style="margin:5px 10px;">Select the user</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
									Select a file:
								</th>
								<td>
									<?php upload_form("upload_file",$allowedmimes); ?>
									<p class="setting-description" style="margin:5px 10px;">Select a file</p>
								</td>
							</tr>
							<tr>
								<th width="30%" valign="top" style="padding-top:10px;">
								</th>
								<td>
								<?php submit_btn("upzfile_submit_sendfile","Upload file"); ?>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
					</div>
					<?php
					}
					?>
			</div>
		</div>
	
	<form method="post" action="options.php">
		<?php
			settings_fields( 'upzfile_options' );
			$options = get_option( 'upzfile_options' );
		?>
		<?php // form_input_hidden("upzfile_options[admin_page]","http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."&action=");?>
		<div class="tabs" id="tab-options">
		<h2>General options</h2>
		<div class="metabox-holder postbox">
		<h3>Display options</h3>
		<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
			<tbody>
				<tr>
					<th width="30%" valign="top" style="padding-top:10px;">
						Display folder filecount
					</th>
					<td><?php form_checkbox("upzfile_options[display_filecount]","Display filecount",true,$options['display_filecount']); ?>
						<p class="setting-description" style="margin:5px 10px;">Check to display filecount at the end of folder name</p>
					</td>
				</tr>
				<tr>
					<th width="30%" valign="top" style="padding-top:10px;">
						Display file size
					</th>
					<td><?php form_checkbox("upzfile_options[display_filesize]","Display file size",true,$options['display_filesize']); ?>
						<p class="setting-description" style="margin:5px 10px;">Check to display file size at the end of file name</p>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<div class="metabox-holder postbox">
		<h3>File upload options</h3>
		<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
			<tbody>
				<tr>
					<th width="30%" valign="top" style="padding-top:10px;">
						Allowed file extensions
					</th>
					<td><?php form_textarea("upzfile_options[allowed_fileextensions]",$options['allowed_fileextensions']); ?><input class="button-secondary" value="Copy default extensions" id="upzfiles_defaultext"/>
						<p class="setting-description" style="margin:5px 10px;">Allowed file extensions, comma separated.</p>

					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<div class="metabox-holder postbox">
		<h3>Specific files options</h3>
		<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
			<tbody>
				<tr>
					<th width="30%" valign="top" style="padding-top:10px;">
						Open PDF files instead of download:
					</th>
					<td><?php form_checkbox("upzfile_options[openpdf]","Open PDF",true,$options['openpdf']); ?>
						<p class="setting-description" style="margin:5px 10px;">Check to open PDF files</p>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</div>
	</form>

	<div class="tabs" id="tab-help">
		<h2>Help</h2>

		<div class="metabox-holder postbox">
		<h3>Before starting...</h3>
		<p>
		This is an earlier of our file management plugin, it may still bugs or missing feature, feel free to contact us if you find something wrong, if you miss a feature or for help.<br />
		Please contact pluginwp@unpointzero.com.
		</p>
		</div>

		<div class="metabox-holder postbox">
		<h3>Public file upload</h3>
		<p>
		Step by step to upload file(s) for public :
		<ol>
			<li>Go on "Folders management", create a new folder. Fill the "folder name", select "public" for folder rights and "Create a new folder".</li>
			<li>On "Files management & shortcodes", on right side fill the form with "folder name", select the folder you've previously created, select your file and click on "Upload file".</li>
			<li>Back on "Files management & shortcodes" select the folder you've created, your file is here. Click on "Generate shortcode", copy the shortcode and paste it in your content. You can also access your file directly on your editor, with the "Files from filemanager" button above below the editor page title.</li>
			<li>That's it !</li>
		</ol>
		</p>
		</div>

		<div class="metabox-holder postbox">
		<h3>Private file upload</h3>
		<p>
		Step by step to upload file(s) for private :
		<ol>
			<li>Go on "Groups management", create a new Group. Fill the "Group name" and Select the users. Selected users will have granted access to all folders linked with this group.</li>
			<li>On "Folders management", create a new folder. Fill the "folder name", select "private" for folder rights, select the group you've previously created and click on "Create a new folder".</li>
			<li>Back on "Files management & shortcodes" select the folder you've created, your file is here. Click on "Generate shortcode", copy the shortcode and paste it in your content. You can also access your file directly on your editor, with the "Files from filemanager" button above below the editor page title.</li>
			<li>That's it !</li>
		</ol>
		</p>
		</div>

		<div class="metabox-holder postbox">
		<h3>File for specific user</h3>
		<p>
		Step by step to upload file(s) for a specific user :
		<ol>
			<li>Go on "User specific file management", on right side fill the "File name", select the user and your file then click on "Upload file".</li>
			<li>Back on "User specific file management", on "User files" select the user. All uploaded files for this user will be listed above. Click on "Generate shortcode", copy the shortcode and paste it in your content.</li>
		</ol>
		</p>
		</div>

		<div class="metabox-holder postbox">
		<h3>Shortcodes for front-end</h3>
		<p>
		You've two options to display the file manager to your WordPress front end :
		<ol>
			<li>Just add [upzfiles] on your page, post, or custom post type content.</li>
			<li>Add <code>echo do_shortcode('[upzfiles]');</code> to your php template pages.</li>
		</ol>
		</p>
		</div>
	</div>

</div>