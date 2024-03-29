<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('jquery.framework');


// Add template js
JHtml::_('script', 'uhpv-full.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'bootstrap.bundle.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', 'bootstrap.min.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));

// Use of Google Font
if ($this->params->get('googleFont'))
{
	$font = $this->params->get('googleFontName');

	// Handle fonts with selected weights and styles, e.g. Source+Sans+Condensed:400,400i
	$fontStyle = str_replace('+', ' ', strstr($font, ':', true) ?: $font);

	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $font);
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . $fontStyle . "', sans-serif;
	}");
}

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

$carousel_imgs = array();
for ($i = 1; $i <= 5; $i++) {
	if($this->params->get('carousel_'.$i)) {
		$carousel_imgs[$i-1][0] = '<img src="'.htmlspecialchars(JUri::root().$this->params->get('carousel_'.$i), ENT_QUOTES).'" class="d-block w-100" alt="...">';
		$carousel_imgs[$i-1][1] = '<h5>'.htmlspecialchars($this->params->get('carousel_label_'.$i), ENT_QUOTES).'</h5>';
		$carousel_imgs[$i-1][2] = '<p>'.htmlspecialchars($this->params->get('carousel_text_'.$i), ENT_QUOTES).'</p>';
	}
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');

if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . htmlspecialchars(JUri::root() . $this->params->get('logoFile'), ENT_QUOTES) . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
	<!-- Body -->
	<div class="body" id="top">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<!-- Header -->
			<header class="header" role="banner">
				<div class="header-inner clearfix">
					<div class="topper">
						<a class="college-logo" href="/">
							<img src="<?php echo JUri::base(true).'/templates/'.$app->getTemplate().'/images/Emblema.jpg' ?>" class="" alt="...">
							<div>ГБПОУ РМ "Саранский государственный промышленно-экономический колледж"</div>
						</a>
						<div class="header-search">
							<jdoc:include type="modules" name="position-0" style="none" />
						</div>
					</div>
					<?php if (count($carousel_imgs) > 0) : ?>
					<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
						<div class="carousel-indicators">
					    <?php
						for ($i=0; $i < count($carousel_imgs); $i++) { 
							if ($i == 0) {
								echo '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 0"></button>';
							}
							else {
								echo '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="'.$i.'" aria-label="Slide '.$i.'"></button>';
							}
						} 
						?>
					  	</div>
					  	<div class="carousel-inner">
						<?php
						for ($i=0; $i < count($carousel_imgs); $i++) { 
							if ($i == 0) {
								echo '<div class="carousel-item active">';
							}
							else {
								echo '<div class="carousel-item">';
							}
							echo $carousel_imgs[$i][0];
							echo '<div class="carousel-caption d-none d-md-block">';
							echo $carousel_imgs[$i][1];
							echo $carousel_imgs[$i][2];
							echo '</div>';
							echo '</div>';
						} 
						?>
					  	</div>
					  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"  data-bs-slide="prev">
					    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
					    <span class="visually-hidden">Previous</span>
					  </button>
					  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"  data-bs-slide="next">
					    <span class="carousel-control-next-icon" aria-hidden="true"></span>
					    <span class="visually-hidden">Next</span>
					  </button>
					</div>
					<?php endif; ?>
				</div>
			</header>
			<?php if ($this->countModules('position-1')) : ?>
				<nav class="navigation" role="navigation">
					<a class="nav-button" data-bs-toggle="collapse" href="#navcollapse" role="button" aria-expanded="false" aria-controls="navcollapse">
						<i class="bi bi-list"></i>
  					</a>
					<div class="collapse show" id="navcollapse">
						<jdoc:include type="modules" name="position-1" style="none" />
					</div>
					<a id="specialButton" href="#" class="bi bi-eye blind-mod-btn"></a>
				</nav>
			<?php endif; ?>
			<jdoc:include type="modules" name="banner" style="xhtml" />
			<div class="row-fluid">
				<?php if ($position8ModuleCount) : ?>
					<!-- Begin Sidebar -->
					<div id="sidebar" class="span3">
						<div class="sidebar-nav">
							<jdoc:include type="modules" name="position-8" style="xhtml" />
						</div>
					</div>
					<!-- End Sidebar -->
				<?php endif; ?>
				<main id="content" role="main" class="<?php echo $span; ?>">
					<!-- Begin Content -->
					<jdoc:include type="modules" name="position-3" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<div class="clearfix"></div>
					<jdoc:include type="modules" name="position-2" style="none" />
					<!-- End Content -->
				</main>
				<?php if ($position7ModuleCount) : ?>
					<div id="aside" class="span3">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="well" />
						<!-- End Right Sidebar -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<jdoc:include type="modules" name="footer" style="none" />
			<div class="backtop">
				<a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
				</a>
			</div>
			<div class="contacts-flex">
				<div class="break"></div>

				<div class="flex-el"><i class="vk-icon"></i> <a href="https://vk.com/official_page_sgpek">official_page_sgpek</a></div>
				<div class="flex-el"><i class="bi bi-mailbox2"></i> <a href="mailto:smt@moris.ru">smt@moris.ru</a></div>
				<div class="flex-el"><i class="bi bi-house-fill"></i> 430005 Республика Мордовия, г. САРАНСК, пр. Ленина, 24</div>
				<div class="flex-el"><i class="bi bi-telephone-fill"></i> 8(8342) 24-79-18</div>

				<div class="break"></div>

				<div class="flex-el">
					&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
				</div>
			</div>
		</div>
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
