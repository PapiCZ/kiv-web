<?php

namespace Core;

class Storage
{
    /**
     * @var string
     */
    private $storageName;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * Storage constructor
     *
     * @param string $storageName
     * @param string $rootPath
     */
    public function __construct(string $storageName, string $rootPath = __DIR__ . '/../storage/')
    {
        $this->storageName = $storageName;
        $this->rootPath = $rootPath;

        try {
            mkdir($this->rootPath . '/' . $this->storageName, 0755, true);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param string $sourceFile
     * @param string $targetRelativePathToStorage
     * @return bool
     */
    public function moveToStorage(string $sourceFile, string $targetRelativePathToStorage): bool
    {
        try {
            mkdir(dirname($this->rootPath . '/' . $this->storageName . '/' . $targetRelativePathToStorage), 0755, true);
        } catch (\Exception $e) {
        }

        return rename($sourceFile, $this->rootPath . '/' . $this->storageName . '/' . $targetRelativePathToStorage);
    }
}
