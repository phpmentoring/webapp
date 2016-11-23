<?php

namespace Mentoring\Auth\Controller;

use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Token\AccessToken;
use Mentoring\User\UserNotFoundException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    /**
     * Returns a list of e-mails associated with a user
     *
     * @param Github $provider
     * @param AccessToken $token
     * @return array
     */
    protected function getUserEmails(Github $provider, AccessToken $token)
    {
        $request = $provider->getAuthenticatedRequest('GET', 'https://api.github.com/user/emails', $token);
        $response = $provider->getResponse($request);

        return $response;
    }

    public function githubAction(Application $app, Request $request)
    {
        $clientID = getenv('GITHUB_API_KEY');
        $clientSecret = getenv('GITHUB_API_SECRET');
        $code = $request->query->get('code');

        $redirectUri = $request->getScheme() . '://' .$request->getHost();
        if (80 != $request->getPort()) {
            $redirectUri .= ':' . $request->getPort();
        }
        $redirectUri .= '/auth/github';

        $provider = new Github([
            'clientId' => $clientID,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
        ]);

        if (empty($code)) {
            $authUrl = $provider->getAuthorizationUrl(['scope' => ['user:email']]);
            $app['session']->set('oauth2state', $provider->getState());
            return $app->redirect($authUrl);
        } else {
            $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $userDetails = $provider->getResourceOwner($token);

            try {
                $user = $app['user.manager']->fetchUserByGithubUid($userDetails->getId());
                $user->setGithubName($userDetails->getNickname());
                $app['user.manager']->saveUser($user);
            } catch (UserNotFoundException $exception) {
                $email = null;
                foreach ($this->getUserEmails($provider, $token) as $providerEmail) {
                    if ($providerEmail['primary']) {
                        $email = $providerEmail['email'];
                        break;
                    }
                }

                $user = $app['user.manager']->createUser([
                    'email' => $email,
                    'roles' => ['ROLE_USER'],
                    'name' => $userDetails->getName(),
                    'githubUid' => $userDetails->getId(),
                    'githubName' => $userDetails->getNickname(),
                ]);

                $app['user.manager']->saveUser($user);
            }

            $app['session']->set('user', $user);
            return $app->redirect($app['url_generator']->generate('account.profile'));
        }
    }

    public function logoutAction(Application $app)
    {
        $app['session']->clear();
        return $app->redirect($app['url_generator']->generate('index'));
    }
}
