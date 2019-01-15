<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Install;

use Composer\Package\PackageInterface;
use OxidEsales\EshopCommunity\Internal\Utility\FactsContextInterface;
use Webmozart\PathUtil\Path;

/**
 * Class ModuleCopyService
 *
 * @package OxidEsales\EshopCommunity\Internal\Module\Setup\Install
 */
class ModuleCopyService implements ModuleCopyServiceInterface
{
    /** Used to determine third party package internal source path. */
    const EXTRA_PARAMETER_KEY_SOURCE = 'source-directory';

    /** Used to install third party integrations. */
    const EXTRA_PARAMETER_KEY_TARGET = 'target-directory';

    /** Glob expression to filter all files, might be used to filter whole directory. */
    const BLACKLIST_ALL_FILES = '**/*';

    /** Name of directory to be excluded for VCS */
    const BLACKLIST_VCS_DIRECTORY = '.git';

    /** Name of ignore files to be excluded for VCS */
    const BLACKLIST_VCS_IGNORE_FILE = '.gitignore';

    /** List of glob expressions used to blacklist files being copied. */
    const EXTRA_PARAMETER_FILTER_BLACKLIST = 'blacklist-filter';

    /** Glob filter expression to exclude VCS files */
    const BLACKLIST_VCS_DIRECTORY_FILTER = self::BLACKLIST_VCS_DIRECTORY . DIRECTORY_SEPARATOR . self::BLACKLIST_ALL_FILES;

    /** Directory for OXID eShop modules */
    const MODULES_DIRECTORY = 'modules';

    const EXTRA_PARAMETER_KEY_ROOT = 'oxideshop';

    /** @var PackageInterface */
    private $package;

    /** @var FactsContextInterface $context */
    private $context;

    /** @var CopyGlobServiceInterface $copyGlobService */
    private $copyGlobService;

    /**
     * ModuleCopyService constructor.
     *
     * @param PackageServiceInterface  $packageService
     * @param FactsContextInterface    $factsContext
     * @param CopyGlobServiceInterface $copyGlobService
     */
    public function __construct(
        PackageServiceInterface $packageService,
        FactsContextInterface $factsContext,
        CopyGlobServiceInterface $copyGlobService
    ) {
        $this->package = $packageService->getPackage();
        $this->context = $factsContext;
        $this->copyGlobService = $copyGlobService;
    }

    /**
     * Copies from vendor directory to source/modules directory respecting the blacklist filters given by the module.
     */
    public function copy()
    {
        $filtersToApply = [
            $this->getBlacklistFilterValue(),
            $this->getVCSFilter(),
        ];

        $this->copyGlobService->copy(
            $this->formSourcePath($this->package->getPackage($path)),
            $this->formTargetPath(),
            $this->getCombinedFilters($filtersToApply)
        );
    }

    /**
     * Return the value defined in composer extra parameters for blacklist filtering.
     *
     * @return array
     */
    private function getBlacklistFilterValue() : array
    {
        return $this->getExtraParameterValueByKey(static::EXTRA_PARAMETER_FILTER_BLACKLIST, []);
    }

    /**
     * Search for parameter with specific key in "extra" composer configuration block
     *
     * @param string $extraParameterKey
     * @param string $defaultValue
     *
     * @return array|string|null
     */
    private function getExtraParameterValueByKey($extraParameterKey, $defaultValue = null)
    {
        $extraParameters = $this->package->getExtra($path);

        $extraParameterValue = isset($extraParameters[static::EXTRA_PARAMETER_KEY_ROOT][$extraParameterKey])?
            $extraParameters[static::EXTRA_PARAMETER_KEY_ROOT][$extraParameterKey]:
            null;

        return (!empty($extraParameterValue)) ? $extraParameterValue : $defaultValue;
    }

    /**
     * Get VCS glob filter expression
     *
     * @return array
     */
    private function getVCSFilter()
    {
        return [self::BLACKLIST_VCS_DIRECTORY_FILTER, self::BLACKLIST_VCS_IGNORE_FILE];
    }

    /**
     * If module source directory option provided add it's relative path.
     * Otherwise return plain package path.
     *
     * @param string $packagePath
     *
     * @return string
     */
    private function formSourcePath($packagePath)
    {
        $sourceDirectory = $this->getExtraParameterValueByKey(static::EXTRA_PARAMETER_KEY_SOURCE);

        return !empty($sourceDirectory)?
            Path::join($packagePath, $sourceDirectory):
            $packagePath;
    }

    /**
     * @return string
     */
    private function formTargetPath() :string
    {
        $targetDirectory = $this->getExtraParameterValueByKey(
            static::EXTRA_PARAMETER_KEY_TARGET,
            $this->package->getName()
        );

        return Path::join($this->context->getSourcePath(), static::MODULES_DIRECTORY, $targetDirectory);
    }

    /**
     * Combine multiple glob expression lists into one list
     *
     * @param array $listOfGlobExpressionLists E.g. [["*.txt", "*.pdf"], ["*.md"]]
     *
     * @return array
     */
    private function getCombinedFilters($listOfGlobExpressionLists) : array
    {
        $filters = [];
        foreach ($listOfGlobExpressionLists as $filter) {
            $filters = array_merge($filters, $filter);
        }

        return $filters;
    }
}
