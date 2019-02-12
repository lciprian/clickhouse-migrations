<?php

namespace Serkarn\ClickhouseMigrations\Migrations;

class FileModel
{
    
    /**
     *
     * @var \League\Flysystem\Filesystem
     */
    protected $fileSystem;

    /**
     *
     * @var \League\Flysystem\Adapter\Local
     */
    protected $adapter;

    /**
     * 
     * File model constructor
     */
    public function __construct()
    {
        $this->adapter = new \League\Flysystem\Adapter\Local(getcwd());
        $this->fileSystem = new \League\Flysystem\Filesystem($this->adapter);
    }
    
    /**
     * 
     * @param string $path
     * @return string
     */
    public function read(string $path): string
    {
        return (string) $this->fileSystem->read($path);
    }
    
    /**
     * 
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function put(string $path, string $contents, array $config = []): bool
    {
        return (bool) $this->fileSystem->put($path, $contents, $config);
    }
    
    /**
     * 
     * @param string $directory
     * @param bool $hidden
     * @return array
     */
    public function files(string $directory, bool $hidden = false): array
    {
        return iterator_to_array(
            \Symfony\Component\Finder\Finder::create()->files()->ignoreDotFiles(! $hidden)->in($directory)->depth(0)->sortByName(),
            false
        );
    }
    
    /**
     * 
     * @return string
     */
    public function getPathPrefix(): string
    {
        return $this->adapter->getPathPrefix();
    }
    
}
