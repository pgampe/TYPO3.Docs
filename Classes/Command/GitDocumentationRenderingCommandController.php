<?php
namespace TYPO3\Docs\Command;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Docs".                 *
 *                                                                        *
 *                                                                        *
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Documentation rendering command controller
 * to be used as a basis for the documentation rendering by the doc team
 *
 * @FLOW3\Scope("singleton")
 */
class GitDocumentationRenderingCommandController extends \TYPO3\DocTools\Command\DocumentationRenderingCommandController {

	/**
	 * @param string $origin
	 */
	public function addGitJobCommand($origin) {

		$urlParts = parse_url($origin);

		// Computes variable first
		$parts = explode('/', $urlParts['path']);
		$repositoryName = str_replace('.git', '', array_pop($parts));
		$repositoryPath = implode('/', $parts);

		$job = new \TYPO3\Docs\Job\GitDocumentationRenderJob($origin, $repositoryPath, $repositoryName);
		$this->jobManager->queue('renderDocumentation', $job);
	}
}

?>