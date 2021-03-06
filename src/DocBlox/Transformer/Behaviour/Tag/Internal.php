<?php
/**
 * @category   DocBlox
 * @package    Transformer
 * @subpackage Behaviour
 * @author	   Stepan Anchugov <kix@kixlive.ru>
 * @license	   http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://docblox-project.org
 */

/**
 * Behaviour that adds support for @internal tag
 *
 * @category   DocBlox
 * @package    Transformer
 * @subpackage Behaviour
 * @author     Stepan Anchugov <kix@kixlive.ru>
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://docblox-project.org
 */
class DocBlox_Transformer_Behaviour_Tag_Internal extends
    DocBlox_Transformer_Behaviour_Tag_Ignore
{
    protected $tag = 'internal';

    public function process(DOMDocument $xml)
    {
        if (!$this->getTransformer()->getParseprivate()) {
            $xml = parent::process($xml);
        }

        $this->log('Removing @internal inline tags');

        $ignoreQry = '//long-description[contains(., "{@internal")]';

        $xpath = new DOMXPath($xml);
        $nodes = $xpath->query($ignoreQry);

        // either replace it with nothing or with the 'stored' value
        $replacement = $this->getTransformer()->getParseprivate() ? '$1' : '';

        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            $node->nodeValue = preg_replace(
                '/\{@internal\s(.+?)\}\}/', $replacement, $node->nodeValue
            );
        }

        return $xml;
    }


}