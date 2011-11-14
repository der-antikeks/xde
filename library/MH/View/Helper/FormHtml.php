<?php

/**
 * Helper to show HTML
 *
 */
class MH_View_Helper_FormHtml extends Zend_View_Helper_FormElement
{
    /**
     * Helper to show a html in a form
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formHtml($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // Get content
        $content = '';
        if (isset($attribs['content'])) {
            $content = $attribs['content'];
            unset($attribs['content']);
        } else {
            $content = $value;
        }

        // Ensure type is sane
        $type = 'button';
        if (isset($attribs['type'])) {
            $attribs['type'] = strtolower($attribs['type']);
			$type = $attribs['type'];
            unset($attribs['type']);
        }

        // build the element
        if ($disable) {
            $attribs['disabled'] = 'disabled';
        }

        $content = ($escape) ? $this->view->escape($content) : $content;

        $xhtml = '<' . $type
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"';

        // add a value if one is given
        if (!empty($value)) {
            $xhtml .= ' value="' . $this->view->escape($value) . '"';
        }

        // add attributes and close start tag
        $xhtml .= $this->_htmlAttribs($attribs) . '>';

        // add content and end tag
        $xhtml .= $content . '</' . $type . '>';

        return $xhtml;
    }
}

