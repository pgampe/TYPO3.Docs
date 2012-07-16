<?php
namespace TYPO3\Docs\Job;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.DocTools".             *
 *                                                                        *
 *                                                                        *
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Job to render a single documentation.
 *
 * THIS CLASS IS A STUB TO BE EXTENDED BY THE DOCUMENTATION TEAM
 */
class GitDocumentationRenderJob extends \TYPO3\DocTools\Job\DocumentationRenderJob {

	/**
	 * The path origin of the repository
	 *
	 * @var string
	 */
	protected $origin;

	/**
	 * The path towards the repository
	 *
	 * @var string
	 */
	protected $repositoryPath;

	/**
	 * The repository name
	 *
	 * @var string
	 */
	protected $repositoryName;

	/**
	 * The repository name
	 *
	 * @var string
	 */
	protected $documentationPath;

	/**
	 * The build path
	 *
	 * @var string
	 */
	protected $buildPath;

	/**
	 * Whether command should be executed or displayed
	 *
	 * @var boolean
	 */
	protected $dryRun = false;

	/**
	 * @param string $pathToRestFiles
	 */
	public function __construct($origin, $repositoryPath, $repositoryName) {
		$this->origin = $origin;

		// could be improved: to get the temporary file, method $this->environment->getPathToTemporaryDirectory() could be used
		$this->repositoryPath = FLOW3_PATH_ROOT . 'Data/Temporary/' . ltrim($repositoryPath, '/') . '/' . $repositoryName;
		$this->repositoryName = $repositoryName;
		$this->documentationPath = $this->repositoryPath . '/Documentation';
		$this->buildPath = FLOW3_PATH_ROOT . 'Data/Persistent/' . ltrim($repositoryPath, '/') . '/' . $repositoryName;
	}

	/**
	 * Execute the job
	 * A job should finish itself after successful execution using the queue methods.
	 *
	 * @param \TYPO3\Queue\QueueInterface $queue
	 * @param \TYPO3\Queue\Message $message The original message
	 * @return boolean TRUE if the job was executed successfully and the message should be finished
	 */
	public function execute(\TYPO3\Queue\QueueInterface $queue, \TYPO3\Queue\Message $message) {

		\TYPO3\FLOW3\Utility\Files::createDirectoryRecursively($this->repositoryPath);

		$this->output('Fetching Source file...');

		// TRUE means this is a new repository
		if (! is_dir($this->repositoryPath)) {
			$command = "cd {$this->repositoryPath}; git clone --quiet --recursive {$this->origin} . ";
			$this->run($command);
		}
		else {
			$command = "cd {$this->repositoryPath}; git pull --quiet";
			$this->run($command);

			$command = "cd {$this->repositoryPath}; git submodule update --quiet ";
			$this->run($command);
		}


		// Generate Sphinx Configuration
		$view = new \TYPO3\Fluid\View\StandaloneView();
		$view->setTemplatePathAndFilename('resource://TYPO3.Docs/Private/Templates/conf.py.fluid');

		$view->assign('version', '1.0');
		$view->assign('extensionName', $this->repositoryName);
		file_put_contents($this->documentationPath . '/conf.py', $view->render());

		// Generate Make Configuration
		$view->setTemplatePathAndFilename('resource://TYPO3.Docs/Private/Templates/Makefile.fluid');
		$view->assign('buildDirectory', $this->buildPath);
		file_put_contents($this->documentationPath . '/Makefile', $view->render());

		// Create build directory
		\TYPO3\FLOW3\Utility\Files::createDirectoryRecursively($this->buildPath);

		// First clean directory
		$this->output('Generating Documentation for "' . $this->repositoryName . '"');
		$command = "cd {$this->documentationPath}; make clean --quiet;";
		$this->run($command);
		$command = "cd {$this->documentationPath}; make html --quiet;";
		$this->run($command);
		$this->output("Job ended successfully!\n");

		return TRUE;
	}

	/**
	 * Get the identifier
	 *
	 * @return string
	 */
	public function getIdentifier() {
		return 'documentationRender';
	}

	/**
	 * Get the label
	 *
	 * @return string
	 */
	public function getLabel() {
		return 'Documentation Render ' . $this->origin;
	}

	/**
	 * Output message on the console.
	 *
	 * @return void
	 */
	protected function output($message = '') {
		if (is_array($message) || is_object($message)) {
			print_r($message);
		} elseif (is_bool($message)) {
			var_dump($message);
		} else {
			print $message . PHP_EOL;
		}
	}

	/**
	 * Run a command
	 *
	 * @param string $command the command to be executed
	 * @param boolean $run whether the command needs to be executed or simply echoed
	 * @return void
	 */
	protected function run($command, $run = TRUE) {
		if (! $this->dryRun && $run) {
			exec($command, $output, $return);
			if ($return !== 0) {
				$message = 'Exception 1342444646: Something when wrong with command "' . $command . '"';
				throw new \RuntimeException(sprintf($message, $command));
			}
		}
		else {
			$this->output($command);
		}
	}
}
?>