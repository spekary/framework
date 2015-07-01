<?php

/**
 * Routines to assist in the installation of various parts of QCubed.
 *
 */

namespace QCubed\Devtools;

use Composer\Installer\PackageEvent;


$__CONFIG_ONLY__ = true;
require_once(dirname(__FILE__) . '/../../qcubed.inc.php');	// get the configuration options so we can know where to put the plugin files

class Installer {

	public static function postPackageInstall(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getPackage();
		$strPackageName = $installedPackage->getName();
		echo 'Found ' . $strPackageName . "/n";
		self::ComposerPluginInstall($strPackageName);
	}

	public static function postPackageUpdate(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getPackage();
		$strPackageName = $installedPackage->getName();
		echo 'Found ' . $strPackageName . "/n";
		self::ComposerPluginInstall($strPackageName);
	}

	public static function postPackageUninstall(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getPackage();
		$strPackageName = $installedPackage->getName();
		echo 'Found ' . $strPackageName . "/n";
		self::ComposerPluginUninstall($strPackageName);
	}

	/**
	 * Move files out of the vendor directory and into the project directory that are in the plugin's install directory.
	 * @param $strPackageName
	 */
	public static function ComposerPluginInstall ($strPackageName) {
		// recursively copy the contents of the install directory, providing each file is not there.
		$strPluginDir = dirname(__FILE__).'/../../plugin/' . $strPackageName . '/install';
		$strDestDir = __INCLUDES__ . '/plugins';

		self::copy_dir($strPluginDir, $strDestDir);
	}

	protected static function copy_dir($src,$dst) {
		$dir = opendir($src);

		if (!file_exists($dst)) {
			mkdir($dst);
		}
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					self::copy_dir($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					if (!file_exists($dst . '/' . $file)) {
						copy($src . '/' . $file,$dst . '/' . $file);
					}
				}
			}
		}
		closedir($dir);
	}

	public static function ComposerPluginUninstall ($strPackageName) {
		// recursively delete the contents of the install directory, providing each file is there.
		$strPluginDir = dirname(__FILE__).'/../../plugin/' . $strPackageName . '/install';
		$strDestDir = __INCLUDES__ . '/plugins';

		self::remove_matching_dir($strPluginDir, $strDestDir);
	}

	protected static function remove_matching_dir($src,$dst) {
		if (!$dst || !$src) return;	// prevent deleting an entire disk by accidentally calling this with an empty string!
		$dir = opendir($src);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					remove_dir($dst . '/' . $file);
				}
				else {
					if (file_exists($dst . '/' . $file)) {
						unlink($dst . '/' . $file);
					}
				}
			}
		}
		closedir($dir);
	}

	protected static function remove_dir($dst) {
		if (!$dst) return;	// prevent deleting an entire disk by accidentally calling this with an empty string!
		$dir = opendir($dst);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($dst . '/' . $file) ) {
					remove_dir($dst . '/' . $file);
				}
				else {
					unlink($dst . '/' . $file);
				}
			}
		}
		closedir($dir);
		rmdir($dst);
	}



}
