<?php
/**
 * @category    ArchApps
 * @package     ArchApps_ContactFormAutoReply
 * @license     https://opensource.org/licenses/osl-3.0.php OSL 3.0
 */

class ArchApps_ContactFormAutoReply_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED        = 'contacts/autoreply/enabled';
    const XML_PATH_EMAIL_SENDER   = 'contacts/autoreply/email_sender';
    const XML_PATH_EMAIL_TEMPLATE = 'contacts/autoreply/email_template';

    /**
     * Whether auto reply feature is enabled for contact form
     *
     * @return mixed
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig(self::XML_PATH_ENABLED);
    }

    /**
     * Returns the auto reply email sender information
     *
     * @return mixed
     */
    public function getEmailSender()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER);
    }

    /**
     * Returns the contact form auto reply email template
     *
     * @return mixed
     */
    public function getEmailTemplate()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE);
    }
}
