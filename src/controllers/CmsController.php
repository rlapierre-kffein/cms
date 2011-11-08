<?php

class CmsController extends BaseController
{
	private $_templateMatch = null;
	private $_defaultTemplateTags = null;

	public function init()
	{
		parent::init();
		$this->_templateMatch = Blocks::app()->getUrlManager()->getTemplateMatch();

		if ($this->_templateMatch !== null)
		{
			$siteId = Blocks::app()->config->getSiteIdByUrl();

			$this->_defaultTemplateTags = array(
				'content' => new ContentTag($siteId),
				'assets' => new AssetsTag($siteId),
				'membership' => new MembershipTag($siteId),
				'security' => new SecurityTag($siteId),
			);
		}
	}

	public function run($actionId)
	{
		if ($this->_templateMatch !== null || Blocks::app()->request->getParam('c', null) !== null)
		{
			/*
            * if(($action=$this->createAction($actionID))!==null)
			{
				if(($parent=$this->getModule())===null)
					$parent=Yii::app();
				if($parent->beforeControllerAction($this,$action))
				{
					$this->runActionWithFilters($action,$this->filters());
					$parent->afterControllerAction($this,$action);
				}
			}
			else
				$this->missingAction($actionID);
 *
 */


			if ($this->_templateMatch !== null)
			{
				$tempController = $this->_templateMatch->getRelativePath();
				$tempAction = $this->_templateMatch->getFileName();
			}
			else
			{
				$tempController = Blocks::app()->request->getParam('c');
				$pathSegs = Blocks::app()->request->getPathSegments();
				$tempAction = $pathSegs[0];
			}

			// we found a matching controller for this request.
			if (($ca = Blocks::app()->createController($tempController)) !== null)
			{
				//list($requestController, $actionID) = $ca;
				$this->setRequestController($ca[0]);
				// save the current controller and swap out the new one.
				$oldController = Blocks::app()->getController();
				Blocks::app()->setController($this->getRequestController());

				// there is an explicit request to a controller and action
				if (Blocks::app()->request->getParam('c', null) !== null
				    || (Blocks::app()->controller->getModule() !== null && Blocks::app()->controller->getModule()->id == 'install')
				    || Blocks::app()->controller->id == 'update')
				{
					Blocks::app()->controller->init();
					// now we run through the filterchain on the swapped out controller.
					//parent::run($tempAction);
					Blocks::app()->controller->run($tempAction);
				}
				else
				{
					// controller request, but no action specified, so load the template.
					$this->loadTemplate($tempAction);
				}

				Blocks::app()->setController($oldController);
			}
			// no matching controller, so load the template.
			else
			{
				parent::run($actionId);
				$this->loadTemplate($tempAction);
			}
		}
		else
		{
			throw new BlocksHttpException('404', 'Page not found.');
		}
	}

	public function loadTemplate($templatePath)
	{
		$this->render($templatePath, $this->_defaultTemplateTags);
	}

	// required
	public function actionIndex()
	{
	}
}
