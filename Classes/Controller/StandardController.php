<?php
namespace TYPO3\Docs\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Docs".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the TYPO3.Docs package
 *
 * @FLOW3\Scope("singleton")
 */
class StandardController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\Docs\Command\GitDocumentationRenderingCommandController
	 */
	protected $documentationRenderingController;

	/**
	 * Render action
	 *
	 * @param string $origin a path
	 * @param string $repositoryType possible values are git, svn, ter
	 * @param string $branch
	 * @return void
	 */
	public function renderAction($origin = '', $repositoryType = 'git', $branch = 'master') {

		#@FLOW3\Validate $source NotEmpty -> does not work ;( @todo check why
		if ($origin === '') {
			throw new \TYPO3\Docs\Exception\InvalidArgumentException('Missing source argument', 1341838275);
		}

		// Register
		$this->documentationRenderingController->addGitJobCommand($origin);

		$this->view->assign('source', $origin);
		$this->view->assign('numberOfItems', 3);
	}

	/**
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('index');
	}

}

?>