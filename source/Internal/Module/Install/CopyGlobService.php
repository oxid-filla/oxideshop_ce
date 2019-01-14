<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Install;

use Composer\Package\PackageInterface;
use OxidEsales\ComposerPlugin\Utilities\CopyFileManager\CopyGlobFilteredFileManager;
use OxidEsales\Facts\Facts;
use Webmozart\PathUtil\Path;

/**
 * Class CopyGlobService
 *
 * @package OxidEsales\EshopCommunity\Internal\Module\Setup\Install
 */
class CopyGlobService implements CopyGlobServiceInterface
{
    /**
     * Copy files/directories from source to destination.
     *
     * @param string $sourcePath         Absolute path to file or directory.
     * @param string $destinationPath    Absolute path to file or directory.
     * @param array  $globExpressionList List of glob expressions, e.g. ["*.txt", "*.pdf"].
     *
     * @throws \InvalidArgumentException If given $sourcePath is not a file.
     *
     */
    public function copy(string $sourcePath, string $destinationPath, array $globExpressionList = [])
    {
        if (!file_exists($sourcePath)) {
            $message = "Given value \"$sourcePath\" is not a valid source path entry. ".
                       "Valid entry must be an absolute path to an existing file or directory.";

            throw new \InvalidArgumentException($message);
        }

        if (is_dir($sourcePath)) {
            self::copyDirectory($sourcePath, $destinationPath, $globExpressionList);
        } else {
            self::copyFile($sourcePath, $destinationPath, $globExpressionList);
        }
    }

    /**
     * Copy whole directory using given glob filters.
     *
     * @param string $sourcePath         Absolute path to directory.
     * @param string $destinationPath    Absolute path to directory.
     * @param array  $globExpressionList List of glob expressions, e.g. ["*.txt", "*.pdf"].
     */
    private static function copyDirectory($sourcePath, $destinationPath, $globExpressionList)
    {
        $filesystem = new Filesystem();

        $flatFileListIterator = self::getFlatFileListIterator($sourcePath);
        $filteredFileListIterator = new BlacklistFilterIterator(
            $flatFileListIterator,
            $sourcePath,
            $globExpressionList
        );

        $filesystem->mirror($sourcePath, $destinationPath, $filteredFileListIterator, ["override" => true]);
    }

    /**
     * Copy file using given glob filters.
     *
     * @param string $sourcePathOfFile   Absolute path to file.
     * @param string $destinationPath    Absolute path to directory.
     * @param array  $globExpressionList List of glob expressions, e.g. ["*.txt", "*.pdf"].
     */
    private static function copyFile($sourcePathOfFile, $destinationPath, $globExpressionList)
    {
        $filesystem = new Filesystem();

        $relativeSourcePath = self::getRelativePathForSingleFile($sourcePathOfFile);

        if (!GlobMatcher::matchAny($relativeSourcePath, $globExpressionList)) {
            $filesystem->copy($sourcePathOfFile, $destinationPath, ["override" => true]);
        }
    }
}
