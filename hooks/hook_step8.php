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
 * Paso 8 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

require_once(realpath(__DIR__ . '/../../../www/_include.php'));

/**
 * Hook a ejecutar antes del paso 8 de la instalación
 *
 * @param array &$data  Los datos a utilizar por las plantillas de tipo stepn
 */
function idpinstaller_hook_step8(&$data) {
    $filename_sp_remote  = realpath(__DIR__ . '/../../../metadata/saml20-sp-remote.php');
    $filename_idp_remote = realpath(__DIR__ . '/../../../metadata/saml20-idp-remote.php');
    $perms_ko            = array();
    $dir_meta            = "https://md.sir2.rediris.es/hub/sir2-hub-metadata.xml";

    $ch      = curl_init($dir_meta);
    
    // this should be only a temporal workaround 
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xmldata = curl_exec($ch);

    if (curl_exec($ch) === false) {
        $data['errors'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step8_curl_error}');
        $data['errors'][] = "<ul><li>" . curl_error($ch) . "</ul></li>";
        return true;
    }
    curl_close($ch);

    $output = transaleXMLToSsPHP($xmldata);

    if (!is_writable($filename_sp_remote) || !is_readable($filename_sp_remote)) {
        array_push($perms_ko, $filename_sp_remote);
    }
    if (!is_writable($filename_idp_remote) || !is_readable($filename_idp_remote)) {
        array_push($perms_ko, $filename_idp_remote);
    }

    if (array_key_exists('saml20-sp-remote', $output) && array_key_exists('saml20-idp-remote', $output)) {
        $res = @file_put_contents($filename_sp_remote, $output['saml20-sp-remote'], FILE_APPEND);
        $res = $res && @file_put_contents($filename_idp_remote, $output['saml20-idp-remote'], FILE_APPEND);
    } else {
        $data['errors'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step8_curl_error2}');
    }
    if (!$res && count($perms_ko) > 0) {
        if (function_exists('posix_getgrnam')) {
            $aux       = "<br/>" . $data['ssphpobj']->t('{idpinstaller:idpinstaller:step8_error}');
            $aux .= "<br/>" . $data['ssphpobj']->t('{idpinstaller:idpinstaller:step4_perms_ko}');
            $filename  = $perms_ko[0];
            $recursive = is_dir($filename) ? "-R" : "";
            $aux.= "<pre>&gt; chown $recursive " . getFileUsername($filename) . ":" . getApacheGroup() . " $filename\n&gt; chmod $recursive g+rw " . $filename . "</pre>";
        }
        $data['errors'][] = $aux;
        $data['errors'][] = $data['ssphpobj']->t("{idpinstaller:idpinstaller:step1_remember_change_perms}");
    }
    if (count($data['errors']) == 0) {
        $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step8_all_ok}');
    }

    return $res;
}
