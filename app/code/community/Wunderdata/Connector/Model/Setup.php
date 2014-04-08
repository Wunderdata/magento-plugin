<?php

class Wunderdata_Connector_Model_Setup extends Mage_Core_Model_Abstract
{
    public function checkEnvironment()
    {
        $report = array();
        $errors = array();

        $tmpDir = sys_get_temp_dir();
        $tmpDirWritable = is_writable($tmpDir);
        $freeSpace = disk_free_space($tmpDir)/1024/1024;

        $memoryLimit = ini_get('memory_limit');
        if (is_numeric($memoryLimit)) {
            $memoryLimitMB = $memoryLimit/1024/1024;
        } else {
            if (preg_match('#([0-9]+)([K|M|G])#i', $memoryLimit, $matches) !== false) {
                $unit = mb_strtolower($matches[2]);
                $value = $matches[1];
                switch ($unit) {
                    case 'k':
                        $memoryLimitMB = $value * 1024;
                        break;
                    case 'm':
                        $memoryLimitMB = $value;
                        break;
                    case 'g':
                        $memoryLimitMB = $value / 1024;
                        break;
                    default:
                        $memoryLimitMB = 'ERROR';
                        $errors['memory_limit'] = 'Unit unknown';
                }
            } else {
                $memoryLimitMB = 'ERROR';
                $errors['memory_limit'] = 'Not parseable';
            }
        }

        $report['tmpDir'] = $tmpDir;
        $report['tmpDirWritable'] = $tmpDirWritable;
        $report['freeSpace'] = $freeSpace;
        $report['memory_limit'] = $memoryLimitMB;

        $curl = extension_loaded('curl');
        $buzzCompatible = version_compare(PHP_VERSION, '5.3.0', '>=');

        $report['curl'] = $curl;
        $report['buzz'] = $buzzCompatible;

        if (!$curl && !$buzzCompatible) {
            $errors['httpClient'] = 'no buzz, no curl';
            $report['httpClient'] = 'Pull only';
        } else {
            $report['httpClient'] = 'Push available';
        }

        return array('facts' => $report, 'errors' => $errors);
    }


}