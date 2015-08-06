<?php namespace PaperClip\NewsLocalizer;

/**
 * Allows the user select what news to display.
 */
class NewsLocalizer extends \PaperClip\Support\Contracts\Widget
{
	/**
	 * The widget settings
	 * @var array
	 */
	protected $settings = array();

	/**
	 * The settings user has submitted
	 * @var array
	 */
	protected $userSettings = array();

	/**
	 * For efficiency reason we don't use language names but 
	 * Settings format:
	 * Language Name => Category Id
	 * E.g. English => 2
	 */
	public function __construct()
	{
		$this->settings = array(
			'title' 				=> \Lang::get('widget/news_localizer/install.title'),
			'author' 				=> 'Mateusz Zawartka',
			// strictName has to be the same as the class name
			'strictName'			=> 'NewsLocalizer',
			'userSettings' 			=> null,
			'description' 			=> \Lang::get('widget/news_localizer/install.description'),
			'bodyTemplateName' 		=> 'admin.dashboard.widgets.news_localizer.index',
			'user_id' 				=> \Auth::id(),
			'previousVersion' 		=> null,
			'currentVersion' 		=> "0.1",
			);

		$this->userSettings = $this->getUserSettings();
	}
	/**
	 * Installs the News Localizer
	 * @return boolean
	 */
	public function install()
	{
		if(($validator = parent::install()) instanceof \Illuminate\Support\MessageBag)
		{
			\Notifier::putError($validator);
			return \Redirect::route('admin.dashboard.widget');
		}
		\Notifier::putSuccess(\Lang::get('widget/news_localizer/install.installation-successfull'));
		return \Redirect::route('admin.dashboard.widget');
	}

	/**
	 * Uninstalls the widget.
	 * @return boolean
	 */
	public function uninstall()
	{
		\Notifier::putInfo("Too bad you're uninstalling NewsLocalizer. Hope we'll see eachother again!");
		return parent::uninstall();
	}

	/**
	 * Returns the news category id for a specified language 
	 * @param  string $langage
	 * @return numeric | null
	 */
	public function getNewsCategoryIdFromUserSettings($language)
	{
		
		if(!isset($this->userSettings[$language])) return null;
		return $this->userSettings[$language];
	}
	
	/**
	 * Get the user prefered cateogry id for a language
	 * @param  string $language
	 * @return int
	 */
	public function getCategoryId($language) {
		return $this->getUserSettings($language);
	}

	/**
	 * Returns the news category name for a specified language
	 * @return null | string
	 */
	public function getCategory($language)
	{
		if( ! isset($this->userSettings[$language])) return null;
		$categoryId = $this->userSettings[$language];
		$category = \Category::whereId($categoryId);
		return $category;
	}

	public function getPostChunkSize()
	{
		return $postChunkSize = $this->userSettings['postChunkSize']; 
	}

	/**
	 * Get a certain amount of news entries for a sppecified
	 * language.	
	 * @param  string $language
	 * @param  integer $limit
	 * @return array
	 */
	public function getLatestNewsEntries($language, $limit = 5)
	{
		$newsCategoryId = $this->getNewsCategoryIdFromUserSettings($language);
		$language = \Language::withName($language)->first();
		if( is_null($language) ) return;
		
		$category = \Category::whereLangAndCatId($language->id, $this->getUserSettings($language->language));
		if( is_null($category)) return;
		
		$localizedPosts = $category->latestPosts()->limit($limit)->get()->all();
		if( is_null($localizedPosts) ) return;
		return $localizedPosts;
	}

	/**
	 * @param  	Get a total number of posts available in a language specific category
	 * @return string | int
	 */
	public function totalCount($language) 
	{
		$newsCategoryId = $this->getNewsCategoryIdFromUserSettings($language);
		$language = \Language::withName($language)->first();
		if( is_null($language) ) return;
		
		$category = \Category::whereLangAndCatId($language->id, $this->getUserSettings($language->language));
		if( is_null($category)) return;
		// TODO: handle the soft deleted cases
		return $category->posts()->whereNull('posts.deleted_at')->get()->count();
	}

	/**
	 * Get user settings
	 * @param  string $fieldname
	 * @return array | string | null
	 */
	public function getUserSettings($fieldname = null)
	{	
		return @parent::getUserSettings($fieldname);
	}
}