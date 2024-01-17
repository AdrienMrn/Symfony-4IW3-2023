<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Mailer
{
    public function __construct(private ParameterBagInterface $parameterBag){}

    /**
     * @throws Exception
     */
    public function sendTemplate(int $templateId, array $to, array $params = null): string
    {
        // TODO: Use a proper API key in .env
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->parameterBag->get('sendinblue_api_key'));

        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $sendSmtpEmail = new SendSmtpEmail();
        if (count($to) === 1) {
            $sendSmtpEmail['to'] = $to;
        } else {
            $sendSmtpEmail['to'] = [['email' => 'no-reply@test.fr', 'name' => 'Module']];
            $sendSmtpEmail['bcc'] = $to;
        }
        $sendSmtpEmail['templateId'] = $templateId;
        $sendSmtpEmail['params'] = $params;
        //$sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'custom_header_1:custom_value_1|custom_header_2:custom_value_2');

        try {
            return $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
            throw new Exception('Exception when calling TransactionalEmailsApi->sendTransacEmail: ' . $e->getMessage());
        }
    }
}
