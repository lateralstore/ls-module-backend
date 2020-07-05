<?php

/*
 * @TODO update instances of addFileLink to HTML uploaders
 *  - file type checks?
 */
	class Backend_Module extends Core_ModuleBase
	{
		/**
		 * Creates the module information object
		 * @return Core_ModuleInfo
		 */
		protected function createModuleInfo()
		{
			return new Core_ModuleInfo(
				"Backend",
				"Back-end user interface",
				"LateralStore" );
		}

		/**
		 * Returns a list of the module back-end GUI tabs.
		 * @param Backend_TabCollection $tabCollection A tab collection object to populate.
		 * @return mixed
		 */
		public function listTabs($tabCollection)
		{
			$user = Phpr::$security->getUser();

			if ($user && $user->get_permission('backend', 'access_dashboard'))
				$tabCollection->tab('dashboard', 'Dashboard', '', 10);

			$reports = Core_ModuleManager::listReports();
			if (count($reports))
			{
				foreach ($reports as $module_id=>$reports)
				{
					if (count($reports['reports']))
					{
						$tabCollection->tab('reports', 'Reports', 'reports', 15);
						break;
					}
				}
			}
		}
		
		/**
		 * Builds user permissions interface
		 * For drop-down and radio fields you should also add methods returning 
		 * options. For example, of you want to have "Access Level" drop-down:
		 * public function get_access_level_options();
		 * This method should return array with keys corresponding your option identifiers
		 * and values corresponding its titles.
		 * 
		 * @param $host_obj ActiveRecord object to add fields to
		 */
		public function buildPermissionsUi($host_obj)
		{
			$host_obj->add_field($this, 'access_dashboard', 'Dashboard Access')->renderAs(frm_checkbox)->comment('User has access to the dashboard.', 'above')->tab('Account');
		}
		
		public function listPersonalSettingsItems()
		{
			return array(
				array(
					'icon'=>Backend_Skin::get_icon_uri('code_editor','personal_settings'),
					'title'=>'Code Editor Settings', 
					'url'=>'/backend/codeeditorsettings',
					'description'=>'Customize the built-in code editor: select color theme, enable word wrapping, etc.',
					'sort_id'=>10,
					'section'=>'System'
					),
				array(
					'icon'=>Backend_Skin::get_icon_uri('appearance','personal_settings'),
					'title'=>'Appearance', 
					'url'=>'/backend/appearancesettings',
					'description'=>'Customize the Administration Area appearance: choose the main menu style, etc.',
					'sort_id'=>20,
					'section'=>'System'
					)
			);
		}
	}
?>