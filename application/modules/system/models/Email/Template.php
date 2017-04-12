<?php
/**
 * @property Core_Entity_Rowset_Abstract $locales
 */
class System_Model_Email_Template extends Core_Entity_Model_Abstract
{
    /**
     * Filling email template and returning it as Zend_Mail instance.
     *
     * @param string $sendTo Email address to send to.
     * @param string $locale Email locale language.
     * @param array  $data  Data to pipe to text and subject.
     *
     * @return Zend_Mail
     */
    public function getMail($sendTo, $locale, array $data = array())
    {
        /** @var Core_Entity_Rowset_Abstract $locales  */
        $locales = $this->locales->setAssocKey('languageId')->enableAssocMode();
        if(count($locales) == 0)
        {
            return false;
        }
        if(!isset($locales[$locale]))
        {
            $locale = DEFAULT_LANGUAGE;
        }
        if(!isset($locales[$locale]))
        {
            $locale = $locales->disableAssocMode()->offsetGet(0);
        }
        else
        {
            $locale = $locales[$locale];
        }


        $messageSubject  = $locale->subject;
        $messageBody     = $locale->textBody;
        $messageBodyHtml = $locale->htmlBody;

        $variables = explode(',', $this->variables);
        if(empty($variables))
        {
            $variables = array();
        }
        foreach($variables as $var)
        {
            $replace = !empty($data[$var]) ? $data[$var] : null;
            $messageSubject  = str_replace('{%' . $var . '%}', $replace, $messageSubject);
            $messageBody     = str_replace('{%' . $var . '%}', strip_tags($replace), $messageBody);
            $messageBodyHtml = str_replace('{%' . $var . '%}', $replace, $messageBodyHtml);
        }

        if(!is_array($sendTo))
        {
            $sendTo = array($sendTo);
        }

        $mail = new Zend_Mail('UTF-8');
        $mail->setFrom($this->from, $locale->fromName);
        $mail->setDefaultFrom($this->from, $locale->fromName);
        $mail->setReplyTo($this->from);
        $mail->setDefaultReplyTo($this->from);

        $mail->setSubject($messageSubject);
        if(!empty($messageBodyHtml))
        {
            $mail->setBodyHtml($messageBodyHtml);
        }
        $mail->setBodyText($messageBody);
        $mail->addTo($sendTo[0]);
        $mail->addHeader('X-Mailer', 'W.A.C v1.0 ©Shturman');
        $mail->addHeader('X-Priority', '3 (Normal)');
        return $mail;
    }
}