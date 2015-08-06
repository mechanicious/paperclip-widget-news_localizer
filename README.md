`app\config\app.php`

```php
'providers' => array(
	'PaperClip\NewsLocalizer\NewsLocalizerServiceProvider',
	),
'aliases' => array(
	'NewsLocalizer'   => 'PaperClip\Support\Facades\NewsLocalizer',
	)
```