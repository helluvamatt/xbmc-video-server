<?php 

$leftItems = array(
	array('label'=>'Movies', 
		'items'=>array(
			array(
				'label'=>'Browse', 'url'=>array('movie/index'),
				'active'=>in_array($this->route, array('movie/index', 'movie/details')),
			),
			array(
				'label'=>'Recently added', 'url'=>array('movie/recentlyAdded'),
			),
		), 'linkOptions'=>array('class'=>'fontastic-icon-video'),
	),
	array('label'=>'TV Shows', 
		'items'=>array(
			array(
				'label'=>'Browse', 'url'=>array('tvShow/index'),
				'active'=>in_array($this->route, array('tvShow/index', 'tvShow/details')),
			),
			array(
				'label'=>'Recently added', 'url'=>array('tvShow/recentlyAdded'),
			),
		), 'linkOptions'=>array('class'=>'fontastic-icon-tv'),
	),
);

$rightItems = array();

// Add a "Change backend" menu when there's more than one configured backend
$backends = Backend::model()->findAll();

if (count($backends) > 1)
{
	$backendItems = array();
	$currentBackend = Yii::app()->backendManager->getCurrent();

	foreach ($backends as $backend)
	{
		$label = $backend->name;

		if ($currentBackend !== null && $currentBackend->id == $backend->id)
			$label = TbHtml::icon(TbHtml::ICON_OK).' '.$label;

		$backendItems[] = array(
			'label'=>$label,
			'url'=>array('backend/change', 'id'=>$backend->id),
		);
	}

	$rightItems[] = array(
		'label'=>'Change backend',
		'icon'=>'cloud',
		'items'=>$backendItems,
	);
}

// Add the "Settings" menu for administrators
if (Yii::app()->user->role == User::ROLE_ADMIN)
{
	$rightItems[] = array('label'=>'Settings', 'items'=>array(
		array('label'=>'Settings'),
		array('label'=>'Manage', 'url'=>array('setting/admin')),
		array('label'=>'Backends'),
		array('label'=>'Manage', 'url'=>array('backend/admin')),
		array('label'=>'Create new', 'url'=>array('backend/create')),
		array('label'=>'Users'),
		array('label'=>'Manage', 'url'=>array('user/admin')),
		array('label'=>'Create new', 'url'=>array('user/create')),
		array('label'=>'System log'),
		array('label'=>'Browse', 'url'=>array('log/')),
	), 'linkOptions'=>array('class'=>'fontastic-icon-settings'));
}

// Add the "Actions" menu
$rightItems[] = array(
	'label'=>'Actions', 'items'=>array(
		// user-related actions
		array('label'=>'User'),
		array('label'=>'Log out', 'url'=>array('site/logout')),
		// system-related
		array('label'=>'System'),
		array('label'=>'Flush cache', 
			'url'=>array('site/flushCache'),
			'linkOptions'=>array('confirm'=>'Are you sure you want to flush the cache?'),
		),
		array('label'=>'Update library', 
			'url'=>array('backend/updateLibrary'), 
			'linkOptions'=>array('confirm'=>"Are you sure you want to update the backend's library?"))
	), 'icon'=>'tasks',
);

// Construct the menu
$navbarItems = array(
	array(
		'class'=>'bootstrap.widgets.TbNav',
		'activateParents'=>true,
		'items'=>$leftItems,
	),
	array(
		'class'=>'bootstrap.widgets.TbNav',
		'activateParents'=>true,
		'encodeLabel'=>false, // because of icons in the Change backend menu
		'items'=>$rightItems,
		'htmlOptions'=>array('class'=>'pull-right'),
	)
);

// Render the navbar
$this->widget('bootstrap.widgets.TbNavbar', array(
	'brandLabel'=>false,
	'display'=>TbHtml::NAVBAR_DISPLAY_NONE,
	'collapse'=>true,
	'items'=>$navbarItems));