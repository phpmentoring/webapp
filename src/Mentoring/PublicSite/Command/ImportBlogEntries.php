<?php

namespace Mentoring\PublicSite\Command;

use Mentoring\PublicSite\Blog\BlogEntry;
use Mentoring\PublicSite\Blog\BlogEntryHydrator;
use Mentoring\PublicSite\Blog\BlogService;
use Mni\FrontYAML\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Imports markdown files from the disk and converts them into blog entries
 *
 * @package Mentoring\PublicSite\Command
 */
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

    /**
     * ImportBlogEntries constructor.
     * @param BlogService $blogService
     * @param $blogDirectory
     */
    public function __construct(BlogService $blogService, $blogDirectory)
    {
        $this->blogService = $blogService;
        $this->blogDirectory = $blogDirectory;

        parent::__construct();
    }

    /**
     * Usage information for the command
     */
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

    /**
     * Imports the blog entries and cleans up removed ones
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting import...');
        $defaultData = [
            'author' => 'A PHP Developer',
            'email' => 'phpmentoring@gmail.com',
            'published' => 0,
        ];
        $pattern = $this->blogDirectory . '*.md';
        $files = glob($pattern);
        $parser = new Parser();
        $hydrator = new BlogEntryHydrator();
        $filenames = [];
        foreach ($files as $filePath) {
            $filename = basename($filePath);
            preg_match('/^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})\-([a-zA-Z-_]+)\.md$/', $filename, $matches);
            $document = $parser->parse(file_get_contents($filePath));
            $yaml = array_merge($defaultData, $document->getYAML());
            $postDate = new \DateTime($matches[1]);

            $data = [
                'filename' => $filename,
                'author' => $yaml['author'],
                'email' => $yaml['email'],
                'post_date' => $postDate->format('Y-m-d'),
                'published' => $yaml['published'],
                'slug' => $matches[2],
                'title' => $yaml['title'],
                'body' => $document->getContent(),
            ];
            $blogEntry = new BlogEntry();
            $blogEntry = $hydrator->hydrate($data, $blogEntry);
            $output->writeln('Saving ' . $filename . '...');
            $this->blogService->saveEntry($blogEntry);
            $filenames[] = $filename;
        }

        $output->writeln('Removing blog entries that no longer exist...');
        $this->blogService->massRemoveByFilename($filenames);

        $output->writeln('Finished');
    }
}
