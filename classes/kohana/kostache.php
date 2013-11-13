<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mustache templates for Kohana.
 *
 * @package    Kostache
 * @category   Base
 * @author     Jeremy Bush <jeremy.bush@kohanaframework.org>
 * @copyright  (c) 2010-2012 Jeremy Bush
 * @license    MIT
 */
class Kohana_Kostache {

	const VERSION = '4.0.0';

	protected $_engine;

	// Cache settings
	public static $config;

	public static function factory()
	{
		// Load config appropriate to environment.
		if ( !self::$config )
		{
			$config = Kohana::$environment === Kohana::PRODUCTION ? 'production' : 'development';

			self::$config = Kohana::$config->load( 'kostache' )->$config;
		} # if

		$m = new Mustache_Engine(
			array(
				'loader'          => new Mustache_Loader_Kohana( ),
				'partials_loader' => new Mustache_Loader_Kohana( self::$config['partials_dir'] ),
				'cache'           => APPPATH.'cache/mustache',
				'escape'          => function($value) {
					return HTML::chars($value);
				},
			)
		);

		$class = get_called_class();
		return new $class( $m );
	} # method

	public function __construct($engine)
	{
		$this->_engine = $engine;
	} # method

	// public function render($class, $template = NULL)
	// {
	// 	if ($template == NULL)
	// 	{
	// 		$template = explode('_', get_class($class));
	// 		array_shift($template);
	// 		$template = implode('/', $template);
	// 	} # if

	// 	return $this->_engine->loadTemplate($template)->render($class);
	// } # method

	/**
	 * Renders template
	 *
	 * @author  Andrius Kazdailevicius
	 * @param   string   $template   file name
	 * @param   array    $data       that will be user for template
	 * @return  string
	 */
	public function render( $template, array $data = array( ))
	{
		return
			$this->_engine->loadTemplate( $template )->render( $data );
	} # function

} # class
