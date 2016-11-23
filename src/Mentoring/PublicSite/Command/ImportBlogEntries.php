<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mentoring\PublicSite\Command;

use Mentoring\PublicSite\Blog\BlogService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportBlogEntries extends Command
{
    /**
     * Directory containing the raw markdown files
     * @var string
     */
    protected $blogDirectory;

    /**
     * @var BlogService
     */
    protected $blogService;

    public function __construct(BlogService $blogService, $blogDirectory)
    {
        $this->blogService = $blogService;
        $this->blogDirectory = $blogDirectory;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName("mentoring:importBlogEntries")
             ->setDescription("Imports blog entries into the database and runs any needed cleanups")
             ->setHelp(<<<EOT
This command will parse any available markdown files and import them into the blog table. This will then clean up and 
remove any missing markdown files from the table as well.
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->blogDirectory);
        die();
        $output->writeln('Starting import...');
        // Glob all the files
        // Import them and insert or update as needed
        $output->writeln('Starting cleanup...');
        // Select all the filenames
        // See if they exist on disk
        // If not, delete

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

            $ch = curl_init('https://api.github.com/users?per_page=1&since=' . ($row['githubUid'] - 1));
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                // Depending on the number of entries you'd want to include a
                // GITHUB-API-TOKEN which you'd have to create first....
                // sprintf('Authorization: token %s', YOUR_GITHUB_TOKEN),
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
