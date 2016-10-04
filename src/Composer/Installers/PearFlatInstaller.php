<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Installer;

use Composer\IO\IOInterface;
use Composer\Composer;
use Composer\Downloader\PearPackageExtractor;
use Composer\Util\Platform;
use Composer\Installer\PearInstaller;
use Composer\Installer\LibraryInstaller;

/**
 * Package installation manager.
 *
 * @author Martin Rüegg <martin.rueegg@bristolpound.org>
 */
class PearFlatInstaller extends PearInstaller
{
    /**
     * Initializes library installer.
     *
     * @param IOInterface $io       io instance
     * @param Composer    $composer
     * @param string      $type     package type that this installer handles
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'pear-library')
    {
        parent::__construct($io, $composer, $type);
    }

    protected function installCode(PackageInterface $package)
    {
        LibraryInstaller::installCode($package);

        $isWindows = Platform::isWindows();
        $php_bin = $this->binDir . ($isWindows ? '/composer-php.bat' : '/composer-php');

        if (!$isWindows) {
            $php_bin = '/usr/bin/env ' . $php_bin;
        }

        $installPath = '/vendor/pear-flat'; //$this->getInstallPath($package);
        $vars = array(
            'os' => $isWindows ? 'windows' : 'linux',
            'php_bin' => $php_bin,
            'pear_php' => $installPath,
            'php_dir' => $installPath,
            'bin_dir' => $installPath . '/bin',
            'data_dir' => $installPath . '/data',
            'version' => $package->getPrettyVersion(),
        );

        $packageArchive = $this->getInstallPath($package).'/'.pathinfo($package->getDistUrl(), PATHINFO_BASENAME);
        $pearExtractor = new PearPackageExtractor($packageArchive);
        $pearExtractor->extractTo($installPath, array('php' => '/', 'script' => '/bin', 'data' => '/data'), $vars);

        $this->io->writeError('    Cleaning up', true, IOInterface::VERBOSE);
        $this->filesystem->unlink($packageArchive);
    }
}
