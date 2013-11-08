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
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package
 */


namespace qtism\data\storage\xml\marshalling;

use qtism\data\content\xhtml\text\Div;
use qtism\data\content\xhtml\Object;
use qtism\data\content\xhtml\lists\DlElement;
use qtism\data\content\xhtml\lists\Dl;
use qtism\data\content\xhtml\lists\Ol;
use qtism\data\content\xhtml\lists\Ul;
use qtism\data\content\xhtml\lists\Li;
use qtism\data\content\AtomicBlock;
use qtism\data\content\xhtml\tables\Th;
use qtism\data\content\xhtml\tables\Caption;
use qtism\data\content\xhtml\tables\Td;
use qtism\data\content\xhtml\tables\Tr;
use qtism\data\content\SimpleBlock;
use qtism\data\content\SimpleInline;
use \DOMElement;
use \DOMNode;
use \DOMText;
use qtism\data\QtiComponentCollection;
use qtism\data\QtiComponent;
use \InvalidArgumentException;

abstract class ContentMarshaller extends RecursiveMarshaller {
    
    public function __construct() {
        $this->setLookupClasses();
    }
    
    /**
     * 
     * @var array
     */
    protected $lookupClasses;
    
    private static $finals = array('textRun', 'br', 'param', 'hr', 'col', 'img', 'math', 'table', 'colgroup', 'tbody',
                                      'thead', 'tfoot',
                                      'printedVariable', 'stylesheet', 'choiceInteraction', 'orderInteraction',
                                      'associateInteraction', 'matchInteraction', 'gapMatchInteraction',
                                      'inlineChoiceInteraction', 'textEntryInteraction', 'extendedTextInteraction',
                                      'hottextInteraction', 'hotspotInteraction', 'selectPointInteraction',
                                      'graphicOrderInteraction', 'graphicAssociateInteraction', 'graphicGapMatchInteraction',
                                      'positionObjectInteraction', 'positionObjectStage', 'sliderInteraction', 'mediaInteraction',
                                      'drawingInteraction', 'uploadInteraction', 'customInteraction');
    
    private static $simpleComposites = array('a', 'abbr', 'acronym', 'b', 'big', 'cite', 'code', 'dfn', 'em', 'feedbackInline', 'i',
                                             'kbd', 'q', 'samp', 'small', 'span', 'strong', 'sub', 'sup', 'tt', 'var', 'td', 'th', 'object',
                                             'caption', 'address', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'li', 'dd', 'dt', 'div');
    
    protected function isElementFinal(DOMNode $element) {
        return $element instanceof DOMText || ($element instanceof DOMElement && in_array($element->nodeName, self::$finals));
    }
    
    protected function isComponentFinal(QtiComponent $component) { 
        return in_array($component->getQtiClassName(), self::$finals);
    }
    
    protected function createCollection(DOMElement $currentNode) {
        return new QtiComponentCollection();
    }
    
    protected function getChildrenComponents(QtiComponent $component) {
        if ($component instanceof SimpleInline) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof AtomicBlock) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Tr) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Td) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Th) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Caption) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Ul) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Ol) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Li) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Dl) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof DlElement) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Object) {
            return $component->getContent()->getArrayCopy();
        }
        else if ($component instanceof Div) {
            return $component->getContent()->getArrayCopy();
        }
    }
    
    protected function getChildrenElements(DOMElement $element) {
        if (in_array($element->nodeName, self::$simpleComposites) === true) {
            return self::getChildElements($element, true);
        }
        else if ($element->nodeName === 'tr') {
            return self::getChildElementsByTagName($element, array('td', 'th'));
        }
        else if ($element->nodeName === 'ul' || $element->nodeName === 'ol') {
            return self::getChildElementsByTagName($element, 'li');
        }
        else if ($element->nodeName === 'dl') {
            return self::getChildElementsByTagName($element, array('dd', 'dt'));
        }
    }
    
    public function getExpectedQtiClassName() {
        return '';
    }
    
    protected abstract function setLookupClasses();
    
    /**
     * 
     * @return array
     */
    protected function getLookupClasses() {
        return $this->lookupClasses;
    }
    
    /**
     * Get the related PHP class name of a given $element.
     * 
     * @param DOMElement $element The element you want to know the data model PHP class.
     * @throws UnmarshallingException If no class can be found for $element.
     * @return string A fully qualified class name.
     */
    protected function lookupClass(DOMElement $element) {
        $lookup = $this->getLookupClasses();
        $class = ucfirst($element->nodeName);
        
        foreach ($lookup as $l) {
            $fqClass = $l . "\\" . $class;
        
            if (class_exists($fqClass) === true) {
                return $fqClass;
            }
        }

        $msg = "No class could be found for tag with name '" . $element->nodeName . "'.";
        throw new UnmarshallingException($msg, $element);
    }
}