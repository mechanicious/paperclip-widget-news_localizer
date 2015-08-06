<?php namespace PaperClip\NewsLocalizer;

use Illuminate\Support\ServiceProvider;

class NewsLocalizerServiceProvider extends ServiceProvider 
{
	public function register()
	{
		$this->app->bindShared('newsLocalizer', function($app)
		{
			return new NewsLocalizer;
		});
	}
}