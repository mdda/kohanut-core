<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Admin controller. This handles login and logout, ensures that the admin is logged in, does some auto-rendering and templating.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin extends Controller {

	// The user thats logged in
	protected $user;
	
	// The view to render
	protected $view;
	
	protected $auto_render = true;
	
	// admin pages require login
	protected $requires_login = true;
	
	public function before()
	{	
		// Do not require login for login/logout
		if ($this->request->action === 'login' OR $this->request->action === 'logout')
		{
			$this->requires_login = FALSE;
		}
		
		// Set the default view
		$this->view = New View('kohanut/admin');
		
		if ($this->requires_login)
		{
			// Check if user is logged in
			if ($id = Cookie::get('user'))
			{
				$user = Sprig::factory('kohanut_user')
					->values(array('id'=>$id))
					->load();
				
				if ($user->loaded())
				{
					// user is logged in
					$this->user = $user;
					// bind username to view so we can say hello
					$this->view->user = $user->username;
				}
			}
			
			// If they aren't logged in, and the page requires login, redirect to login screen
			if ( ! $this->user )
			{
				$this->request->redirect(Route::get('kohanut-login')->uri(array('action'=>'login')));
			}
		}
		
		// Check for language change
		if (isset($_GET['lang']))
		{
			$lang = $_GET['lang'];

			// Load the accepted language list
			$translations = array_keys(Kohana::message('kohanut', 'translations'));

			if (in_array($lang, $translations))
			{
				// Set the language cookie
				Cookie::set('kohanut_language', $lang, Date::YEAR);
			}

			// Reload the page
			$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages')));
		}
		
		// Set the translation language
		I18n::$lang = Cookie::get('kohanut_language', Kohana::config('kohanut')->lang);
		
		// Include Twig if it hasn't been yet
		if ( ! class_exists('Twig_Autoloader'))
		{
			require Kohana::find_file('vendor', 'Twig/lib/Twig/Autoloader');
			Twig_Autoloader::register();
		}
		
		// Include Markdown Extra, if it hasn't been yet
		if ( ! function_exists('Markdown'))
		{
			require Kohana::find_file('vendor','Markdown/markdown');
		}
		
	}
	
	public function __call($method,$args)
	{
		$this->admin_error("Could not find the url you requested.");
	}
	
	
	public function admin_error($message)
	{
		$this->before();
		$this->view->body = new View('kohanut/admin-error');
		$this->view->body->message = $message;
	}

	public function after()
	{
		// If auto_render is true, send the response
		if ($this->auto_render)
		{
			$this->request->response = $this->view;
		}
	}

	public function action_login()
	{
		// If the user is logged in, redirect them
		if ($this->user)
		{
			$this->request->redirect('admin/pages');
		}
		
		// Overide default view and bind with $user and $errors
		$this->view = View::factory('kohanut/login')
			->bind('user', $user)
			->bind('errors', $errors);
		
		$this->view->title = "Login";
		
		// Load an empty user
		$user = Sprig::factory('kohanut_user');
		
		// Load rules defined in sprig model into validation factory    
		$post = Validate::factory($_POST)
			->rules('username', $user->field('username')->rules)
			->rules('password', $user->field('password')->rules);
		
		// Validate the post    
		if ($_POST)
		{
			$user->values($post->as_array());
			
			if ($post->check())
			{
				// Load the user by username and password
				$user->values($post->as_array())->load();
		
				if ($user->loaded())
				{
					// Store the user id
					Cookie::set('user', $user->id);
		
					// Redirect to the home page
					$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages')));
				}
				else
				{
					$post->error('username', 'invalid');
				}
			}
		}
		
		$errors = $post->errors('kohanut',TRUE);
	}
	
	public function action_logout()
	{
		// Delete the user cookie
		Cookie::delete('user');
			
		// Redirect to the login
		$this->request->redirect(Route::get('kohanut-login')->uri(array('action'=>'login')));
	}

	public function action_lang()
	{
		$this->view->body = View::factory('kohanut/lang',array('translations'=>Kohana::message('kohanut', 'translations')));
	}
}
