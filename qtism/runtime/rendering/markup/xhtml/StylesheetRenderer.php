<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package qtism
 * @subpackage
 *
 */

namespace qtism\runtime\rendering\markup\xhtml;

use qtism\data\QtiComponent;
use \DOMDocumentFragment;

/**
 * Stylesheet renderer.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class StylesheetRenderer extends AbstractXhtmlRenderer {
    
    protected function appendElement(DOMDocumentFragment $fragment, QtiComponent $component) {
        $fragment->appendChild($this->getRenderingContext()->getDocument()->createElement('stylesheet'));
    }
    
    public function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component) {
        $fragment->firstChild->setAttribute('rel', 'stylesheet');
        $fragment->firstChild->setAttribute('href', $component->getHref());
        
        if ($component->hasMedia() === true) {
            $fragment->firstChild->setAttribute('media', $component->getMedia());
        }
        
        if ($component->hasTitle() === true) {
            $fragment->firstChild->setAttribute('title', $component->getTitle());
        }
    }
}