<?php
namespace TYPO3\Docs\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Docs".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Documentation
 *
 * @FLOW3\Entity
 */
class Documentation {

	/**
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $abstract;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var int
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $version;

	/**
	 * @var \Date
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $generationDate;

	/**
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $language;

	/**
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $pluginKey;

//	protected $authorName;
//
//	protected $authorEmail;

	/**
	 * @var \TYPO3\Docs\Domain\Model\Category
	 * @ORM\ManyToMany(inversedBy="categories")
	 */
	protected $categories;


}

?>