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
 * Paso 9 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

/**
 * Hook a ejecutar antes del paso 9 de la instalación
 *
 * @param array &$data  Los datos a utilizar por las plantillas de tipo stepn
 */
function idpinstaller_hook_step9(&$data) {

    $conf_path = realpath(__DIR__ . '/../../../config/config.php');
    $idph_path = realpath(__DIR__ . '/../../../metadata/saml20-idp-hosted.php');
    $spr_path  = realpath(__DIR__ . '/../../../metadata/saml20-sp-remote.php');
    $idpr_path = realpath(__DIR__ . '/../../../metadata/saml20-idp-remote.php');
    $mods_path = realpath(__DIR__ . '/../../../modules'); 
    $cert_path = realpath(__DIR__ . '/../../../cert');
    $cert = $cert_path."/".$_SERVER['HTTP_HOST'].'.crt.pem';
    
    $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step9_finished}');    
    if (function_exists('posix_getgrnam')) {
        $aux = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step9_change_perms}');
        $print = false;
        if(@!chmod($conf_path, 0440)){
            $aux.= "<pre>&gt; chmod 440 " . $conf_path . "</pre>";
            $print = true;
        }
        if(@!chmod($idph_path, 0444)){
            $aux.= "<pre>&gt; chmod 444 " . $idph_path . "</pre>";
            $print = true;
        }
        if(@!chmod($spr_path, 0444)){
            $aux.= "<pre>&gt; chmod 444 " . $spr_path . "</pre>";
            $print = true;
        }
        if(@!chmod($idpr_path, 0444)){
            $aux.= "<pre>&gt; chmod 444 " . $idpr_path . "</pre>";
            $print = true;
        }
        if(@!chmod($mods_path, 0555)){
            $recursive5 = is_dir($mods_path) ? "-R" : "";
            $aux.= "<pre>&gt; chmod $recursive5 555 " . $mods_path . "</pre>";
            $print = true;
        }
        if(@!chmod($cert_path, 0540)){
            $recursive6 = is_dir($cert_path) ? "-R" : "";
            $aux.= "<pre>&gt; chmod $recursive6 540 " . $cert_path . "</pre>";
            $print = true;
        }
        $print ? $data['info'][] = $aux : $data['info'][] = "\n";
    }
    $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step9_remember}');
    $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step9_modified_files}');
    
    $aux  = "<pre>&gt;" . $conf_path . "</pre>";
    $aux .= "<pre>&gt;" . $idph_path . "</pre>";
    $aux .= "<pre>&gt;" . $spr_path  . "</pre>";
    $aux .= "<pre>&gt;" . $idpr_path . "</pre>";
    $data['info'][] = $aux;

    $url_meta       = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, -24) . "saml2/idp/metadata.php?output=xhtml";
    $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step7_all_info_extra}') . " <a href='$url_meta' target='_blank'>" . $data['ssphpobj']->t('{idpinstaller:idpinstaller:step7_here}') . "</a>";
    
    $url_init       = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, -24) . "module.php/core/frontpage_welcome.php";
    $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step9_url_init}') . " <a href='$url_init' target='_blank'>" . $data['ssphpobj']->t('{idpinstaller:idpinstaller:step7_here}') . "</a></br>";
    
    $data['info'][] = $data['ssphpobj']->t('{idpinstaller:idpinstaller:step9_remember_cert}').'<i>'.$cert_path.'</i>';
    $data['cert']   = file_get_contents($cert);
    return true;
}
