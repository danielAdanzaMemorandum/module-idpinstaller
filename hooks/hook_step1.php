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
 * Paso 1 del modulo instalador para SimpleSAMLphp v1.13.1
 * @package    IdPRef\modules\idpinstaller
 * @author     "PRiSE [Auditoria y Consultoria de privacidad y Seguridad, S.L.]"
 * @copyright  Copyright (C) 2014 - 2015 by the Spanish Research and Academic
 *             Network
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version    0.3-Sprint3-R57
 */

/**
 * Hook para ejecutar el paso 1 de la instalación. En este paso se deberá 
 * comprobar el estado de la instalación de SSPHP, siendo necesario comprobar:
 *   1) PHP version >= 5.3.0.
 *   2) PHP extensions:
 *     2.1) Siempre requeridas: date, dom, hash, libxml, openssl, pcre, SPL,
 *          zlib, mcrypt
 *     2.2) Para autenticar mediante un servidor LDAP: ldap
 *  // 2.3) Para autenticar mediante un servidor RADIUS: radius(no es necesaria)
 *     2.4) Para salvar información de sesion en un servidor memcache: memcache 
 *          (Indicar que será necesario para entornos balanceados)
 *     2.5) Para usar bases de datos:
 *          2.5.1) Siempre: PDO
 *          2.5.2) Database driver: (mysql, pgsql, ...)
 * 
 * Si no está activo ni LDAP ni BBDD => Error
 * Si está uno u otro => indicar que solo podrá usar authN según el activo
 * Si están los 2 => Palante
 *
 * Se deberán comprobar los permisos de los archivos que se deberán modificar
 * por el usuario
 * 
 * @param array &$data  Los datos a utilizar por las plantillas de tipo stepn
 * 
 */
function idpinstaller_hook_step1(&$data) {
    $ssphpobj = $data['ssphpobj'];
    //Comprobamos la versión de PHP
    if (version_compare(PHP_VERSION, "5.3.0", ">=") === false) {
        $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:general_error}');
        $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_error_version}');
    } else {
        //Continuamos comprobando las extensiones de PHP
        $extensions        = array("date", "dom", "hash", "libxml", "openssl", "pcre", "SPL", "zlib", "mcrypt", "posix");
        $failed_extensions = array();
        $loaded_extensions = get_loaded_extensions();
        foreach ($extensions as $extension) {
            if (!isExtensionActive($extension, $loaded_extensions)) {
                $failed_extensions[] = $extension;
            }
        }
        if (count($failed_extensions) > 0) {
            $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:general_error}');
            $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_error_extensions}') . implode(", ", $failed_extensions) . ".";
        } else {
            //Continuamos comprobando memcache (opcional)
            if (!isExtensionActive('memcached', $loaded_extensions)) {
                $data['warning'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_memcached_error}');
            }
            //Continuamos comprobando LDAP, Driver PDO
            $ldap    = isLDAPextensionActive($loaded_extensions);
            $pdo     = isPDOextensionActive($loaded_extensions);
            $modulos = $pdo ? isPDOextensionActive($loaded_extensions) : false;
            if ($ldap && $pdo && $modulos) {
                //Todo OK
            } else if (!$ldap && !$pdo) {
                //Todo KO, me dan igual los módulos, porq pdo no está activo
                $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:general_error}');
                $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_any_data_source_error}');
            } else if (!$ldap && $pdo && $modulos) {
                //Solo PDO
                $aux               = $ssphpobj->t('{idpinstaller:idpinstaller:step1_only_pdo}');
                $aux.= " " . $ssphpobj->t('{idpinstaller:idpinstaller:step1_only_pdo_modules_list}');
                $aux.= implode(", ", getPDOModulesActive($loaded_extensions)) . ".";
                $data['warning'][] = $aux;
            } else if (!$ldap && $pdo && !$modulos) {
                //Necesita modulos $pdo
                $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:general_error}');
                $data['errors'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_pdo_no_modules}');
            } else if ($ldap) {
                //Solo LDAP ($ldap y me da igual si $pdo o $modulos estan ok, 
                //ya se que algo falla por que no entré en caso 1
                $data['warning'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_only_ldap}');
            }
            $files    = array(
                "config/config.php",
                "config/authsources.php",
                "metadata/saml20-idp-hosted.php",
                "metadata/saml20-sp-remote.php",
                "modules",                
            );
            $perms_ko = array();
            $apachegroupname = getApacheGroup();
            foreach ($files as $file) {
                $f = realpath(__DIR__ . "/../../../" . $file);
                if (!is_writable($f) || !is_readable($f)) {
                    $actual_perms = fileperms($f);
                    $new_perms = $actual_perms | 0060 ;
                    @$changed_perm = chmod($f, $new_perms);
                    @$changed_grp = chgrp($f, $apachegroupname);
                    if(!($changed_perm && $changed_grp)){
                        $perms_ko[] = $f;
                    }
                }
            }
            if (count($perms_ko) > 0) {
                $aux = $ssphpobj->t('{idpinstaller:idpinstaller:step1_perms_ko}');
                $aux.= "<ul style='margin-top:30px;'><li>".implode("</li><li>",$perms_ko)."</li></ul>";
                $aux.= $ssphpobj->t('{idpinstaller:idpinstaller:step1_perms_ko2}');

                if(function_exists('posix_getgrnam')){                    
                    $aux.= "<br/>".$ssphpobj->t('{idpinstaller:idpinstaller:step1_perms_ko3}');
                    $filename = $perms_ko[0];
                    $username = getFileUsername($filename);
                    $groupname = getApacheGroup();                    
                    $recursive = is_dir($filename)?"-R":"";
                    $aux.= "<pre>&gt; chown $recursive ".$username.":".$groupname." $filename\n&gt; chmod $recursive g+rw ".$filename."</pre>";
                }
                $data['errors'][] = $aux;
                $data['errors'][] = $ssphpobj->t("{idpinstaller:idpinstaller:step1_remember_change_perms}");
            }
        }
    }
    
    //Aquí se configuran los idiomas que estarán disponibles para SSPHP
    $confile = __DIR__ . '/../../../config/config.php';
    include($confile);
    $config['language.available']     = ['es'];
    $res                              = @file_put_contents($confile, '<?php  $config = ' . var_export($config, 1) . "; ?>");
    
    if (count($data['errors']) == 0) {
        $data['info'][] = $ssphpobj->t('{idpinstaller:idpinstaller:step1_all_ok}');
    }
    return true;
}
