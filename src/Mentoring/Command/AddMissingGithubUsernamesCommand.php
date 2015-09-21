<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mentoring\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddMissingGithubUsernamesCommand extends Command
{
    protected function configure()
    {
        $this->setName("mentoring:addMissingGithubUsernames")
             ->setDescription("Add missing github-Usernames")
             ->setHelp(<<<EOT
Add missing github-Usernames based on the github-UserID
EOT
             );
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting to fetch github-Usernames');
        $data = parse_ini_file(__DIR__ . '/../../../.env');
        $pdo = new \PDO(
            'mysql:dbname=' . $data['DB_DBNAME'] . ';host=' . $data['DB_HOSTNAME'],
            $data['DB_USERNAME'],
            $data['DB_PASSWORD']
        );

        $select = $pdo->prepare('SELECT * FROM `users` WHERE `githubName` = ""');

        $update = $pdo->prepare('UPDATE `users` SET `githubName`= :githubName WHERE githubUid = :githubUid');

        $select->execute();
        $result = $select->fetchAll();

        foreach ($result as $row) {
            $output->writeln(sprintf(
                'Fetching username for user %s (%s)',
                $row['githubUid'],
                $row['name']
            ));

            $ch = curl_init('https://api.github.com/users?per_page=1&since=' . ($row['githubUid'] - 1));//Here is the file we are downloading, replace spaces with %20
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                sprintf('Authorization: token %s', $data['GITHUB_API_KEY']),
                'User-Agent: PHPMentoring-App',
            ));
            $info = curl_exec($ch); // get curl response
            curl_close($ch);

            var_Dump($info);
            $info = json_decode($info, true);

            var_Dump($info);
            if (! isset($info[0])) {
                throw new \Exception('Transport Error occured');
            }

            $update->execute(array(
                ':githubUid' => $row['githubUid'],
                ':githubName' => $info[0]['login'],
            ));
        }

        $output->writeln('Finished');
    }
}
