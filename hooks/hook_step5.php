<?php
/*
 *  IdPRef - IdP de Referencia para SIR 2 basado en SimpleSAMLPHP v1.13.1
 * =========================================================================== *
 *
 * Copyright (C) 2014 - 2015 by the Spanish Research and Academic Network.
 * This code was developed by Auditoria y Consultoría de Privacidad y Seguridad
 * (PRiSE http://www.prise.es) for the RedIRIS SIR service (SIR: 
 * http://www.rediris.es/sir)
 *
 * *****************************************************************************
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * ************************************************************************** */

/** 
 * Paso 5 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

/**
 * Hook a ejecutar antes del paso 5 de la instalación
 * Extrae cuales son las fuentes de datos principales que podría utilizarse
 *
 * @param array &$data  Los datos a utilizar por las plantillas de tipo stepn
 */
function idpinstaller_hook_step5(&$data) {
    $data['datasources'] = getDataSources();
    $require_mods = array("saml", "idpinstaller", "modinfo", "ldap", "sqlauth", "core", "portal", "sir2skin"); //Modulos obligatorios
    $ssphpobj     = $data['ssphpobj'];
    $modules      = SimpleSAML_Module::getModules();
    sort($modules);
    $perms_ko     = array();
    $modules_ko   = array();
    
    foreach ($modules as $m) {
        $f = realpath(__DIR__ . '/../../' . $m);

        if ((!file_exists($f . '/default-disable') && (!file_exists($f . '/default-enable')) && in_array($m, $require_mods))) {
            $modules_ko[] = $f;
        } elseif ((file_exists($f . '/default-disable') && !is_writable($f . '/default-disable')) || (file_exists($f . '/default-enable') && !is_writable($f . '/default-enable'))) {
            $perms_ko[] = $f;
        } else {
            if (in_array($m, $require_mods)) { //PARA LOS QUE SI QUEREMOS ACTIVAR
                if (file_exists($f . '/default-disable')) {

                    @unlink($f . '/default-disable');
                    @touch($f . '/default-enable');

                    if (!file_exists($f . '/default-enable')) {
                        $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step4_error}');
                    }
                }
            } else { //PARA LOS QUE QUEREMOS DESACTIVAR
                if (file_exists($f . '/default-enable')) {

                    @unlink($f . '/default-enable');
                    @touch($f . '/default-disable');

                    if (!file_exists($f . '/default-disable')) {
                        $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step4_error}');
                    }
                }
            }
        }
    }
    if (count($modules_ko) > 0) {
        $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step4_error}');
    } elseif (count($perms_ko) > 0) {
        if (function_exists('posix_getgrnam')) {
            $aux = "<br/>" . $ssphpobj->t('{idpinstaller:idpinstaller:step4_perms_ko}');
            $filename = $perms_ko[0];
            $file_owner = posix_getpwuid(fileowner($filename));
            $group = posix_getgrgid(posix_getgid());
            $recursive = is_dir($filename) ? "-R" : "";
            $aux.= "<pre>&gt; chown $recursive " . $file_owner['name'] . ":" . $group['name'] . " $filename\n&gt; chmod $recursive g+w " . $filename . "</pre>";
        }
        $data['errors'][] = $aux;
        $data['errors'][] = $ssphpobj->t("{idpinstaller:idpinstaller:step1_remember_change_perms}");
    }
    if (count($data['errors']) == 0) {
        $data['info'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step4_all_ok}');
    } /*else {
        $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step4_error}');
    }*/
    return true;
}
