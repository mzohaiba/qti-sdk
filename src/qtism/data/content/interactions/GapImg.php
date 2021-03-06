<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * Copyright (c) 2013-2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\content\interactions;

use qtism\data\QtiComponentCollection;
use qtism\data\content\xhtml\ObjectElement;
use \InvalidArgumentException;

/**
 * From IMS QTI:
 *
 * A gap image contains a single image object to be inserted into a
 * gap by the candidate.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class GapImg extends GapChoice
{
    /**
     * From IMS QTI:
     *
     * An optional label for the image object to be inserted.
     *
     * @var string
     * @qtism-bean-property
     */
    private $objectLabel = '';

    /**
     * The image as an ObjectElement object.
     *
     * @var ObjectElement
     * @qtism-bean-property
     */
    private $object;

    /**
     * Create a new GapImg object.
     *
     * @param string $identifier The identifier of the response associated to the GapImg object.
     * @param integer $matchMax The maximum number of choice association.
     * @param ObjectElement $object An image as an ObjectElement object.
     * @param string $id The id of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws \InvalidArgumentException If one of the argument is invalid.
     */
    public function __construct($identifier, $matchMax, ObjectElement $object, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($identifier, $matchMax, $id, $class, $lang, $label);
        $this->setObject($object);
        $this->setObjectLabel('');
    }

    /**
     * Set an optional label for the image object to be inserted. An empty
     * string indicates the GapImg has no objectLabel.
     *
     * @param string $objectLabel A label for the image.
     * @throws \InvalidArgumentException If $objectLabel is not a string value.
     */
    public function setObjectLabel($objectLabel)
    {
        if (is_string($objectLabel) === true) {
            $this->objectLabel = $objectLabel;
        } else {
            $msg = "The 'objectLabel' argument must be a string, '" . gettype($objectLabel) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the optional label for the image object to be inserted. An empty
     * string indicates the GapImg has no objectLabel.
     *
     * @return string A label for the image.
     */
    public function getObjectLabel()
    {
        return $this->objectLabel;
    }

    /**
     * Whether a value is defined for the 'objectLabel' attribute.
     *
     * @return boolean
     */
    public function hasObjectLabel()
    {
        return $this->getObjectLabel() !== '';
    }

    /**
     * Set the ObjectElement representing the GapImg's image.
     *
     * @param \qtism\data\content\xhtml\ObjectElement $object An ObjectElement object.
     */
    public function setObject(ObjectElement $object)
    {
        $this->object = $object;
    }

    /**
     * Get the ObjectElement representing the GapImg's image.
     *
     * @return \qtism\data\content\xhtml\ObjectElement An ObjectElement object.
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @see \qtism\data\QtiComponent::getComponents()
     */
    public function getComponents()
    {
        return new QtiComponentCollection(array($this->getObject()));
    }

    /**
     * @see \qtism\data\QtiComponent::getQtiClassName()
     */
    public function getQtiClassName()
    {
        return 'gapImg';
    }
}
