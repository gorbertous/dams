<?php
declare(strict_types=1);

namespace UserMgmt;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\I18n\Number;

/**
 * Plugin for UserMgmt
 */
class Plugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
		
		//$this->addPlugin('Authentication');
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin(
            'UserMgmt',
            ['path' => '/user-mgmt'],
            function (RouteBuilder $builder) {
                // Add custom routes here

                $builder->fallbacks();
            }
        );
        parent::routes($routes);
    }

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to update.
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {

		/*$csrf = new CsrfProtectionMiddleware();

		// Token check will be skipped when callback returns `true`.
		$csrf->skipCheckCallback(function ($request) {
			$action = $this->getController()->getAction;
			$skipped = array('assert', 'metadata');
			if (in_array($action, $skipped)) {
				return true;
			}
		});

		// Ensure routing middleware is added to the queue before CSRF protection middleware.
		$middlewareQueue->add($csrf);
*/

        return $middlewareQueue;
    }
	
	public function beforeFilter(\cake\Event\EventInterface $event) {
		parent::beforeFilter($event);
		//$this->Security->setConfig('unlockedActions', ['assert', 'metadata']);
		I18n::setLocale('Europe/Luxembourg');
		Date::setDefaultLocale('Europe/Luxembourg');
	}
}
