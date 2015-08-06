@extends('admin.dashboard._content_table-free')
@include('admin._alert')
@section('form')
		{{-- Update --}}
		<form action="{{ route('admin.dashboard.widget.update', array('id' => $id, 'lang' => \App::getLocale())) }}" class='form-horizontal' method='post'>
			@yield('error')
			@yield('warning')
			@yield('success')
			@yield('info')
			<fieldset>
			<legend>@lang('widget/news_localizer/index.news-categories')</legend>
				
				@if(count(Language::getNotDeleted()))
					{{-- For each language make a list of categories. For each language we will make a
					list of categories which belong to this language. --}}
						@foreach (Language::getNotDeleted() as $language)
							{{-- If there are categories which have the current language ID, then loop over
							those categories and echo the category name so that user can select it. 
							----------------------------------------------------------------------------
							If there aren't categories with the current language ID, then display an
							so that user eventually know why he can't choose an option for this language. --}}
							@if (Category::getFirstNotDeleted($language->id) !== null)
								<div class='control-group'>
									<label class='control-label' for='language'>{{ $language->language }}</label>
									<div class='controls'>
										<select name="{{ $language->language }}" autofocus>
											@foreach (Category::where('language_id', '=', $language->id)->get() as $category)
												<?php 
													/*---------------------------------------------------------
													| Variable Declaration
													|---------------------------------------------------------*/
													$userCategoryId = \NewsLocalizer::getUserSettings($language->language);
												?>
												{{-- We'll autmatically select the default option for user, if possible --}}
												<option value="{{ $category->id }}" 
												@if (!is_null($userCategoryId) && $userCategoryId == $category->id) 
													selected 
												@endif >{{ $category->category }}</option>
											@endforeach
										</select>
									</div>
								</div><!-- /control-group --> 
							@else
								<div class="alert alert-info animated pulse">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									@lang('widget/news_localizer/index.no-categories-found', array('language' => $language->language))
								</div>
							@endif		       	    		
						@endforeach
					@else
						<div class="alert alert-danger animated shake">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							@lang('widget/news_localizer/index.no-languages-found')
						</div>
					@endif

				<legend>@lang('widget/news_localizer/index.other-settings')</legend>
				<div class='control-group'>
                	<label class='control-label' for='title'>@lang('widget/news_localizer/index.post-chunk-size')</label>
                	<div class='controls'>
						<?php 
						/*---------------------------------------------------------
						| Variable Declaration
						|---------------------------------------------------------*/
							$postChunkSize = \NewsLocalizer::getUserSettings('postChunkSize');
						?>
						<input type="number" name="postChunkSize" id="input" class="form-control" value="{{ $postChunkSize or 200 }}" min="0" max="1600000" step="10" required="required">
					</div><!-- /controls -->
            	</div><!-- /control-group -->

				{{-- Submit to update. --}}				
				<div class='form-actions'>
					<button class='btn btn-primary' type='submit'>@lang('widget/news_localizer/index.submit')</button>
					<button class='btn' type='reset'>@lang('widget/news_localizer/index.reset')</button>
				</div><!-- /form-actions -->
			</fieldset>
	</form>
@stop