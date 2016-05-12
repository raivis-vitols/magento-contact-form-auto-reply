<?php
/**
 * @category    ArchApps
 * @package     ArchApps_ContactFormAutoReply
 * @license     https://opensource.org/licenses/osl-3.0.php OSL 3.0
 */

class ArchApps_ContactFormAutoReply_Model_Observer
{
    /**
     * Send the auto reply email after user submits successful contact email
     *
     * @param Varien_Event_Observer $observer Default event observer object
     */
    public function sendAutoReplyEmail(Varien_Event_Observer $observer)
    {
        /** @var ArchApps_ContactFormAutoReply_Helper_Data $helper */
        $helper = Mage::helper('archapps_contactformautoreply');

        if (!$helper->isEnabled()) {
            return;
        }

        // Check if last added message is either empty or is not an error
        // type - to find out if contact email was submitted successfully
        // and whether we should proceed with sending auto reply

        /** @var Mage_Core_Model_Message_Collection $messages */
        $messages = Mage::getSingleton('customer/session')->getMessages();

        /** @var Mage_Core_Model_Message_Abstract $lastMessage */
        $lastMessage = $messages->getLastAddedMessage();

        $sendAutoReplyEmail = !$lastMessage
            || $lastMessage->getType() !== Mage_Core_Model_Message::ERROR;

        if (!$sendAutoReplyEmail) {
            return;
        }

        // Collect post data into Varien_Object and pass the data into an
        // email template. Make sure email is translated to current store
        // view language. Send auto reply email.

        /** @var Mage_Contacts_IndexController $controller */
        $controller = $observer->getEvent()->getControllerAction();

        $postData = new Varien_Object();
        $postData->setData($controller->getRequest()->getPost());

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        /* @var $emailTemplate Mage_Core_Model_Email_Template */
        $emailTemplate = Mage::getModel('core/email_template');

        try {
            $emailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->sendTransactional(
                    $helper->getEmailTemplate(),
                    $helper->getEmailSender(),
                    $postData->getData('email'),
                    null,
                    array('data' => $postData)
                );

            $translate->setTranslateInline(true);
        } catch (Exception $exception) {
            // If something failed while sending auto reply email, there is
            // nothing we can do - this customer will simply not receive it

            $translate->setTranslateInline(true);
        }
    }
}
