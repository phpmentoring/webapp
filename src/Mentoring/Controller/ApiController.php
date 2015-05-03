<?php

namespace Mentoring\Controller;

use Mentoring\User\UserHydrator;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    public function getMenteesAction(Application $app)
    {
        /** @var \Mentoring\User\UserService $userService */
        $userService = $app['user.manager'];

        $mentees = $userService->fetchMentees();

        $responseData = [];
        $hydrator = new UserHydrator();
        foreach($mentees as $mentee) {
            $data = $hydrator->extract($mentee);
            $responseData[] = $data;
        }

        return new Response(json_encode($responseData), 200, ['Content-Type' => 'application/json']);
    }

    public function getMentorsAction(Application $app)
    {
        /** @var \Mentoring\User\UserService $userService */
        $userService = $app['user.manager'];

        $mentors = $userService->fetchMentors();

        $responseData = [];
        $hydrator = new UserHydrator();
        foreach($mentors as $mentor) {
            $data = $hydrator->extract($mentor);
            $responseData[] = $data;
        }

        return new Response(json_encode($responseData), 200, ['Content-Type' => 'application/json']);
    }
}