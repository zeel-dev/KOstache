<?php

class Mustache_Loader_Kohana implements Mustache_Loader, Mustache_Loader_MutableLoader
{
	private $_templates = array();

	// Save current options
	private $_config    = array(
		'base_dir'   => 'templates',
		'extension'  => 'mustache'
		);

	public function __construct( $base_dir = NULL, $options = array( ))
	{
		//Load config appropriate to environment.
		$config = Kohana::$environment === Kohana::PRODUCTION ? 'production' : 'development';

		$this->_config = Kohana::$config->load( 'kostache' )->$config;


		if ( $base_dir )
		{
			$this->_config['base_dir'] = $base_dir;
		} # if

		if (isset($options['extension']))
		{
			$this->_config['extension'] = ltrim($options['extension'], '.');
		} # if
	} # __construct

	public function load($name)
	{
		if (!isset($this->_templates[$name]))
		{
			$this->_templates[$name] = $this->_load_file($name);
		} # if

		return $this->_templates[$name];
	} # method

	protected function _load_file($name)
	{
		$filename = Kohana::find_file( $this->_config['base_dir'], strtolower($name), $this->_config['extension'] );

		if ( ! $filename)
		{
			throw new Kohana_Exception('Mustache template ":name" not found', array(':name' => $name));
		} # if

		return file_get_contents($filename);
	} # method

	/**
	 * Set an associative array of Template sources for this loader.
	 *
	 * @param array $templates
	 */
	public function setTemplates(array $templates)
	{
		$this->_templates = array_merge($this->_templates, $templates);
	} # method

	/**
	 * Set a Template source by name.
	 *
	 * @param string $name
	 * @param string $template Mustache Template source
	 */
	public function setTemplate($name, $template)
	{
		$this->_templates[$name] = $template;
	} # method

} # class
