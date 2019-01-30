<?php
/**
 * Created by IntelliJ IDEA.
 * User: jakub.polomsky
 * Date: 30.01.2019
 * Time: 07:53
 */

namespace jakubpolomsky\phpRequirementsChecker;

use phpDocumentor\Reflection\File;


class Checker
{
    private $extensions = [];

    public function check($includeLoading = false)
    {
        $result = [];
        foreach ($this->extensions as $extension => $version) {
            $versionAvailable = @phpversion($extension);
            if (extension_loaded($extension) && StringFunctions::compareVersions($version, $versionAvailable)) {
                $result[$extension] = true;
            } else {
                if ($includeLoading) {
                    $result[$extension] = $this->loadExtension($extension);
                }
                $result[$extension] = false;
            }
        }
        return $result;
    }

    private function loadExtension($extension, $realExtensionName = null)
    {
        if ( ! function_exists('dl')) {
            return false;
        }

        $prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';

        return dl($prefix . ($realExtensionName ? $realExtensionName : $extension) . '.' . PHP_SHLIB_SUFFIX);
    }

    /**
     * @param String $string comma separated string of modules e.g. "intl,mcrypt,curl" or just a single dependency "intl"
     * @param String $delimiter optional delimiter
     * @return Checker
     */
    public function addDependenciesViaString($string, $delimiter = ",")
    {
        if (trim(strlen($string)) == 0) {
            return $this;
        }

        foreach (explode($delimiter, $string) as $extension) {
            $this->extensions[$extension] = '*';
        }

        return $this;
    }

    /**
     * @param $array
     * @return $this
     */
    public function addDependenciesViaArray($array)
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $extension => $version) {
                if (is_int($extension)) {
                    $extension = $version;
                    $version   = "*";
                }
                $this->extensions[$extension] = $version;
            }
        }
        return $this;
    }

    /**
     * @param string|File $composerFile
     * @param boolean $includeDev
     * @return Checker
     * @throws \Exception
     */
    public function addDependenciesViaComposerFile($composerFile, $includeDev = false)
    {
        $composerJson = null;
        if (is_string($composerFile) && is_file($composerFile)) {
            $composerJson = file_get_contents($composerFile);
        } elseif (trim(strlen($composerFile)) > 0) {
            $composerJson = $composerFile;
        } elseif ( ! StringFunctions::isValidJson($composerFile)) {
            throw new \Exception("Argument is not a valid composer.json file.");
        } else {
            throw new \Exception("Unknown error occured while adding dependencies via composer file.");
        }

        $composerJson = json_decode($composerJson);

        $requires = array();

        if (property_exists($composerJson, 'require')) {
            $requires = (array)$composerJson->require;
        }

        if ($includeDev && property_exists($composerJson, 'require-dev')) {
            $requires = array_merge($requires, (array)$composerJson->{'require-dev'});
        }

        foreach ($requires as $require => $version) {
            if (StringFunctions::startsWith($require, "ext")) {
                $this->extensions[StringFunctions::replaceFirstOccurence('ext-', '', $require)] = $version;
            }
        }

        return $this;
    }


}