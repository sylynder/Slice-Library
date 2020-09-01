<?php

/**
 * Plates is a library forked from Slice-Library for CodeIgniter 3
 *
 * This class is based on Laravel's Blade templating system!
 * To see the usage documentation please visit the link below.
 *
 * @package		Plates
 * @subpackage	Library
 * @category	Library
 * @author		Gustavo Martins <gustavo_martins92@hotmail.com>
 * @author      Kwame Oteng Appiah-Nti <developerkwame@gmail.com>
 * @link		https://github.com/GustMartins/Slice-Library
 * @link		https://github.com/sylynder/plates
 * @version 	0.0.1
 */

namespace Sylynder\Plates;

class Plates {

	/**
	 *  The file extension for the plates template
	 *
	 *  @var   string
	 */
	public $plate_ext		= '.plate.php';

	/**
	 *  The amount of time to keep the file in cache
	 *
	 *  @var   integer
	 */
	public $cache_time		= 3600;

	/**
	 *  Autoload CodeIgniter Libraries and Helpers
	 *
	 *  @var   boolean
	 */
	public $enable_autoload	= FALSE;

	/**
	 *  Default language
	 *
	 *  @var   string
	 */
	public $locale			= 'english';

	// --------------------------------------------------------------------------

	/**
	 *  Reference to CodeIgniter instance
	 *
	 *  @var   object
	 */
	protected $ci;

	/**
	 *  Global array of data for Plates Template
	 *
	 *  @var   array
	 */
	protected $_data		= [];

	/**
	 *  The content of each section
	 *
	 *  @var   array
	 */
	protected $_sections	= [];

	/**
	 *  The stack of current sections being buffered
	 *
	 *  @var   array
	 */
	protected $_buffer		= [];

	/**
	 *  Custom compile functions by the user
	 *
	 *  @var   array
	 */
	protected $_directives	= [];

	/**
	 *  CodeIgniter Libraries to autoload with Plates
	 *
	 *  @var   array
	 */
	protected $_ci_libraries	= [];

	/**
	 *  CodeIgniter Helpers to autoload with Plates
	 *
	 *  @var   array
	 */
	protected $_ci_helpers		= [];

	/**
	 *  Language strings to use with translation
	 *
	 *  @var   array
	 */
	protected $_language		= [];

	/**
	 *  List of languages loaded
	 *
	 *  @var   array
	 */
	protected $_i18n_loaded		= [];

	// --------------------------------------------------------------------------

	/**
	 *  All of the compiler methods used by Plates to simulate
	 *  Laravel Blade Template
	 *
	 *  @var   array
	 */
	private $_compilers = [
		'directive',
		'comment',
		'ternary',
		'preserved',
		'echo',
		'variable',
		'forelse',
		'empty',
		'endforelse',
		'opening_statements',
		'else',
		'continueIf',
		'continue',
		'breakIf',
		'break',
		'closing_statements',
		'each',
		'unless',
		'endunless',
		'includeIf',
		'include',
		'extends',
		'yield',
		'show',
		'opening_section',
		'closing_section',
		'php',
		'endphp',
		'lang',
		'choice'
	];

	/**
	 *  Plates Class Constructor
	 *
	 *  @param   array   $params = array()
	 *  @return	 void
	 */
	public function __construct(array $params = [])
	{
		// Set the super object to a local variable for use later
		$this->ci =& get_instance();
		$this->ci->benchmark->mark('slice_execution_time_start');	//	Start the timer

		$this->ci->load->driver('cache');	//	Load ci cache driver
		$this->ci->config->load('plate');	//	Load plates config file

		if (config_item('enable_helper')) {
			$this->ci->load->helper('slice');	//	Load Slice Helper
		}

		$this->initialize($params);

		//	Autoload CodeIgniter Libraries and Helpers
		if ($this->enable_autoload === TRUE) {
			//	Autoload Libraries
			empty($this->_ci_libraries) OR $this->ci->load->library($this->_ci_libraries);

			//	Autoload Helpers
			empty($this->_ci_helpers) OR $this->ci->load->helper($this->_ci_helpers);
		}

		log_message('info', 'Plates Template Class Initialized');
	}

	// --------------------------------------------------------------------------

	/**
	 * __set magic method
	 *
	 * Handles writing to the data property
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
	}

	/**
	 *  __unset magic method
	 *
	 *  Handles unseting to the data property
	 *
	 *  @param   string   $name
	 */
	public function __unset($name)
	{
		unset($this->_data[$name]);
	}


}