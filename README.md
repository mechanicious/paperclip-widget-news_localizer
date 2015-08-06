# Register
`app\config\app.php:`

```php
'providers' => array(
	'PaperClip\NewsLocalizer\NewsLocalizerServiceProvider',
	),
'aliases' => array(
	'NewsLocalizer'   => 'PaperClip\Support\Facades\NewsLocalizer',
	)
```

# Directory Tree
```
└───app
    ├───lang
    │   └───en
    │       └───widget
    │           └───news_localizer
    ├───src
    │   └───mechanic
    │       └───paperclip
    │           ├───NewsLocalizer
    │           └───Support
    │               └───Facades
    └───views
        └───admin
            └───dashboard
                └───widgets
                    └───news_localizer
```