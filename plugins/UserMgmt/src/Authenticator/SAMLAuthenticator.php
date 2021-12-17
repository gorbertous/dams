<?php
declare(strict_types=1);

use UserMgmt\Controller\AppController;
use UserMgmt\Model\Table;
use Cake\ORM\TableLocator;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Utils;

use CakeDC\Users;

class SAMLAuthenticator extends AbstractAuthenticator implements PersistenceInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [];

    private $spBaseUrl;

    public function __construct()
    {
        Utils::setProxyVars(true); //https://github.com/onelogin/php-saml/issues/56

        $this->spBaseUrl = env('DEFAULT_PROTOCOL') . $_SERVER['HTTP_HOST'];
    }

    /**
     * @return OneLogin\Saml2\Auth
     */
    private function initAuthObject(int $Id)
    {
        $ssoInfo = $this->Sso->findByTeamId($Id)->first();
        $settings = [];
        $metadataInfo = $this->setMetadata($ssoInfo);
        $settingsInfo = IdPMetadataParser::injectIntoSettings($settings, $metadataInfo);

        return new Auth($settingsInfo);
    }

    private function setMetadata(object $ssoInfo): array
    {
        $metadataInfo = [
            'sp' => [
                'entityId' => $this->spBaseUrl . '/saml/metadata',
                'assertionConsumerService' => [
                    'url' => $this->spBaseUrl . '/saml/acs'
                ],
                'NameIDFormat' => $ssoInfo['nameid_format'],
            ],
            'idp' => [
                'entityId' => $ssoInfo['entity_id'],
                'singleSignOnService' => [
                    'url' => $ssoInfo['sso_url']
                ],
                'singleLogoutService' => [
                    'url' => $ssoInfo['slo_url']
                ],
                'x509cert' => $ssoInfo['certificate']
            ],
        ];

        return $metadataInfo;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request request
     * @return \Authentication\Authenticator\ResultInterface
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        $auth = $this->initAuthObject($Id);

        if (empty($_REQUEST['SAMLResponse']) || empty($_REQUEST['RelayState'])){
            return new Result(null, Result::FAILURE_CREDENTIALS_MISSING);
        }

        $auth->processResponse();
        $errors = $auth->getErrors();
        if (!empty($errors)){
            $reason = $auth->getLastErrorReason();
            Log::info('SAMLAuthenticator::authenticate:' . $reason);
            return new Result(null, Result::FAILURE_CREDENTIALS_INVALID);
        }

        $attributes = $auth->getAttributes();
        if (empty($attributes)) {
            return  new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND);
        }

        $user = new ArrayObject($attributes);
        return new Result($user, Result::SUCCESS);
    }
}