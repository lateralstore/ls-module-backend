<?php
class Backend_Skin extends Core_Configuration_Model {

	public $record_code = 'backend_skin_config';
	const default_skin = 'lemon';


	public static function create()
	{
		$config = new self();
		return $config->load();
	}

	protected function build_form() {
		// Add logo to our model
		$this->add_relation('has_many', 'logo', array('class_name'=>'Db_File', 'foreign_key'=>'master_object_id', 'conditions'=>"master_object_class='Admin_Config' and field='logo'", 'order'=>'id', 'delete'=>true));
		$this->define_multi_relation_column('logo', 'logo', 'Admin Logo', '@name')->invisible();
		$this->add_form_field('logo', 'left')->display_as(frm_file_attachments)
			->display_files_as('single_image')
			->add_document_label('Upload admin logo')
			->no_attachments_label('Admin Logo is not uploaded')
			->image_thumb_size(170)
			->no_label()
			->tab('General')
			->comment('Maximum image dimensions 85px height by 450px width');


		$this->add_field('skin_id', 'Admin Theme', 'full', db_varchar)->tab('General')->no_form();

	}

	protected function init_config_data()
	{
		$this->skin_id = self::default_skin;
	}

	public static function get_skin_id()
	{
		$config = new self();
		return ($config->skin_id) ? $config->skin_id : self::default_skin;
	}

	public function is_configured()
	{
		$config = self::create();
		if (!$config)
			return false;

		return $this->logo->count ? true : false;
	}

	public static function get_logo()
	{
		$settings = self::create();
		if ($settings->logo->count > 0)
			return root_url($settings->logo->first()->get_path());
		else
			return null;
	}

	public static function get_icon_uri($name, $context=null){
		$file = 'icon--'.$name.'.png';

		$active_skin_id = Backend_Skin::get_skin_id();
		$rel_locations = array(
			'/modules/backend/skins/'.$active_skin_id.'/assets/images/',
			'/modules/backend/resources/images/'
		);

		foreach($rel_locations as $rel_location) {
			if ( $context ) {
				$rel_location .= $context . '/';
			}
			$abs_location = PATH_APP . $rel_location;
			if ( is_dir( $abs_location ) ) {
				if ( file_exists( $abs_location . $file ) ) {
					return $rel_location . $file;
				}
			}
		}
		return null;
	}
}