<?php
namespace Scarbous\MrFlashMessage\ViewHelpers;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * View helper which renders the flash messages (if there are any) as an unsorted list.
 *
 * In case you need custom Flash Message HTML output, please write your own ViewHelper for the moment.
 *
 *
 * = Examples =
 *
 * <code title="Simple">
 * <f:flashMessages />
 * </code>
 * <output>
 * An ul-list of flash messages.
 * </output>
 *
 * <code title="Output with custom css class">
 * <f:flashMessages class="specialClass" />
 * </code>
 * <output>
 * <ul class="specialClass">
 * ...
 * </ul>
 * </output>
 *
 * <code title="TYPO3 core style">
 * <f:flashMessages />
 * </code>
 * <output>
 * <ul class="typo3-messages">
 * <li class="alert alert-ok">
 * <h4>Some Message Header</h4>
 * Some message body
 * </li>
 * <li class="alert alert-notice">
 * Some notice message without header
 * </li>
 * </ul>
 * </output>
 * <code title="Output flash messages as a description list">
 * <f:flashMessages as="flashMessages">
 *    <dl class="messages">
 *    <f:for each="{flashMessages}" as="flashMessage">
 *        <dt>{flashMessage.code}</dt>
 *        <dd>{flashMessage.message}</dd>
 *    </f:for>
 *    </dl>
 * </f:flashMessages>
 * </code>
 * <output>
 * <dl class="messages">
 *    <dt>1013</dt>
 *    <dd>Some Warning Message.</dd>
 * </dl>
 * </output>
 *
 * <code title="Using a specific queue">
 * <f:flashMessages queueIdentifier="myQueue" />
 * </code>
 *
 * @api
 */
class FlashMessagesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    const NOTICE = -2;
    const INFO = -1;
    const OK = 0;
    const WARNING = 1;
    const ERROR = 2;

    /**
     * Initialize arguments
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('queueIdentifier', 'string', 'Flash-message queue to use', false);
    }

    /**
     * Renders FlashMessages and flushes the FlashMessage queue
     *
     * @return string rendered Flash Messages, if there are any.
     * @api
     */
    public function render()
    {
        $queueIdentifier = isset($this->arguments['queueIdentifier']) ? $this->arguments['queueIdentifier'] : null;
        $flashMessages = $this->controllerContext->getFlashMessageQueue($queueIdentifier)->getAllMessagesAndFlush();
        if ($flashMessages === null || count($flashMessages) === 0) {
            return '';
        }
        $growlMessages = array();
        foreach ($flashMessages as $singleFlashMessage) {
            $growlMessages[] = array(
                'type' => self::getGrowlTypeBySeverity($singleFlashMessage->getSeverity()),
                'message' => '<div class="head">' . $singleFlashMessage->getTitle() . '</div>'
                    . $singleFlashMessage->getMessage(),

            );
        }
        if (count($growlMessages) > 0) {
            $script = array();
            foreach ($growlMessages as $gm) {
                $script[] = 'flashmessages.push(' . json_encode($gm) . ')';
            }
            return '<script>' . implode($script, ';') . ';</script>';
        }
        return '';
    }

    /**
     * @param int $severity
     * @return string
     */
    protected static function getGrowlTypeBySeverity($severity)
    {
        switch ($severity) {
            case self::OK:
                return 'success';
                break;
            case self::WARNING:
                return 'warning';
                break;
            case self::ERROR:
                return 'danger';
                break;
            case self::NOTICE:
            case self::INFO:
            default:
                return 'info';
                break;
        }
    }
}
