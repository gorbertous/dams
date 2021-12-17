<?php

declare(strict_types=1);

namespace UserMgmt\Controller;

use UserMgmt\Controller\AppController;
use UserMgmt\Model\Table;
use Cake\ORM\TableLocator;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Utils;
use Authentication\Identity;
use CakeDC\Users;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

/**
 * Sso Controller
 *
 * @method \UserMgmt\Model\Entity\Sso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SsoController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        try {
            Configure::config('default', new PhpConfig());
            Configure::load('app', 'default', false);
        } catch (\Exception $e) {
            exit($e->getMessage() . "\n");
        }
        Configure::load('app_local', 'default');
        $this->sso_settings = [
            // If 'strict' is True, then the PHP Toolkit will reject unsigned
            // or unencrypted messages if it expects them signed or encrypted
            // Also will reject the messages if not strictly follow the SAML
            // standard: Destination, NameId, Conditions ... are validated too.
            'strict' => false,
            // Enable debug mode (to print errors)
            'debug' => true,
            // Set a BaseURL to be used instead of try to guess 
            // the BaseURL of the view that process the SAML Message.
            // Ex. http://sp.example.com/
            //     http://example.com/sp/
            'baseurl' => Configure::read('SSO.baseurl'),
            // Service Provider Data that we are deploying
            'sp' => array(
                // Identifier of the SP entity  (must be a URI)
                'entityId'                 => Configure::read('SSO.baseurl') . '/user-mgmt/sso/metadata',
                // Specifies info about where and how the <AuthnResponse> message MUST be
                // returned to the requester, in this case our SP.
                'assertionConsumerService' => array(
                    // URL Location where the <Response> from the IdP will be returned
                    'url'     => Configure::read('SSO.baseurl') . '/user-mgmt/sso/assert',
                    // SAML protocol binding to be used when returning the <Response>
                    // message.  Onelogin Toolkit supports for this endpoint the
                    // HTTP-Redirect binding only
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                ),
                // If you need to specify requested attributes, set a
                // attributeConsumingService. nameFormat, attributeValue and
                // friendlyName can be omitted. Otherwise remove this section. 
                /* "attributeConsumingService"=> array(
                  "ServiceName" => "SP test",
                  "serviceDescription" => "Test Service",
                  "requestedAttributes" => array(
                  array(
                  "name" => "",
                  "isRequired" => false,
                  "nameFormat" => "",
                  "friendlyName" => "",
                  "attributeValue" => ""
                  )
                  )
                  ), */
                // Specifies info about where and how the <Logout Response> message MUST be
                // returned to the requester, in this case our SP.
                'singleLogoutService'      => array(
                    // URL Location where the <Response> from the IdP will be returned
                    'url'     => Configure::read('SSO.baseurl') . '/user-mgmt/sso/logout',
                    // SAML protocol binding to be used when returning the <Response>
                    // message.  Onelogin Toolkit supports for this endpoint the
                    // HTTP-Redirect binding only
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                ),
                // Specifies constraints on the name identifier to be used to
                // represent the requested subject.
                // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
                'NameIDFormat'             => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
                // Usually x509cert and privateKey of the SP are provided by files placed at
                // the certs folder. But we can also provide them with the following parameters
                'x509cert'   => '-----BEGIN CERTIFICATE-----MIICpjCCAg+gAwIBAgIBADANBgkqhkiG9w0BAQ0FADBwMQswCQYDVQQGEwJsdTETMBEGA1UECAwKbHV4ZW1ib3VyZzEMMAoGA1UECgwDRUlGMRcwFQYDVQQDDA52bWQuZWlmYXdzLmNvbTESMBAGA1UEBwwJa2lyY2hiZXJnMREwDwYDVQQLDAhFSUYgTUlCTzAeFw0xOTA0MjYxNDIzNTlaFw0yMDA0MjUxNDIzNTlaMHAxCzAJBgNVBAYTAmx1MRMwEQYDVQQIDApsdXhlbWJvdXJnMQwwCgYDVQQKDANFSUYxFzAVBgNVBAMMDnZtZC5laWZhd3MuY29tMRIwEAYDVQQHDAlraXJjaGJlcmcxETAPBgNVBAsMCEVJRiBNSUJPMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDgLhjMrkDiwrNd1PW9hoHEDy2tI9pZoxBD6UJI/h/EwxEWRtRwmo0dJLkXEeNvlEqSLQatAGoSHNSxvstDys8UxO3PusBaQXb74UHog7VaWqosCtolUEOpHx0zv4UmBsiWPBEgNtDP+aRN5lZfZM69XCSk+K4dVOIq4O5/t1+k2QIDAQABo1AwTjAdBgNVHQ4EFgQUr6hsGGwlZctxvlYLIthhE94zkJgwHwYDVR0jBBgwFoAUr6hsGGwlZctxvlYLIthhE94zkJgwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQ0FAAOBgQBIp2ixFxtihXQukDGQMosXlOj0LEnCaIHR5HTSvUsGpuIeodB6hjEUfCfMQMLfYwY4iP/a6YFhvuzsB+3F4Xra1O6QrU0njOAW+yyn26nls42WgU+BVSEqFPTrYzcK3lUJ+i2Zn9dJi32Nv22+tEaGwkTPOSR3SGjHVjx+r6FrKw==-----END CERTIFICATE-----',
                'privateKey' => '-----BEGIN PRIVATE KEY-----MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAOAuGMyuQOLCs13U9b2GgcQPLa0j2lmjEEPpQkj+H8TDERZG1HCajR0kuRcR42+USpItBq0AahIc1LG+y0PKzxTE7c+6wFpBdvvhQeiDtVpaqiwK2iVQQ6kfHTO/hSYGyJY8ESA20M/5pE3mVl9kzr1cJKT4rh1U4irg7n+3X6TZAgMBAAECgYEAwUVcohHU5OrA4rN4DSaKd7IySePwGnlny3StbeqRDvyxnYgBHPdRk3+Whxon/4lUZQOmjr4dtHHguUDpUw/lSMAe0BUVtBTHDf20OGgwsmsR9TcWuKQepQnMfj0mJc72/JP+QHP0C0GyktYAjbmoNWU2FXhCOWmcoJl4ks7s/DUCQQD68NjXQSvVoSX0TBT3GR8/3zs1cxQ6grnXQ7+vvXZ2bKVulD2rbEu/xrLw40MGSCO0LIGwZc2BSJlW+3akTxqTAkEA5LMh8RFbM9CROGKUtVxUNPT6IqQahzATqD3k35ntsg5rS6RrY+BgZ+1vD5YsDwuPHYjkaib763D2MTTdEErqYwJBAOyKOtwswnUNAgnV7a0+MQa0Fcu8PkUpFKSlZ/rwTMo0f3xMoBUGcCDF28bVckhpl74uddEhJSXImUI0pxEav6ECQQDaK+ldR+lxbK6c0655tTK/slRNZ6/G955JCYKlrPqMuiSxbJDmHs7ZVjB09hXh5G0LB+SfP8Fjwcea+YtKV61xAkBIlyWhkFTBNJce9wfjOB+w5C7O7I4m5lOpb9eytOr7Ewpq8oVpmc98VnHJOyh+M4lUeG+me1IYEjUjG3s1XyW4-----END PRIVATE KEY-----',
            ),
            // Identity Provider Data that we want connect with our SP
            'idp' => array(
                // Identifier of the IdP entity  (must be a URI)
                'entityId'                 => Configure::read('SSO.baseentityurl') . '/saml/metadata/' . Configure::read('SSO.ssoserial'),
                // SSO endpoint info of the IdP. (Authentication Request protocol)
                'singleSignOnService'      => array(
                    // URL Target of the IdP where the SP will send the Authentication Request Message
                    'url'     => Configure::read('SSO.endpointurl') . '/trust/saml2/http-post/sso/' . Configure::read('SSO.ssoserial'),
                    // SAML protocol binding to be used when returning the <Response>
                    // message.  Onelogin Toolkit supports for this endpoint the
                    // HTTP-POST binding only
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                ),
                // SLO endpoint info of the IdP.
                'singleLogoutService'      => array(
                    // URL Location of the IdP where the SP will send the SLO Request
                    'url'     => Configure::read('SSO.endpointurl') . '/trust/saml2/http-redirect/slo/' . Configure::read('SSO.slo'),
                    // SAML protocol binding to be used when returning the <Response>
                    // message.  Onelogin Toolkit supports for this endpoint the
                    // HTTP-Redirect binding only
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                ),
                // Public x509 certificate of the IdP
                'x509cert'                 => 'MIIEazCCA1OgAwIBAgIUWHtpGJeBl76z/kNA7TsY0bXG2YUwDQYJKoZIhvcNAQEFBQAwdDELMAkGA1UEBhMCVVMxEzARBgNVBAcMCkx1eGVtYm91cmcxGDAWBgNVBAoMD2luZm9ybWF0aW9uc2xhYjEVMBMGA1UECwwMT25lTG9naW4gSWRQMR8wHQYDVQQDDBZPbmVMb2dpbiBBY2NvdW50IDM3NDc2MB4XDTE3MTAzMDEzMDcyOVoXDTIyMTAzMTEzMDcyOVowdDELMAkGA1UEBhMCVVMxEzARBgNVBAcMCkx1eGVtYm91cmcxGDAWBgNVBAoMD2luZm9ybWF0aW9uc2xhYjEVMBMGA1UECwwMT25lTG9naW4gSWRQMR8wHQYDVQQDDBZPbmVMb2dpbiBBY2NvdW50IDM3NDc2MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAz4fEo/t7KePERjbYFzDeTiU3TpSAbpF+rJxXwGzSIjoiSNTCQi3Em5XFyhouN1GxaYpJab9NAiiQEo1l3s0CYrNp3OrnGDHOMZU7FnGK025VmsTVfoSr4Yt0VIrDGHw/bsGtP/I6BI1i8Aip3pEs2nq76HtCxLOONqwhGylLjkVIF89dUD4qmrSMBOE2aTHTcgWz1zeMWrosVN2A4whyS+JxG8BSgqY83uvHV41Z8zNJDuZ4xmO16oYPAqDgjPlP31G7xEegqcZX0RHxQcgbJOUBGtsJk8qMX4fOksEg0ubmOvbF65et8h4mqj10/Tsus2rege5L3fyWbs6Gm+LXlwIDAQABo4H0MIHxMAwGA1UdEwEB/wQCMAAwHQYDVR0OBBYEFFGK1fybKc3QnGpxjd9egERtAnC8MIGxBgNVHSMEgakwgaaAFFGK1fybKc3QnGpxjd9egERtAnC8oXikdjB0MQswCQYDVQQGEwJVUzETMBEGA1UEBwwKTHV4ZW1ib3VyZzEYMBYGA1UECgwPaW5mb3JtYXRpb25zbGFiMRUwEwYDVQQLDAxPbmVMb2dpbiBJZFAxHzAdBgNVBAMMFk9uZUxvZ2luIEFjY291bnQgMzc0NzaCFFh7aRiXgZe+s/5DQO07GNG1xtmFMA4GA1UdDwEB/wQEAwIHgDANBgkqhkiG9w0BAQUFAAOCAQEAL6er5jJ6q/U6Qapes0zkV0d32DVTrjfd5604RYx2H2tCdDvqqf9amwb9CMndFnbQD1xZZ/j3LqTX0ycPL/1WbBCM8Xl108j/BQ+Dl/W3bwXSD8yX17+pmOVSvUgQjUN/BuHLl1MSVml9VjPpQ/f9lIt65siNeftcPx2BTP8/YMr+NzsUg8VAIKyz9eqjtNj2WmxLsym8TRZ5v9EDMJ6cbdkbFrxSyURm3Bu61dleybWh4P0gu0onFyYLKFmi23bVx8loN/hZHS1gM6WnHNixL151qguyPFdNr699oqUuiOPt+16CU+5upITAD3r7aPEsWGT8UXLKruiRj7eltQHrvg==',
                /*
                 *  Instead of use the whole x509cert you can use a fingerprint
                 *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
                 *   or add for example the -sha256 , -sha384 or -sha512 parameter)
                 *
                 *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
                 *  let the toolkit know which Algorithm was used. Possible values: sha1, sha256, sha384 or sha512
                 *  'sha1' is the default value.
                 */
                //'certFingerprint' => '',
                'certFingerprintAlgorithm' => 'sha256',
            ),
        ];

        $this->loadComponent('Security');
        $this->loadComponent('Authentication.Authentication');
        $this->Authentication->allowUnauthenticated(['assert', 'metadata']);
        //$this->loadComponent('Cookie');//prevent bug session does not exists
    }

    public function beforeFilter(\cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['assert', 'metadata']);
    }

    private $sso_settings;

    public function assert()
    {
        /* if($_POST['SAMLResponse'] == null){
          $this->redirect(env('SSO_BASEURL'));
          } */
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();
        if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
            $requestID = $_SESSION['AuthNRequestID'];
        } else {
            $requestID = null;
        }
        unset($_POST['RelayState']); // unused, should be empty

        $auth = new Auth($this->sso_settings);

        if (isset($_POST['SAMLResponse'])) {
            $auth->processResponse($requestID);
        } else {
            try {
                $auth->login();
            } catch (Exception $e) {
                error_log("OneLogin error :exception message " . $e->getMessage());
                error_log("OneLogin error :saml responsee " . json_encode($POST));
                error_log("OneLogin error :saml responsee " . json_encode($_POST['SAMLResponse']));
                error_log("OneLogin error : request id " . $requestID);
                error_log("OneLogin error : SESSION " . json_encode($_SESSION));
                error_log("OneLogin error : session " . json_encode($session));
                error_log("OneLogin error : auth geterror " . json_encode($auth->getErrors()));
                error_log("OneLogin error : getLastErrorReason " . json_encode($auth->getLastErrorReason()));
                error_log("OneLogin error : latest errors ");

                //$this->redirect(env('SSO_BASEURL'));
            }
        }

        $errors = $auth->getErrors();

        if (!empty($errors)) {
            //$this->set('auth_response_error', $errors);
            error_log("OneLogin error : " . json_encode($auth->getLastErrorReason()));
            unset($_SESSION);
            $this->Session->setFlash(json_encode($auth->getLastErrorReason()), 'flash/warning');
            $this->Session->setFlash(__('The OneLogin configuration is incorrect'), 'flash/error');
            $this->redirect("/accessDenied");
        }

        /* if (!$auth->isAuthenticated()) {
          $this->Session->setFlash(__('The password you entered is incorrect'), 'flash/error');
          $this->redirect(LOGIN_REDIRECT_URL);
          exit();
          } */
        $_SESSION['samlUserdata'] = $auth->getAttributes();
        $_SESSION['samlNameId'] = $auth->getNameId();
        $_SESSION['samlNameIdFormat'] = $auth->getNameIdFormat();
        $_SESSION['samlSessionIndex'] = $auth->getSessionIndex();
        //debug($_SESSION['samlUserdata']['Username']);

        $this->getRequest()->getSession()->write('samlUserdata', $auth->getAttributes());
        $this->getRequest()->getSession()->write('samlNameId', $auth->getNameId());
        $this->getRequest()->getSession()->write('samlNameIdFormat', $auth->getNameIdFormat());
        $this->getRequest()->getSession()->write('samlSessionIndex', $auth->getSessionIndex());
        $this->getRequest()->getSession()->write('dams_timeout', time() + 3600); // session time = 30min = 60*30 = 1800

        if (empty($this->getRequest()->getSession()->read('samlUserdata'))) {
            $this->set('saml_values_error', $_SESSION);
            unset($_SESSION);
            //$this->Session->setFlash(__('The OneLogin user is empty'), 'flash/error');
            //$this->Flash->success(__('The OneLogin user is empty'));
            $this->redirect("/accessDenied");
        }
        $username = null;
        if (!empty($_SESSION['samlUserdata']['Username'])) {
            $username = $_SESSION['samlUserdata']['Username'];
        } elseif (!empty($_SESSION['samlUserdata']['User.Username'])) {
            $username = $_SESSION['samlUserdata']['User.Username'];
        } elseif (!empty($_SESSION['samlUserdata']['username'])) {
            $username = $_SESSION['samlUserdata']['username'];
        }
        if (is_array($username)) {
            $username = $username[0];
        }
        $username = strtolower($username);
        if (!$username) {
            $this->set('saml_user_error', $_SESSION['samlUserdata']);
            unset($_SESSION);
            //$this->Session->setFlash(__('The OneLogin username is empty'), 'flash/error');
            $this->redirect("/accessDenied");
        }
        //@$user = $this->User->findByUsername($username);
        //    Cake\ORM\TableLocator
        //\Cake\ORM\TableLocator\FactoryLocator::get('User')->setConfig('Users', ['table' => 'eif.users']);
        //$this->loadModel('User');
        $User = $this->getTableLocator()->get('UserMgmt.Users');
        $user = $User->find('all', ['contain'])->where(['username' => $username])->first();

        if (empty($user)) {
            error_log("user does not exist : " . json_encode($username));
            $this->set('user_not_found', $username);
            unset($_SESSION);
            //$this->Session->setFlash(__('The user does not exists in SAS'), 'flash/error');
            $this->redirect("/accessDenied");
        } else {
            /* $user['User']['email_verified'] = 1;
              $user['User']['active']         = 1;
              $this->User->save($user); */
        }

        // check for inactive account
        /* if ($user['User']['id'] != 1 and $user['User']['active']==0) {

          $this->Session->setFlash(__('Sorry your account is not active, please contact your Administrator'), 'flash/error');
          $this->redirect("/accessDenied");
          } */

        unset($_SESSION['Message']);

        try {
            // get profiles
            $user_array = $user->toArray();
            error_log("logging user " . json_encode($user_array, 2));
            $Subscriptions = $this->getTableLocator()->get('UserMgmt.Subscriptions');
            $Profile = $this->getTableLocator()->get('UserMgmt.Profiles');
            $subscription = $Subscriptions->find()->where(['user_id' => $user_array['id']])->all();
            $group_ids = array();
            foreach ($subscription as $id) {
                $group_ids[] = $id->group_id;
            }
            if (!empty($group_ids)) {
                $profiles = $Profile->find()->where(['id IN' => $group_ids])->all();
                $profiles_array = array();
                foreach ($profiles as $prof) {
                    $profiles_array[] = array('id' => $prof->id, 'name' => $prof->name, 'alias_name' => $prof->alias_name);
                }
                error_log("logging user profiles " . json_encode($profiles_array, 2));
                $this->getRequest()->getSession()->write('user_profiles', $profiles_array);
            }
        } catch (Exception $e) {
            error_log("getting profile for user " . $username . " failed " . $e->getMessage());
        }
        $identity = new Identity($user);
        $this->Authentication->setIdentity($identity);

        unset($_SESSION['AuthNRequestID']);
        if (empty($_SESSION['Usermgmt']['OriginAfterLogin'])) {
            $auth->redirectTo(Configure::read('SSO.baseurl') . "/damsv2");
        }
        $origin = $_SESSION['Usermgmt']['OriginAfterLogin'];
        //$origin = filter_var($origin, FILTER_VALIDATE_URL);
        //error_log(__LINE__." sso controller : redirecting to ".$origin);
        if (strpos($origin, env('SSO_BASEURL')) != 0) {//first position
            $origin = Configure::read('SSO.baseurl') . "/damsv2";
        }
        $auth->redirectTo($origin);
    }

    public function metadata()
    {
        $this->autoRender = false;
        try {
            #$auth = new OneLogin_Saml2_Auth($settingsInfo);
            #$settings = $auth->getSettings();
            // Now we only validate SP settings
            $settings = new \OneLogin\Saml2\Settings($this->sso_settings, true);
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);
            if (empty($errors)) {
                header('Content-Type: text/xml');
                echo $metadata;
            } else {
                throw new \OneLogin\Saml2\Error(
                        'Invalid SP metadata: ' . implode(', ', $errors),
                        OneLogin_Saml2_Error::METADATA_SP_INVALID
                );
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function logout()
    {
        $this->Authentication->logout();

        $auth = new Auth($this->sso_settings);
        $returnTo = Configure::read('SSO.baseurl');
        $paramters = array();
        $nameId = null;
        $sessionIndex = null;
        $nameIdFormat = null;

        if (isset($_SESSION['samlNameId'])) {
            $nameId = $_SESSION['samlNameId'];
        }
        if (isset($_SESSION['samlSessionIndex'])) {
            $sessionIndex = $_SESSION['samlSessionIndex'];
        }
        if (isset($_SESSION['samlNameIdFormat'])) {
            $nameIdFormat = $_SESSION['samlNameIdFormat'];
        }

        $auth->logout($returnTo, $paramters, $nameId, $sessionIndex, false, $nameIdFormat);

        $this->Session->setFlash(__('You are successfully signed out'), 'flash/info');
        $this->redirect(Configure::read('SSO.baseurl'));
    }

}
