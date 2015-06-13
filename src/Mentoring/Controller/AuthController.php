<?php

namespace Mentoring\Controller;

use League\OAuth2\Client\Provider\Github;
use Mentoring\User\UserNotFoundException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
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
            'scopes' => ['user:email'],
        ]);

        if (empty($code)) {
            $authUrl = $provider->getAuthorizationUrl();
            $app['session']->set('oauth2state', $provider->state);
            return $app->redirect($authUrl);
        } else {
            $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $userDetails = $provider->getUserDetails($token);

            try {
                $user = $app['user.manager']->fetchUserByGithubUid($userDetails->uid);
            } catch (UserNotFoundException $exception) {
                $email = null;
                foreach ($provider->getUserEmails($token) as $providerEmail) {
                    if ($providerEmail->primary) {
                        $email = $providerEmail->email;
                        break;
                    }
                }

                $user = $app['user.manager']->createUser([
                    'email' => $email,
                    'roles' => ['ROLE_USER'],
                    'name' => $userDetails->name,
                    'githubUid' => $userDetails->uid,
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
