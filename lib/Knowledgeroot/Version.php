<?php
/**
 * Knowledgeroot
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://knowledgeroot.org/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@knowledgeroot.org so we can send you a copy immediately.
 *
 * @category   Knowledgeroot
 * @package    Knowledgeroot_Version
 * @copyright  Copyright (c) 2011 Frank Habermann
 * @license    http://knowledgeroot.org/license/new-bsd     New BSD License
 * @version    $Id:$
 */

/**
 * Class to store and retrieve the version of Knowledgeroot.
 *
 * @category   Knowledgeroot
 * @package    Knowledgeroot_Version
 * @copyright  Copyright (c) 2011 Frank Habermann
 * @license    http://knowledgeroot.org/license/new-bsd     New BSD License
 */
final class Knowledgeroot_Version
{
    /**
     * Knowledgeroot version identification - see compareVersion()
     */
    const VERSION = '1.0.100';

    /**
     * The latest stable version Knowledgeroot available
     *
     * @var string
     */
    protected static $_latestVersion;

    /**
     * Compare the specified Knowledgeroot version string $version
     * with the current Knowledgeroot_Version::VERSION of Knowledgeroot.
     *
     * @param  string  $version  A version string (e.g. "0.7.1").
     * @return int           -1 if the $version is older,
     *                           0 if they are the same,
     *                           and +1 if $version is newer.
     *
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(self::VERSION));
    }

    /**
     * Fetches the version of the latest stable release
     *
     * @link http://www.knowledgeroot.org/download.html
     * @return string
     */
    public static function getLatest()
    {
        if (null === self::$_latestVersion) {
            self::$_latestVersion = 'not available';

            $handle = fopen('http://api.knowledgeroot.org/latest', 'r');
            if (false !== $handle) {
                self::$_latestVersion = stream_get_contents($handle);
                fclose($handle);
            }
        }

        return self::$_latestVersion;
    }
}
